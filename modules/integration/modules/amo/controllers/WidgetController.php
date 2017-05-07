<?php
namespace app\modules\integration\modules\amo\controllers;

use app\modules\core\components\BaseCoreController;
use app\modules\integration\modules\amo\components\Widget;
use Yii;
use yii\web\Response;


/**
 * ScriptController
 */
class WidgetController extends BaseCoreController
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['auth'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSONP,
                ]
            ]
        ];
    }

    /**
     * Процедура автоматической регистрации пользователей, установивших виджет ScriptDesigner в AmoCRM:
     *
     * @param string $callback
     * @param string $amouser
     * @param string $amohash
     * @param string $subdomain
     * @param string $name
     * @param string $phone
     * @param int $auto_log_in
     * @param int $create_account
     * @return array
     */
    public function actionAuth($callback, $amouser, $amohash, $subdomain, $auto_log_in, $create_account, $name, $phone)
    {
        $data = [];

        try {
            $data['success'] = 1;
            $data['message'] = Widget::auth($amouser, $amohash, $subdomain, $auto_log_in, $create_account, $name, $phone);
        } catch (\Exception $e) {
            $data['success'] = 0;
            $data['message'] = $e->getMessage();
        }

        return [
            'data' => $data,
            'callback' => $callback
        ];
    }
}
