<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\PasswordRecoveryForm $model
 * @var $login bool
 */

$this->title = 'Задайте свой новый пароль';

Pjax::begin(['id' => 'user___user__reset_password_container', 'enablePushState' => false]);

$form = ActiveForm::begin(['id' => 'user___user__reset_password_form', 'options' => ['data-pjax' => true]]); ?>

    <fieldset>
        <legend><?= Html::encode($this->title) ?></legend>
        <?= $form->field($model, 'new_password')->passwordInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'new_password_repeat')->passwordInput() ?>
    </fieldset>


<?= Html::submitButton('Сменить пароль', ['class' => 'btn btn-success btn-block']) ?><br>

<?php ActiveForm::end(); ?>
<?php
Pjax::end();
