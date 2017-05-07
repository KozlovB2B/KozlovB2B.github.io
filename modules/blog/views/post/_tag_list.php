<?php
use yii\widgets\ListView;
use app\modules\blog\models\TagPostSearch;
use app\modules\blog\models\TagPost;
use yii\helpers\Html;

/* @var $this yii\web\View */

$data_provider = (new TagPostSearch())->popularTags();

if ($data_provider->getTotalCount()) {
    echo Html::beginTag('div', ['class' => 'public-blog-tags-filter']);

    echo Html::tag('div', Html::a(Yii::t('blog', 'All'), '/blog', ['class' => 'tag ' . (empty($_GET['t']) ? 'active' : null)]), ['class' => 'clearfix']);

    echo ListView::widget([
        'dataProvider' => $data_provider,
        'summary' => false,
        'emptyText' => false,
        'layout' => "{items}",
        'itemView' => function (TagPost $model) {
            return $this->render('_tag_list_item', ['model' => $model]);
        },
    ]);

    echo Html::endTag('div');
}