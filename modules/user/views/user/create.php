<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\models\profile\ProfileRelation;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\UserCreateForm */
/* @var $form ActiveForm */

$this->title = 'Создать пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Список пользователей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'action' => '/user/user/create',
//    'method' => 'POST',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false
]); ?>

<div class="container">
    <div class="row">
        <div class="col-xs-6">
            <fieldset>
                <legend>Общие данные</legend>
                <?= $form->field($model, 'username')->textInput() ?>
                <?= $form->field($model, 'password')->textInput() ?>
                <?= $form->field($model, 'profile')->dropDownList(ProfileRelation::profilesForCreatingForm()) ?>
                <div class="form-group">
                    <?= Html::submitButton('Создать пользователя', ['class' => 'btn btn-primary']) ?>
                </div>
            </fieldset>
        </div>
        <div class="col-xs-6">
            <fieldset>
                <legend>Спец. данные профиля</legend>
                <?= Html::tag('div', $this->render('@app/modules/user/views/profile/create-form/_' . $model->getProfileModel()->getCode(), ['model' => $model->getProfileModel()]), ['id' => $model->formName() . '_profile_fields']) ?>
            </fieldset>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>

