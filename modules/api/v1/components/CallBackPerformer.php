<?php

namespace app\modules\api\v1\components;


use app\modules\script\models\Call;
use yii\base\Component;
use Yii;

class CallBackPerformer extends Component
{
    /**
     * Performs API callback URL
     *
     * @param Call $model
     * @param $callback
     */
    public static function perform(Call $model, $callback = false)
    {
        if ($callback) {
            $url = str_replace([' ', '_call_id_', '_user_id_', '_script_id_', '_is_goal_reached_', '_normal_ending_'], ['%20', (int)$model->id, (int)$model->user_id, (int)$model->script_id, (int)$model->is_goal_reached, (int)$model->normal_ending], $callback);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOBODY, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_exec($ch);

//            Yii::error('Callback result: ' . $result);

            curl_close($ch);

//            if (filter_var($url, FILTER_VALIDATE_URL)) {
//
//            }else{
//                Yii::error('Callback invalid: ' . $callback);
//            }
        }
    }

}