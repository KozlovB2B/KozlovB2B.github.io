<?php
namespace app\modules\api\v1\controllers;

use app\modules\api\v1\components\BaseController;
use Yii;
use app\modules\script\models\ApiToken;
use yii\web\BadRequestHttpException;

/**
 * CheckController
 */
class CheckController extends BaseController
{

    /** @inheritdoc */
    public function init()
    {
        $this->setFormat();
        $this->layout = false;
    }


    /**
     *
     * @return array
     * @throws \Exception
     */
    public function actionKey()
    {
        $key = Yii::$app->getRequest()->get('key', false);

        if ($key === false) {
            throw new BadRequestHttpException('Не указан параметр key');
        }

        $token = ApiToken::getByToken($key);

        return [
            'key_valid' => (int)!!$token,
            'user_id' => $token ? $token->id : 0,
        ];
    }
}