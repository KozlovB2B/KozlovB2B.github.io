<?php

namespace app\modules\integration\modules\hookz\controllers;

use app\modules\core\components\CoreController;
use app\modules\integration\models\EnabledList;
use Yii;
use app\modules\user\models\UserHeadManager;

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
        $this->checkAccess('integration___integration__manage');
        $head_manager = UserHeadManager::findHeadManagerByUser();
        return $this->render('index', ['enabled' => EnabledList::findOrCreate($head_manager->id)->isEnabled('hookz')]);
    }
}
