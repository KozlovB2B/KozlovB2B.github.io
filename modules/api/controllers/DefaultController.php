<?php
namespace app\modules\api\controllers;

use yii\web\Controller;

/**
 * Class DefaultController
 * @package app\modules\api\controllers
 */
class DefaultController extends Controller
{
    /**
     * @var string last version of the API
     */
    protected $_last_version = 'v1';

    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect('/api/' . $this->_last_version);
    }
}