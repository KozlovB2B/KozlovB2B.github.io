<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

Modal::begin([
    'header' => Html::tag('h4', Yii::t('site', 'Contact us')),
    'id' => 'site___site__contact_modal',
    'size' => Modal::SIZE_SMALL,
    'footer' => Html::a('Закрыть', "#", ['class' => 'btn btn-default', "data-dismiss" => "modal"])
]);

Pjax::begin(['id' => 'site___site__contact_modal_container']);

?>
    <p>
        <strong>Телефон:</strong> <?php echo Yii::$app->params['phone'] ?>
    </p>

    <p>
        <strong>E-mail:</strong> <?php echo Yii::$app->params['supportEmail'] ?>
    </p>
<?php

Pjax::end();

Modal::end();