<?php
namespace app\modules\api\v1\components;

use app\modules\script\models\ApiToken;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use app\modules\user\models\UserHeadManager;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * BaseController
 */
class BaseController extends Controller
{
    /** @inheritdoc */
    public $enableCsrfValidation = false;

    /** @var UserHeadManager */
    protected $_user_head_manager;

    /** @inheritdoc */
    public function initGui()
    {
        Yii::$app->response->format = Response::FORMAT_HTML;
        $this->layout = "@app/modules/api/views/layout/gui";
        Yii::$app->getModule('site');
        Yii::$app->getModule('script');
        Yii::$app->getModule('billing');
    }

    /**
     * Checks key and load user model
     *
     * @throws BadRequestHttpException
     */
    private function auth()
    {
        $key = Yii::$app->getRequest()->get('key', false);
        if ($key) {
            $this->authByKey($key);
        } else {
            $this->authByUser();
        }
    }

    /**
     * Auht by key
     *
     * @param $key
     * @throws BadRequestHttpException
     */
    private function authByKey($key)
    {
        $token = ApiToken::getByToken($key);

        if (!$token) {
            throw new BadRequestHttpException('В параметре key передан неверный ключ API');
        }

        $this->_user_head_manager = UserHeadManager::findOne($token->id);

        if (!$this->_user_head_manager) {
            throw new BadRequestHttpException('Нет пользователя с таким ключом API');
        }
    }

    /**
     * Auth as normal session
     *
     * @throws BadRequestHttpException
     */
    private function authByUser()
    {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->end(0, $this->redirect(Url::to(['/api/v1/auth/auth'])));
        } else {
            $this->_user_head_manager = UserHeadManager::findHeadManagerByUser(Yii::$app->getUser()->getId());

            if(!$this->_user_head_manager){
                throw new BadRequestHttpException('Head manager not found');
            }
        }
    }

    /**
     * Sets response format
     *
     * @throws BadRequestHttpException
     */
    protected function setFormat()
    {
        $formats = $this->availableResponseFormats();
        $format = Yii::$app->getRequest()->get('format', 'json');

        if (!isset($formats[$format])) {
            throw new BadRequestHttpException('Указан неизвестный формат! Формат может быть: ' . implode(',', array_keys($formats)));
        }

        Yii::$app->response->format = $formats[$format];
    }

    /**
     * @return array Available response format
     */
    protected function availableResponseFormats()
    {
        return [
            'json' => Response::FORMAT_JSON,
            'xml' => Response::FORMAT_XML
        ];
    }

    /** @inheritdoc */
    public function init()
    {
        $this->setFormat();
        $this->auth();
        $this->layout = false;
        parent::init();
    }


    /**
     * @param string $message
     * @param int $code
     * @return array
     */
    public function throwException($message = 'Что-то сломалось...', $code = 500)
    {
        return [
            'status' => $code,
            'message' => $message
        ];
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
        return ArrayHelper::merge(['status' => 200, 'message' => $message], $additional_data);
    }
}