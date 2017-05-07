<?php

use app\modules\core\components\widgets\GlyphIcon;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $scripts_data_provider yii\data\ActiveDataProvider */

?>
<?php Pjax::begin(['id' => 'user___head__team_invite_buttons', 'timeout' => 10000, 'enablePushState' => 0, 'enableReplaceState' => 0, 'options' => ['url' => Url::to(['/user/head/team-invite-buttons'])]]); ?>
<fieldset>
    <legend class="buttons text-right">
            <span class="pull-left">
                Сотрудники
            </span>
        <?php if (Yii::$app->getUser()->can('site___user_operator__create')) : ?>


            <span class="visible-xs">
                <br/>
                <small class="pull-left"><i class="small">Пригласить</i></small>
              <?= Html::a(GlyphIcon::i('user') . ' Оператора', Url::to(['/user/operator/invite']), ['data-container' => '#user___operator___invite_modal_pjax', 'class' => 'btn btn-primary btn-xs pjax-modal']) ?>
              <?= Html::a(GlyphIcon::i('education') . ' Проектировщика', Url::to(['/user/designer/invite']), ['data-container' => '#user___designer___invite_modal_pjax', 'class' => 'btn btn-primary btn-xs pjax-modal']) ?>
            </span>
            <span class="hidden-xs">
                <small><i class="small">Пригласить</i></small>
            <?= Html::a(GlyphIcon::i('user') . ' Оператора', Url::to(['/user/operator/invite']), ['data-container' => '#user___operator___invite_modal_pjax', 'class' => 'btn btn-primary btn-sm pjax-modal']) ?>
            <?= Html::a(GlyphIcon::i('education') . ' Проектировщика', Url::to(['/user/designer/invite']), ['data-container' => '#user___designer___invite_modal_pjax', 'class' => 'btn btn-primary btn-sm pjax-modal']) ?>
            </span>


        <?php else : ?>
        <small><i class="small">Вы не можете приглашать новых сотрудников</i></small>
        <?php endif; ?>
    </legend>
</fieldset>
<?php Pjax::end() ?>
