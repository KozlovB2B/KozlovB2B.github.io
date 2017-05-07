<?php

namespace app\modules\integration\modules\hookz\components;


use app\modules\integration\modules\hookz\models\Hook;
use app\modules\script\models\ar\CallData;
use app\modules\script\models\Call;
use yii\base\Component;
use Yii;

class WebHookPerformer extends Component
{
    public static function getMarkers()
    {
        return [
            '_call_id_' => [
                'get' => true,
                'attribute' => 'id',
                'description' => "ID звонка"
            ],
            '_user_id_' => [
                'get' => true,
                'attribute' => 'user_id',
                'description' => "ID пользователя, совершившего звонок"
            ],
            '_script_id_' => [
                'get' => true,
                'attribute' => 'script_id',
                'description' => "ID скрипта, по кторому совершался звонок"
            ],
            '_is_goal_reached_' => [
                'get' => true,
                'attribute' => 'is_goal_reached',
                'description' => "1 - Цель достигнута, 0 - Не достигнута"
            ],
            '_normal_ending_' => [
                'get' => true,
                'attribute' => 'normal_ending',
                'description' => "1 - Скрипт отработал, 0 - Скрипт сломался"
            ],
            '_comment_' => [
                'get' => false,
                'attribute' => 'comment',
                'description' => "Комментарий оператора"
            ],
            '_data_' => [
                'get' => true,
                'attribute' => 'data',
                'description' => "Данные, переданные в звонок, через hash-навигацию &mdash; cм. &laquo;Как передать свои данные в звонок&raquo;"
            ],
            '_fields_' => [
                'get' => false,
                'attribute' => 'fields',
                'description' => "Поля в контексте которых производился звонок."
            ]
        ];
    }

    /**
     * @param Call $model
     * @param Hook $hook
     */
    public static function perform(Call $model, Hook $hook)
    {
        $url = $hook->get;

        $post = $hook->post;

        foreach (static::getMarkers() as $marker => $data) {
            $value = addslashes($model->{$data['attribute']});

            $url = str_replace($marker, $data['get'] === true ? $value : "", $url);

            if ($data['attribute'] == 'fields') {
                $post = str_replace('"' . $marker . '"', $value ? $value : "{}", $post);
            } else {
                $post = str_replace($marker, $value, $post);
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_decode($post));
        curl_exec($ch);
        curl_close($ch);
    }

}