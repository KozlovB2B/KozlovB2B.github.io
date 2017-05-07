<?php
namespace app\modules\user\controllers;

use Yii;
use app\modules\core\components\BaseController;

/**
 * Class AdminController
 *
 * Контроллер админа
 *
 * @package app\modules\user\controllers.
 */
class AdminController extends BaseController
{

    /**
     * @return string
     */
    public function actionDashboard()
    {
        $this->checkAccess('user___admin__dashboard');

        return $this->render('dashboard');
    }

}
