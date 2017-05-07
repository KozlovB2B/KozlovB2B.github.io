<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ListView;
use app\modules\script\models\ar\Field;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('script', 'Fields');

?>

<div class="row">
    <div class="col-md-6 col-xs-12">
        <?php Pjax::begin(['id' => 'script___field__index', 'enablePushState' => false, 'options' => ['class' => 'col-xs-12', 'url' => Url::to(['/script/field/index'])]]); ?>

        <fieldset>
            <legend class="buttons text-right">

                <span class="pull-left"><?php echo Yii::t('script', 'Fields') ?></span>

                <?php echo Html::a(Yii::t('script', 'Add field'), Url::to(['/script/field/create']), [
                    'class' => 'btn btn-primary btn-sm pjax-modal',
                    'data-container' => '#script___field__create_ajax_modal_container',
                    'data-pjax' => 0
                ]); ?>
            </legend>
        </fieldset>

        <?= ListView::widget([
            'id' => 'script___field__index_list_view',
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'list-view container-fluid'],
            'itemOptions' => ['class' => 'list-group-item row'],
            'layout' => "{items}",
            'itemView' => "_list_item"
        ]); ?>

        <?php Pjax::end(); ?>
    </div>
    <div class="col-md-6 col-xs-12">
        <fieldset>
            <legend class="buttons">
                <?php echo Yii::t('script', 'About fields') ?>
            </legend>
        </fieldset>

        <?php

        $example = new Field([
            'id' => 1,
            'code' => 'name',
            'name' => 'Имя',
            'type' => 'string'
        ])

        ?>
        <h4>Общая информация</h4>
        <p>
            Поля используются в узлах скрипта как <i><strong>переменные</strong></i>.
        </p>

        <p>
            По ходу звонка оператор может <i><strong>заполнять</strong></i> или <i><strong>изменять</strong></i> содержимое полей прямо в тексте узла и далее система будет <i><strong>подставлять</strong></i> эти значение далее по скрипту.
        </p>

        <br/>
        <h4>Пример</h4>
        <p>
            Например в первом узле вы вставили поле  <?php echo $example->displayHtml() ?>.
        </p>

        <p>
            Оператор выясняет имя собеседника и заполняет его значением "Сергей".
        </p>

        <p>
            Далее вместо пустого поля <?php echo $example->displayHtml() ?> везде будет отображаться <?php echo $example->displayHtml("Сергей") ?>.
        </p>

        <br/>
        <h4>Интеграция по API</h4>
        <p>
            Есть возможность предзаполнять поля программно, используя API, а так же получать эти данные через механизм WebHooks.
        </p>

        <p>
            Проще говоря вы можете интегрировать свою CRM и передавать данные клиентов или контактов прямо в скрипты, а потом получать измененные (дозаполненные) данные обратно и сохранять в свою базу.
        </p>

        <p>
            Чтобы проконсультироваться по поводу интеграции полей с вашей системой - вы или ваши тех-специалисты могут пообщаться с нашим разработчиком по скайпу: romi.45 или написать на почту: agilovr@gmail.com
        </p>
    </div>
</div>

