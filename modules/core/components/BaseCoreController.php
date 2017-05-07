<?php

namespace app\modules\core\components;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\modules\user\models\User;
use app\modules\user\models\profile\ProfileRelation;
use app\modules\user\components\ProfileGenerator;

class BaseCoreController extends Controller
{
    public $func_panel = [];

    public function init(){
        parent::init();

        if (YII_ENV == "prod" && !Yii::$app->getRequest()->getIsSecureConnection()) {
            Yii::$app->end(0, $this->redirect('https://' . Yii::$app->getRequest()->getServerName() . Yii::$app->getRequest()->getUrl(), 301));
        }

        Yii::$app->getModule('core');
        Yii::$app->getModule('user');
        Yii::$app->getModule('site');
        Yii::$app->getModule('script');
        Yii::$app->getModule('integration');
        Yii::$app->getModule('billing');
        Yii::$app->getModule('aff');

        Yii::$app->assetManager->forceCopy = YII_DEBUG;

        if (Yii::$app->getUser()->getIsGuest()) {
            $this->layout = "@app/modules/site/views/layouts/public";
        } else {

            /** @var User $user */
            $user = Yii::$app->getUser()->identity;

            // Если пользователь перешел на вторую версию - редиректим его на новый домен и аутентифицируем там
            if (!$user->v2) {
                Yii::$app->getUser()->identity->v2 = 1;

                Yii::$app->getUser()->identity->update(false, ['v2']);
            }

            if (!ProfileRelation::findOne(Yii::$app->getUser()->getId())) {
                ProfileGenerator::generate();
            }

            $this->layout = $user->profile->getLayout();
        }
    }

    /**
     * @param string $layout Устанавливает обертку
     */
    protected function layout($layout)
    {
        $this->layout = "@app/modules/site/views/layouts/" . $layout;
    }

    /**
     * Показывает сообщение пользователю как целую страницу
     *
     * @param null $type
     * @param null $message
     * @return string
     */
    protected function message($type = null, $message = null)
    {
        if ($type && $message) {
            Yii::$app->session->setFlash($type, $message);
        }

        $this->layout('standalone_public');

        return $this->render('@app/modules/site/views/site/message');
    }

    /**
     * RBAC проверка доступности кода.
     * Рубит процесс, если нет доступа
     * к действию, выводит ошибку 403
     *
     * @param $auth_item - элемент авторизации RBAC дерева
     * @param array $params
     * @return bool
     */
    public function checkAccess($auth_item, $params = [])
    {
        if (!\Yii::$app->getUser()->getId()){
            Yii::$app->end(200, $this->redirect('/'));
        }

        if (!\Yii::$app->getUser()->can($auth_item, $params)) {
            throw new ForbiddenHttpException("Access denied" . (YII_DEBUG ? " for " . $auth_item : null));
        }
    }

    /**
     * Проверяет на аяксовость
     *
     * @return mixed - аякс или нет
     */
    public function isAjax()
    {
        return \Yii::$app->getRequest()->getIsAjax();
    }

    /**
     * Access denied if not ajax
     *
     * @param bool $message
     * @throws Exception
     */
    public function denyNotAjax($message = false)
    {
        if (!$this->isAjax()) {
            throw new Exception(403, $message ? $message : 'Access denied.');
        }
    }


    /**
     * Performs ajax validation.
     * @param Model $model
     * @throws \yii\base\ExitException
     */
    protected function ajaxValidation(Model &$model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $errors = ActiveForm::validate($model);
            if ($errors) {
                echo json_encode(ActiveForm::validate($model));
                Yii::$app->end();
            }
        }
    }

    /**
     * Показывает пользователю сообщение системы.
     *
     * @param string $message
     * @param array $additional_data [optional] дополнительные данные для передачи вместе с сообщением пользователю
     * @return string
     */
    public function result($message = 'OK', $additional_data = [])
    {
        if ($this->isAjax()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            echo Json::encode(ArrayHelper::merge(['status' => 200, 'message' => $message], $additional_data));
            Yii::$app->end();
        } else {
            return $message;
        }
    }
}
