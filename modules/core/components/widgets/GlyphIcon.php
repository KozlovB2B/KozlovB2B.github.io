<?php

namespace app\modules\core\components\widgets;

use yii\helpers\Html;

class GlyphIcon
{
    public static function i($type, $options = [])
    {
        if (empty($options['class'])) {
            $options['class'] = '';
        }

        $options['class'] .= ' glyphicon glyphicon-' . $type;

        return Html::tag('span', "", $options);
    }
}