<?php

use yii\helpers\Html;

echo Html::tag('div', Html::tag('h1', 'Поддержка'), ['class' => 'public-page-heading'])

?>

<div class="row">
    <div class="col-xs-12">
        <br/>
        <br/>
        <br/>
        <br/>

        <p>
            Пожалуйста, посмотрите <a href="/blog/faq-voprosy">FAQ</a> и обучающее видео в разделе <a href="/blog/konstruktor-scriptov-prodaz-video-instrukciya">Инструкции</a>, если там тоже нет ответа на ваш вопрос - пишите <?php echo Yii::$app->params['supportEmail'] ?>. Обычно мы отвечаем в течение 1-2 рабочих дней.
            <?php echo Yii::$app->params['supportEmail'] ?>
        </p>

        <p>
            Спасибо!
        </p>
        <br/>
        <br/>
        <br/>
        <br/>
    </div>
</div>