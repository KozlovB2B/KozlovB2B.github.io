<?php

namespace app\modules\integration\modules\recorder\controllers;

use app\modules\core\components\CoreController;
use app\modules\user\models\UserHeadManager;
use Yii;

/**
 * ApiCredentialsController implements the CRUD actions for ApiCredentials model.
 */
class DefaultController extends CoreController
{

    /**
     * Lists all ApiCredentials models.
     * @return mixed
     */
    public function actionIndex()
    {
        $enabled = false;
        if (!Yii::$app->getUser()->getIsGuest()) {
            $hm = UserHeadManager::findHeadManagerByUser();
            if ($hm) {
                $enabled = $hm->record_calls;
            }

        }

        return $this->render('index', ['enabled' => $enabled]);
    }

    public function actionEnable()
    {
        $this->checkAccess('integration___integration__manage');
        $hm = UserHeadManager::findHeadManagerByUser();
        $hm->record_calls = 1;
        $hm->update(false, ['record_calls']);
    }

    public function actionDisable()
    {
        $this->checkAccess('integration___integration__manage');
        $hm = UserHeadManager::findHeadManagerByUser();
        $hm->record_calls = 0;
        $hm->update(false, ['record_calls']);
    }
}
