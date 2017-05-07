<?php
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\UserCreateForm */
/* @var $form ActiveForm */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\models\profile\ProfileRelation;
use app\modules\user\models\UserCreateForm;


$form = ActiveForm::begin([
    'id' => $model->formName(),
    'action' => '/user/user/create',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
Modal::begin([
    'header' => Html::tag('h4', 'Создать пользователя'),
    'id' => 'user___user___create_modal',
    'size' => Modal::SIZE_LARGE,
    'footer' => Html::submitButton('Создать пользователя', ['class' => 'btn btn-success']) . " " . Html::a('Отмена', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]); ?>
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group field-user___user___create_form-profile">
                <label class="control-label">Сценарий</label>
                <?= Html::dropDownList('scenario', $model->scenario, UserCreateForm::scenariosList(), ['class' => 'form-control', 'id' => $model->formName() . '-scenario']) ?>
            </div>
            <?= $form->field($model, 'email')->textInput() ?>
            <div class="<?= $model->formName() . '-scenario-fields' ?>" id="<?= $model->formName() . '-scenario-create-fields' ?>" style="display: <?= $model->scenario == 'create' ? 'block' : 'none' ?>">
                <?= $form->field($model, 'username')->textInput() ?>
                <?= $form->field($model, 'password')->textInput() ?>

                <small  class="text-info">
                    Пользователь будет сразу создан и подтвержден в системе.
                    Никаких сообщений на указанный email выслано не будет.
                    Вам нужно будет самостоятельно сообщить ему его логин и пароль.
                </small>
            </div>
            <div class="<?= $model->formName() . '-scenario-fields' ?>" id="<?= $model->formName() . '-scenario-invite-fields' ?>" style="display: <?= $model->scenario == 'invite' ? 'block' : 'none' ?>">
                <small class="text-info">
                    Пользователь будет создан неподтвержденным.
                    Email будет использован в качестве логина, пароль сгенерируется автоматически.
                    Пользователю будет выслано пригласительное письмо со ссылкой для завершения регистрации.
                    Там он сможет придумать себе другой пароль и логин.
                </small>
            </div>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'profile')->dropDownList(ProfileRelation::profilesForCreatingForm()) ?>
            <?= Html::tag('div', $this->render('@app/modules/user/views/profile/create-form/_' . $model->getProfileModel()->getCode(), ['model' => $model->getProfileModel(), 'form' => $form]), ['id' => $model->formName() . '_profile_fields']) ?>
        </div>
    </div>
<?php
Modal::end();
ActiveForm::end();