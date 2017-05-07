<?php

namespace app\modules\integration\controllers;

use app\modules\core\components\CoreController;
use app\modules\integration\models\EnabledList;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\web\Response;

/**
 * ApiCredentialsController implements the CRUD actions for ApiCredentials model.
 */
class EnabledListController extends CoreController
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['enable', 'disable'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ]
        ];
    }

    /**
     * @param $module
     * @return bool
     */
    public function actionEnable($module)
    {
        $this->checkAccess('integration___integration__manage');

        $head_manager = UserHeadManager::findHeadManagerByUser();
        return EnabledList::enable($head_manager->id, $module);
    }

    /**
     * @param $module
     * @return bool
     */
    public function actionDisable($module)
    {
        $this->checkAccess('integration___integration__manage');

        $head_manager = UserHeadManager::findHeadManagerByUser();
        return EnabledList::disable($head_manager->id, $module);
    }
}
