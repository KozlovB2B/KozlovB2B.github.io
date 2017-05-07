<?php
namespace app\modules\api\v1\controllers;

use app\modules\api\v1\components\BaseController;
use app\modules\script\models\ar\Script;
use Yii;
use yii\data\ActiveDataProvider;
use  \app\modules\billing\models\Account as BillingAccount;


/**
 * ScriptController
 */
class ScriptController extends BaseController
{
    /**
     * Возвращает список скриптов пользователя
     *
     * @param bool|false $all
     * @return array
     */
    public function actionList()
    {
        /** @var Script[] $scripts */
        $scripts = Script::find()->activeByUserCriteria($this->_user_head_manager->id)->all();

        $result = [];

        foreach ($scripts as $s) {
            $result[] = [
                'id' => $s->id,
                'name' => $s->name,
                'published' => (int)(!!$s->latest_release),
            ];
        }

        return $result;
    }

    /**
     * Scripts table
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect('/');

        $this->initGui();

        return $this->render('index', [
            'scripts_data_provider' => new ActiveDataProvider([
                'query' => Script::find()->publishedByUserCriteria($this->_user_head_manager->id),
            ]),
            'billing' => BillingAccount::findOne($this->_user_head_manager->id)
        ]);
    }
}