<?php

namespace app\modules\site\controllers;

use app\modules\core\components\BaseCoreController;
use Yii;
use app\modules\user\models\UserHeadManager;


class UserHeadManagerController extends BaseCoreController
{

    public function actionEnableHitsReport()
    {
        $this->checkAccess('integration___integration__manage');
        $hm = UserHeadManager::findHeadManagerByUser();
        $hm->hits_report = 1;
        $hm->update(false, ['hits_report']);
    }

    public function actionDisableHitsReport()
    {
        $this->checkAccess('integration___integration__manage');
        $hm = UserHeadManager::findHeadManagerByUser();
        $hm->hits_report = 0;
        $hm->update(false, ['hits_report']);
    }

}
