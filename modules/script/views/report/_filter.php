<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \app\modules\script\models\Call;
use yii\helpers\ArrayHelper;
use app\modules\user\models\profile\Operator;
use \app\modules\script\models\ar\Script;
use kartik\daterange\DateRangePicker;
use dosamigos\selectize\SelectizeDropDownList;

/**
 * @var \app\modules\script\components\ByCallsReport $report
 * @var string $url
 */

$form = ActiveForm::begin([
    'id' => 'script___report__by_calls_search_form',
    'action' => $url,
    'layout' => 'inline',
    'method' => 'get',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]); ?>
    <div class="row">
        <div class="col-lg-10">
            <?= $form->field($report, 'id')->textInput(['placeholder' => 'ID']) ?>

            <?= $form->field($report, 'user_id')->widget(SelectizeDropDownList::className(), [
                'items' => Operator::getOperatorsList(),
                'options' => ['class' => 'form-control', 'multiple' => true, 'prompt' => '-- ' . $report->getAttributeLabel('user_id')],
                'clientOptions' => [
                    'plugins' => ['remove_button']
                ],
            ]) ?>

            <?php

            $api_users = \app\modules\api\models\ApiUser::getList();

            if ($api_users) {
                echo $form->field($report, 'api_user')->widget(SelectizeDropDownList::className(), [
                    'items' => $api_users,
                    'options' => ['class' => 'form-control', 'multiple' => true, 'prompt' => '-- ' . $report->getAttributeLabel('api_user')],
                    'clientOptions' => [
                        'plugins' => ['remove_button']
                    ],
                ]);
            } ?>

            <?= $form->field($report, 'script_id')->widget(SelectizeDropDownList::className(), [
                'items' => ArrayHelper::map(Script::find()->allByUserCriteria(Yii::$app->getUser()->getId())->all(), 'id', 'name'),
                'options' => ['class' => 'form-control', 'multiple' => true, 'prompt' => '-- ' . $report->getAttributeLabel('script_id')],
                'clientOptions' => [
                    'plugins' => ['remove_button']
                ],
            ]) ?>


            <?= $form->field($report, 'is_goal_reached')->dropDownList(Call::isGoalReachedVariants(), ['prompt' => '-- ' . $report->getAttributeLabel('is_goal_reached')]); ?>

            <?= $form->field($report, 'normal_ending')->dropDownList(Call::normalEndingsVariants(), ['prompt' => '-- ' . $report->getAttributeLabel('normal_ending')]); ?>

            <br/>
            <br/>
            <?= Yii::t("script", 'Period') ?>
            &nbsp;&nbsp;

            <?php echo $form->field($report, 'started_at', [
                'options' => ['class' => 'drp-container form-group'],

            ])->widget(DateRangePicker::classname(), [
                'pluginOptions' => [
                    'locale' => ['applyLabel' => 'ok'],
                ],
                'presetDropdown' => true
            ]); ?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?= Yii::t("script", 'Duration (seconds)') ?>
            &nbsp;&nbsp;
            <?= $form->field($report, 'duration_from')->textInput(['style' => 'width:69px', 'placeholder' => Yii::t("script", 'from'), 'class' => 'form-control']) ?>
            &mdash;
            <?= $form->field($report, 'duration_to')->textInput(['style' => 'width:69px', 'placeholder' => Yii::t("script", 'to'), 'class' => 'form-control']) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::submitButton(Yii::t("script", 'Search'), ['class' => 'btn btn-success']) ?><br/><br/>
            <?= Html::a(Yii::t("script", 'Excel export'), $url . '?excel=1&' . Yii::$app->request->queryString, ['class' => 'btn btn-xs btn-primary']) ?>
        </div>
    </div>


<?php ActiveForm::end(); ?>