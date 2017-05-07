<?php
namespace app\modules\integration\modules\apiv2\controllers;

use app\modules\integration\modules\apiv2\components\BaseController;
use app\modules\script\models\ar\Script;
use Yii;

/**
 * ScriptController
 */
class ScriptController extends BaseController
{
    /**
     * @return array
     */
    public function actionList()
    {
        /** @var Script[] $scripts */
        $scripts = Script::find()->byAccount($this->_user_head_manager->id)->active()->all();

        $result = [];

        foreach ($scripts as $s) {
            $result[] = [
                'id' => $s->id,
                'name' => $s->name,
                'published' => $this->_user_head_manager->create_builds_manually ? (int)(!!$s->latest_release) : 1,
            ];
        }

        return $result;
    }
}