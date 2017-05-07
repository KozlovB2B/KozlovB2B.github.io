<?php

namespace app\modules\integration\modules\widget\controllers;

use app\modules\core\components\CoreController;
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
        $this->checkAccess('integration___integration__manage');
        return $this->render('index');
    }
}
