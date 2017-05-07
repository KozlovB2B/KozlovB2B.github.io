<?php

/* @var $this yii\web\View */
/* @var $scripts_data_provider yii\data\ActiveDataProvider */
/* @var $operators_data array */

$this->title = Yii::t('site', 'Sales Script Prompter');
?>

<div class="row">
    <div class="
    col-lg-4 col-lg-offset-4
    col-md-6 col-md-offset-3
    col-sm-8 col-sm-offset-2
    col-xs-12
    ">
        <?= $this->render('@app/modules/script/views/script/_operator_list') ?>
    </div>
</div>