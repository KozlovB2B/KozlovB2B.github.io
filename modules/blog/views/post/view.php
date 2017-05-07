<?php
use app\modules\blog\components\PublicAssetBundle as BlogPublicAssetBundle;
use yii\bootstrap\Html;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\ArrayHelper;
use app\modules\blog\components\Share;
use yii\helpers\Url;
use romi45\seoContent\components\SeoContentHelper;

/**
 * @var $this yii\web\View
 * @var app\modules\blog\models\Post $model
 */

BlogPublicAssetBundle::register($this);

/**
 * You can also user partial register functions
 * @see SeoContentHelper::registerAll()
 */
SeoContentHelper::registerAll($model);

?>
<h1>
    <?php echo $model->heading ?>
</h1>
<div class="row">
    <div class="col-xs-9 public-blog-feed">
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
                <?= Html::a(FA::i(FA::_COMMENT_O). ' ' .Yii::t('blog', 'Leave a comment'), '#comments'); ?>
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

        <?php if ($model->tags) : ?>
            <ul class="public-blog-tags">
                <?php echo FA::i(FA::_TAGS) ?><?php echo implode(', ', ArrayHelper::map($model->tags, 'id', 'name')) ?>
            </ul>
        <?php endif; ?>

        <div>
            <?php echo $model->content ?>
        </div>

        <?php if ($model->author) : ?>
            <hr/>
            <h4><?= Yii::t('blog', 'Author') ?></h4>

            <div class="container-fluid">

                <div class="row">
                    <div class="col-xs-2">
                        <?= Html::img($model->author->avatar, [
                            'class' => 'img-responsive'
                        ]); ?>
                    </div>
                    <div class="col-xs-10">
                        <h5><?= $model->author->name ?></h5>

                        <p>
                            <?= $model->author->about ?>
                        </p>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div id="comments">
            <hr/>
            <?= $this->render('_hypercomments') ?>
        </div>


    </div>
    <div class="col-xs-3">
        <?= $this->render('_tag_list') ?>
    </div>
</div>