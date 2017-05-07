<?php
use yii\helpers\Html;


/* @var $model app\modules\script\models\Script */
/* @var integer $focus_node Node to be focused */

$data_json = $model->data_json ? $model->data_json : "{}";


echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => array_keys($model->getAttributes()),
]);

echo Html::textarea('code', $data_json);


