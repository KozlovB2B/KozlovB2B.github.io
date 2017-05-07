<?php
use \yii\helpers\Html;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\ArrayHelper;
use app\modules\blog\components\Share;
use yii\helpers\Url;

/**
 * @var app\modules\blog\models\Post $model
 */
?>
<h2>
    <a href="<?php echo $model->getUrl() ?>">
        <?php echo $model->heading ?>
    </a>
</h2>
<?php if ($model->tags) : ?>
    <ul class="public-blog-tags">
        <?php echo FA::i(FA::_TAGS) ?><?php echo implode(', ', ArrayHelper::map($model->tags, 'id', 'name')) ?>
    </ul>
<?php endif; ?>

<div>
    <?php echo $model->teaser ?>
</div>
<div>
    <a href="<?php echo $model->getUrl() ?>">
        <?php echo Yii::t('blog', 'Read more >>>>') ?>
    </a>
</div>
<ul class="public-blog-teaser-functions">
    <li>
        <?= FA::i(FA::_CLOCK_O) ?><?php echo Yii::$app->getFormatter()->asDate($model->published_at) ?>
    </li>

    <?php if ($model->author) : ?>
        <li>
            <?= FA::i(FA::_USER) ?><?php echo $model->author->name ?>
        </li>
    <?php endif; ?>


    <li>

        <?= Html::a(FA::i(FA::_COMMENT_O) . ' ' . Yii::t('blog', 'Leave a comment'), $model->getUrl() . '#comments'); ?>
    </li>

    <li>
        <?= Share::widget([
            'type' => 'small',
            'tag' => 'span',
            'template' => '&nbsp;<span>{button}</span>&nbsp;',
            'url' => Url::to([$model->getUrl()] , TRUE),
        ]); ?>
    </li>
</ul>