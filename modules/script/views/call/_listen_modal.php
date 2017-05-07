<?php
/* @var app\modules\script\models\Call $model */
/* @var int $start */

use yii\bootstrap\Modal;
use yii\helpers\Html;


Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Listen call record #{0}', $model->id)),
    'id' => 'script___report___listen_play_modal'
]); ?>
    <br/>
    <br/>
    <div class="text-center">
        <?php if ($model->record_url): ?>
            <audio id="script___report___listen_player" src="<?php echo $model->record_url ?>" controls></audio>
            <script>
                var player = document.getElementById('script___report___listen_player');
                player.currentTime = <?php echo $start ?>;
                player.play();

                setEvent('hide.bs.modal', '#script___report___listen_play_modal', function () {
                    player.pause();
                    return true;
                });
            </script>
        <?php else: ?>
            <?= Yii::t('script', 'Call record not available') ?>
            <br/>
            <br/>
            <br/>
            <small>
                <?= Yii::t('script', 'Do you want to integrate Script PROMPTER with voice recording system? Please contact support team') ?>
            </small>
        <?php endif; ?>
    </div>
    <br/>
    <br/>
<?php
Modal::end();