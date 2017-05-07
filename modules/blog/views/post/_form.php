<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\blog\models\Post;
use app\modules\blog\models\Author;
use app\modules\core\components\Division;
use yii\redactor\widgets\Redactor;
use app\modules\site\behaviors\DefaultSeoContentBehavior;
use dosamigos\selectize\SelectizeTextInput;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-4">
        <fieldset>
            <legend>Системная информация</legend>

            <?= $form->field($model, 'division')->dropDownList(Division::active(), ['disabled' => !$model->isNewRecord, 'prompt' => '-- выберите дивизион'])
                ->hint(Html::tag('small', 'Посты разделяются по дивизионам.
            Например пост для русского сайта не может быть прочитана на англоязычном домене даже если у пользователя будет прямая ссылка.
            Русские посты читаются только на русском домене и т.п.')) ?>

            <?= $form->field($model, 'author_id')->dropDownList(Author::postFormList($model), ['disabled' => !$model->isNewRecord, 'prompt' => '-- выберите автора'])
                ->hint(Html::tag('small', 'Если у поста указать автора - информация об авторе будет показана ниже. Уазывать автора не обязательно. Например новости или оповещения могут не иметь автора.')) ?>

            <?= $form->field($model, 'status_id')->dropDownList(Post::getStatuses())
                ->hint(Html::tag('small', 'Опубликованные посты будут отображени в ленте блога и доступны для прочтения.')) ?>


            <?= $form->field($model, 'tagNames')->widget(SelectizeTextInput::className(), [
                'loadUrl' => ['post/tags'],
                'options' => ['class' => 'form-control'],
                'clientOptions' => [
                    'plugins' => ['remove_button'],
                    'valueField' => 'name',
                    'labelField' => 'name',
                    'searchField' => ['name'],
                    'create' => true,
                ],
            ])->hint(Html::tag('small', '<strong>Используйте теги для создания фидов.</strong> Например если вы используете тег &laquo;Новости&raquo; пользователь сможет увидеть все новости, нажав на тег &laquo;Новости&raquo;')) ?>

        </fieldset>

        <fieldset>
            <legend>SEO</legend>
            <?= $form->field($model, 'friendly_url')->textInput(['maxlength' => 150, 'disabled' => !$model->getIsNewRecord() && $model->friendly_url && !$model->getErrors('friendly_url')])
                ->hint(Html::tag('small', 'SEO URL задается только один раз при создании поста. Так что проверьте его хорошенько, потом изменить его будет нельзя.')) ?>

            <?= $form->field($model, 'seoTitle')->textInput()->hint(Html::tag('small', 'Title должен быть уникален для каждой страницы сайта. Система проверит это перед сохранением.')); ?>
            <?= $form->field($model, 'seoKeywords')->textInput(); ?>
            <?= $form->field($model, 'seoDescription')->textarea(); ?>

            <div class="hint-block">
                <small>
                    Если не указать мета-содержимое страницы, оно будет заполнено автоматически:<br/>
                    <strong>title</strong> - <?= DefaultSeoContentBehavior::getContentFor('title') ?><br/>
                    <strong>keywords</strong> - <?= DefaultSeoContentBehavior::getContentFor('keywords') ?><br/>
                    <strong>description</strong> - <?= DefaultSeoContentBehavior::getContentFor('description') ?>

                </small>
            </div>

            <br/>
            <br/>
        </fieldset>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>


    <div class="col-xs-8">
        <fieldset>
            <legend>Контент</legend>

            <?= $form->field($model, 'heading')->textInput()
                ->hint(Html::tag('small', 'Если вы не заполнили SEO URL вручную - он будет автоматически сгенерироватн из заголовка.')) ?>

            <?= $form->field($model, 'teaser')->textarea(['rows' => 6])->widget(Redactor::className())
                ->hint(Html::tag('small', 'Пользователь видет тизеры постов или новостей в списке на главной странице и если интересно - нажимает читать далее. Чтобы тизер выглядел ярче - рекомендуется добавить в него картинку.')) ?>

            <?= $form->field($model, 'content')->widget(Redactor::className()) ?>

        </fieldset>
    </div>


    <?php ActiveForm::end(); ?>

</div>
