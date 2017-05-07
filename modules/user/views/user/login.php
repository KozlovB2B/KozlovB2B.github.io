<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\LoginForm $login
 */

$this->title = 'Войти';
?>

<?php echo $this->render('_login_form', ['login' => $login]) ?>