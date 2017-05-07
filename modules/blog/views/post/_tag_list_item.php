<?php
use \yii\helpers\Html;

/**
 * @var app\modules\blog\models\TagPost $model
 */

$active = null;

if (isset($_GET['t']) && $model->tag->name == urldecode($_GET['t'])) {
    $active = 'active';
}

echo Html::a($model->tag->name, '/blog?t=' . $model->tag->name, ['class' => 'tag ' . $active]);
echo '<div class="clearfix"></div>';