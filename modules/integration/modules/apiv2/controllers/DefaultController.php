<?php
namespace app\modules\integration\modules\apiv2\controllers;

use app\modules\core\components\CoreController;
use Yii;
use app\modules\script\models\ApiToken;

/**
 * DefaultController
 */
class DefaultController extends CoreController
{
    /**
     * @return array Available response formats
     */
    protected function formats()
    {
        return [
            'json' => 'Формат ответа по-умолчанию',
            'jsonp' => 'Чтобы получить jsonp ответ - передайте GET-параметр "_format=jsonp". Название callback функции можно передать в GET-параметре "callback=my_func", по-умолчанию используется название функции "callback"'
        ];
    }


    public function actionIndex()
    {
        $token = null;

        if (!Yii::$app->getUser()->getIsGuest()) {
            $token = ApiToken::findOne(Yii::$app->getUser()->getId());

            if (!$token) {
                ApiToken::generate();
                $token = ApiToken::findOne(Yii::$app->getUser()->getId());
            }
        }

        return $this->render('index', ['token' => $token, 'formats' => $this->formats()]);
    }
}