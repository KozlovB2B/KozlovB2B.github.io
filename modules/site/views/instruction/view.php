<?php
/** @var app\modules\site\models\Instruction $model */
?>

<div class="row">
    <div class="col-xs-6 col-xs-offset-3">
        <iframe width="800" height="600" src="<?php echo $model->video ?>" frameborder="0" allowfullscreen></iframe>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-xs-offset-3">
        <h3><?php echo $model->description ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-xs-offset-3">
        <?php echo $model->content ?>
    </div>
</div>