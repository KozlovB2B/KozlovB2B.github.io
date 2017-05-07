<?php
namespace app\modules\integration\modules\apiv2\components;

use app\modules\script\models\ApiToken;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\modules\user\models\UserHeadManager;
use yii\helpers\ArrayHelper;

/**
 * BaseController
 */
class BaseController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/javascript' => Response::FORMAT_JSONP
                ]
            ]
        ];
    }

    /** @inheritdoc */
    public $enableCsrfValidation = false;

    /** @var UserHeadManager */
    protected $_user_head_manager;

    /** @var string */
    protected $_key;

    /**
     *
     */
    protected function loadKey()
    {
        $headers = Yii::$app->getRequest()->getHeaders();

        $key = null;

        if ($headers['key']) {
            $this->_key = $headers['key'];
        } else {
            $this->_key = Yii::$app->getRequest()->get('key', false);
        }

        if (!$this->_key) {
            $this->error('Не передан ключ!');
        }
    }

    /**
     * @throws \yii\base\ExitException
     */
    protected function auth()
    {
        $this->loadKey();

        $token = ApiToken::getByToken($this->_key);

        if (!$token) {
            $this->error('В параметре key передан неверный ключ API');
        }

        $this->_user_head_manager = UserHeadManager::findOne($token->id);

        if (!$this->_user_head_manager) {
            $this->error('Нет пользователя с таким ключом API');
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        parent::beforeAction($action);

        $this->auth();

        return true;
    }

    /**
     * @param $message
     * @throws \yii\base\ExitException
     */
    public function error($message)
    {
        Yii::$app->response->data = $this->result(0, $message);
        Yii::$app->end();
    }

    /**
     * Возврат ответа
     *
     * @param int $success
     * @param string $message
     * @param array $additional_data [optional] дополнительные данные для передачи вместе с сообщением пользователю
     * @return string
     */
    public function result($success = 1, $message = 'OK', $additional_data = [])
    {
        $result = ArrayHelper::merge(['success' => $success, 'message' => $message], $additional_data);

        if (Yii::$app->response->format == Response::FORMAT_JSONP) {
            return [
                'callback' => Yii::$app->getRequest()->get('callback', 'callback'),
                'data' => $result
            ];
        }

        return $result;
    }
}