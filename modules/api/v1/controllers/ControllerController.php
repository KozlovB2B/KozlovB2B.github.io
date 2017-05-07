<?php
namespace app\modules\api\v1\controllers;

use yii\web\Controller;
use yii\web\Response;
use Yii;

/**
 * Class DefaultController
 * @package app\modules\api\controllers
 */
class ControllerController extends Controller
{
    /**
     * @return array
     */
    public function actionMethod()
    {
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Yii::$app->request->get();
    }
}