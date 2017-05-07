<?php
use yii\widgets\Pjax;
Pjax::begin(['id' => 'site___flash', 'timeout' => false, 'enablePushState' => false]);?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
                <?php if ($message && in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
                    <div class="alert alert-<?= $type ?>">
                        <?= $message ?>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
</div>
<?php Pjax::end();?>