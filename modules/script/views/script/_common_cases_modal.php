<?php
/** @var OperatorRegistrationForm $model */
/* @var app\modules\script\models\Script $script */

use app\modules\user\models\OperatorRegistrationForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use app\modules\site\models\Tooltip;
use rmrevin\yii\fontawesome\FA;
use app\modules\script\models\Call;

Modal::begin([
    'header' => Html::tag("strong", Yii::t('script', 'Common cases')),
    'id' => 'script___script___common_cases_modal',
    'footer' => Html::tag('div', Html::a(Yii::t('site', 'Ok'), "#", ['class' => 'btn btn-success', "data-dismiss" => "modal"]))

]); ?>

<?php if (!Tooltip::isSkipped(Tooltip::SCRIPT_COMMON_CASES)) : ?>
    <div class="alert alert-success site___tooltip__wrapper">
        <button type="button" class="close site___tooltip__skip" data-tooltip="<?php echo Tooltip::SCRIPT_COMMON_CASES; ?>">×</button>
        <?php echo Tooltip::getText(Tooltip::SCRIPT_COMMON_CASES); ?>
    </div>
<?php endif; ?>
    <div id="script___script___common_cases_tabular">

    </div>

    <div id="script___script___common_cases_call_stages_list" class="hide">
        <?php echo Html::dropDownList('script___script___common_cases_call_stages_list', null, Call::getStages(), ['class' => 'form-control', 'prompt' =>  "-- " . Yii::t('script', 'conversation (calls) stage')]) ?>
    </div>

    <div class="btn btn-primary btn-sm" id="script___script___common_cases_add">
        <?= FA::icon('plus'); ?>
        &nbsp;
        <?= Yii::t('script', 'Add a common case') ?>
    </div>
<?php
Modal::end();