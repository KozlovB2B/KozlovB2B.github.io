<?php
use app\modules\blog\models\Post;
use yii\widgets\ListView;
use app\modules\blog\models\TagPost;
use app\modules\blog\components\PublicAssetBundle as BlogPublicAssetBundle;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $postDataProvider yii\data\ActiveDataProvider */
/* @var $tagDataProvider yii\data\ActiveDataProvider */

BlogPublicAssetBundle::register($this);

$this->title = Yii::t('blog', 'Blog, news, announcements');
?>
<?php echo Html::tag('div', Html::tag('h1', $this->title), ['class' => 'public-page-heading']) ?>
<div class="row">
    <div class="col-xs-9 public-blog-feed">

        <?= ListView::widget([
            'dataProvider' => $postDataProvider,
            'itemOptions' => ['class' => 'public-blog-feed-teaser'],
            'summary' => false,
            'emptyText' => false,
            'itemView' => function (Post $model) {
                return $this->render('_blog_list_item', ['model' => $model]);
            },
        ]); ?>
    </div>
    <div class="col-xs-3">
        <?= $this->render('_tag_list') ?>
    </div>
</div>