<?php
namespace app\modules\api\v1\controllers;

use Yii;
use yii\web\Controller;

/**
 * DefaultController
 */
class DefaultController extends Controller
{
    public function init()
    {
        $this->layout = "@app/modules/api/views/layout/default";

        parent::init();
    }


    public function actionIndex()
    {
        return $this->render('doc');
    }
}