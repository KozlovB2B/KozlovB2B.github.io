<?php
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var app\modules\user\models\HeadRegistrationForm $register
 */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<fieldset>
    <legend>
        <?= Html::encode($this->title) ?>

        <?= Html::a('Вход', Url::to(['/login']), ['class' => 'pull-right legend-link']) ?>
    </legend>
    <?php echo $this->render('_reg_form', ['register' => $register]) ?>
</fieldset>