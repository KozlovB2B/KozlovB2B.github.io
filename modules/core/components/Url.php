<?php
namespace app\modules\core\components;


class Url extends \yii\helpers\Url
{
    /**
     * Is active url now or not
     *
     * @param string $url
     * @return bool
     */
    public static function isActive($url)
    {
        return \Yii::$app->request->getUrl() == $url;
    }
}