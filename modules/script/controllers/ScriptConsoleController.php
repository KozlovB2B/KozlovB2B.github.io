<?php
namespace app\modules\script\controllers;

use app\modules\script\models\ar\Node;
use app\modules\script\models\ar\Script;
use app\modules\user\models\User;
use Yii;
use yii\console\Controller;

class ScriptConsoleController extends Controller
{
    /**
     * @param null $command
     * @return int
     */
    public function actionIndex($command = null)
    {
        echo "Добро пожаловать в контроллер скриптов." . PHP_EOL;

        return 0;
    }

    public function actionCalc()
    {
        $models = Script::find()->all();
        echo 'Считаю количество нод по скриптам...' . PHP_EOL;
        foreach ($models as $m) {
            $m->calculateNodesCount();
            echo $m->nodes_count;
            $m->update(false, ['nodes_count']);
        }

    }

    /**
     * End trials
     *
     * DEV php yii billing/end-trials
     *
     * PROD php /home/ss/apps/ss/yii_prod script/check-duplicates
     *
     * @return int
     */
    public function actionCheckDuplicates()
    {
        $nodes = Node::find()->all();

        $grouping = [];

        foreach ($nodes as $n) {
            if (empty($grouping[$n->script_id . '_' . $n->number])) {
                $grouping[$n->script_id . '_' . $n->number] = [];
            }

            $grouping[$n->script_id . '_' . $n->number][] = $n;
        }

        $duplicates_fount = 0;

        foreach ($grouping as $grouped_by => $duplicates) {
//            echo count($duplicates).PHP_EOL;
            if (count($duplicates) > 1) {
                list($script_id, $number) = explode('_', $grouped_by);
                $script = Script::findOne($script_id);
                $user = User::findOne($script->user_id);
                $duplicates_fount++;
                $this->stdout('В скрипте #' . $script_id . ' - ' . $script->name . ' содержится ' . (count($duplicates) - 1) . ' дубля для узла ' . $duplicates[0]->number . '. Пользователь: ' . $script->user_id . ' ' . $user->username . PHP_EOL);

//                for($i = 1; $i< count($duplicates); $i++){
//                    $duplicates[$i]->delete();
//                }
            }
        }

        $this->stdout('Всго найдено дупликатов: ' . $duplicates_fount . PHP_EOL);
    }
}