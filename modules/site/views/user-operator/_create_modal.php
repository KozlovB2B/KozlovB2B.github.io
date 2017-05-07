<?php
/** @var OperatorRegistrationForm $model */
/** @var string $password */
use app\modules\user\models\OperatorRegistrationForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;

Modal::begin([
    'header' => Html::tag("strong", Yii::t('site', "Operator has been added!")),
    'id' => 'site___user_operator__create_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::a(Yii::t('site', 'Ок'), "#", ['class' => 'btn btn-success', "data-dismiss" => "modal"]),
//    'toggleButton' => ['label' => Yii::t('site', 'Close')],
]); ?>

    <strong><?php echo Yii::t('site', 'Operator has been added.') ?></strong>
    <br/>
    <small><?php echo Yii::t('site', 'All operators have to be logged from the main page at {link}', ['link' => Html::a(Yii::$app->getRequest()->getHostInfo(), Yii::$app->getRequest()->getHostInfo())]) ?></small><br/>
    <br/>
    <strong><?php echo Yii::t('site', 'Username') ?>:</strong> <?php echo $model->username; ?><br/>
    <strong><?php echo Yii::t('site', 'Password') ?>:</strong> <?php echo $password; ?><br/>
    <br/>
    <small>
        * <?php echo Yii::t('site', "you may change password and operator's name by clicking edit button") ?>
    </small>
<?php
Modal::end();