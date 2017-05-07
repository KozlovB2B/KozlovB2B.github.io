<?php

namespace app\modules\core\components;

use app\modules\user\components\ProfileGenerator;
use app\modules\user\models\profile\Profile;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\base\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\modules\user\models\User;
use app\modules\user\models\profile\ProfileRelation;

class CoreController extends Controller
{
    /**
     * @var array Содержимое панели функций
     */
    public $func_panel = [];


    /**
     * @var array Модалы вставляются перед закрывающим тегом </body>
     */
    public $modals = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
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
        Yii::$app->getModule('crm');
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

            $this->layout = $user->getProfile()->getLayout();
        }
    }


    /**
     * Проверяет наличие обязательных данных в $_POST массиве
     *
     * @param array $data
     */
    public function checkRequiredData($data = [])
    {
        if (is_string($data)) {
            if (!isset($_REQUEST[$data]) || empty($_REQUEST[$data])) {
                $this->throwException('Необходимо передать ' . $data, 402);
            }
        } else if (is_array($data)) {
            foreach ($data as $param) {
                if (!isset($_REQUEST[$param]) || empty($_REQUEST[$param])) {
                    $this->throwException('Необходимо передать ' . $param, 402);
                }
            }
        } else {
            $this->throwException('Controller::checkRequiredData($data) $data должен быть строкой или массивом');
        }
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
        if (!Yii::$app->getUser()->getId()) {
            Yii::$app->end(200, $this->redirect('/'));
        }

        if(Yii::$app->getUser()->getIdentity()->getIsBlocked()){
            Yii::$app->getUser()->logout();
            Yii::$app->end(200, $this->redirect('/'));
        }

        if (!Yii::$app->getUser()->can($auth_item, $params)) {
            Yii::$app->end(200, $this->redirect('/'));
//            $this->throwException("Access denied." . (YII_DEBUG ? " for " . $auth_item : null));
        }
    }

    /**
     * Проверяет на аяксовость
     *
     * @return mixed - аякс или нет
     */
    public function isAjax()
    {
        return Yii::$app->getRequest()->getIsAjax();
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
     * Сохраняет переданную модель в базу,
     * с выводом сообщения об ошибке, если что-то не так
     * Если задано сообщение, то заканчивает скрипт выводом сообщения
     *
     * @param ActiveRecord $model модель для сохранения
     * @param string $message [optional] сообщение для вывода пользователю
     * @param array $additional_data [optional] дополнительные данные для передачи их вместе с сообщением
     */
    public function saveModel($model, $message = "", $additional_data = [])
    {
        if ($model->save()) {
            if (!empty($message)) {
                $additional_data['id'] = isset($model->id) ? $model->id : null;

                return $this->result($message, $additional_data);
            }
        } else {
            $this->throwException(Html::errorSummary($model), 402);
        }
    }

    /**
     * Кидает пользователю исключение.
     * Если запрос аяксовый - кидает аяксовое исключение,
     * Если не аякс - кидает простое исключение...
     *
     * @param bool|string $message - сообщение об ошибке
     * @param bool|int $code - код ошибки
     * @param bool|string $type - тип эксепшна
     * @throws
     */
    public function throwException($message = 'Что-то сломалось...', $code = 500, $type = 'Exception')
    {
        if ($this->isAjax()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            echo Json::encode([
                'status' => $code,
                'message' => $message
            ]);
            Yii::$app->end();
        } else {
            throw new Exception($message, $code);
        }
    }


    /**
     * Performs ajax validation.
     * @param Model $model
     * @throws Yii\base\ExitException
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


    /**
     * Sets public clean layout
     */
    public function setCleanLayout()
    {
        $this->layout = "@app/modules/site/views/layouts/public_clean";
    }

    /**
     * Sets public layout
     */
    public function setPublicLayout()
    {
        $this->layout = "@app/modules/site/views/layouts/public";
    }
}
