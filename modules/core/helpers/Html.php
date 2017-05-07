<?php
namespace app\modules\core\helpers;


class Html extends \yii\helpers\Html
{
    /**
     * Is active url now or not
     *
     * @param string $text
     * @return bool
     */
    public static function hint($text)
    {
        return Html::tag('small', $text);
    }
}