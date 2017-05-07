<?php
use yii\helpers\Html;
use app\modules\core\components\widgets\GlyphIcon;
use app\modules\core\components\Url;

/* @var $this yii\web\View */
/* @var boolean $enabled */


$this->title = Yii::t('recorder', 'Calls record');
$this->params['breadcrumbs'][] = ['label' => Yii::t('integration', 'Integrations'), 'url' => '/integration'];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
setEvent("change", "#integration___recorder__enable", function () {
    if($(this).prop("checked")){
        ajax("/integration/recorder/default/enable");
    } else {
        ajax("/integration/recorder/default/disable");
    }

    return true;
});');

?>
<div class="container">
    <?php if (Yii::$app->getUser()->can('integration___integration__manage')): ?>
        <div class="row">
            <div class="col-xs-12">
                <fieldset>
                    <legend class="buttons">
                        <label> <?php echo Html::checkbox('enable', $enabled, ['id' => 'integration___recorder__enable', 'class' => 'big-checkbox']); ?> Включить запись звонков </label>

                    </legend>
                </fieldset>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-xs-12">
            <fieldset>
                <legend class="buttons "><?php echo Yii::t('recorder', 'Программа для записи звонков') ?></legend>
            </fieldset>




        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">

            <img src="/static/recorder/preview.png" style="margin-left: -20px">

            <br/>
            <br/>
            <a download href="/static/recorder/SDRecorderSetup.msi" class="btn btn-primary btn-lg">Скачать установщик</a>
            <br/>
            <br/>
            <p class="small">Для корректной работы программы - добавьте ее в исключения вашего антивируса и файлрвола если такие есть.</p>

        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">

            <?php if (!Yii::$app->getUser()->getIsGuest()): ?>
                <br/>
                <div class="alert alert-warning">
                    Ваш ключ авторизации (нужно будет ввести в программу):<br/>
                    <?php echo Yii::$app->getUser()->getIdentity()->getAuthKey(); ?>
                    <br/>
                    <small>
                        У каждого пользователя ключ авторизации разный<br/>
                        Оператор может узнать свой ключ авторизации на этой странице, либо прямо в прогонщике, когда тот выдаст ему сообщение о том, что программа звукозаписи не подключена.
                    </small>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <fieldset>
        <legend class="buttons ">Как она работает</legend>
    </fieldset>

    <div class="row">
        <div class="col-xs-12">



        </div>
    </div>
</div>

