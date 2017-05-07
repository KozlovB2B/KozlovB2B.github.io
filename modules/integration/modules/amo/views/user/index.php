<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\integration\modules\amo\models\AmoUserSearch;
use app\modules\integration\modules\amo\components\Account as AmoAccount;

/* @var $this yii\web\View */
/* @var $head app\modules\integration\modules\amo\models\Amouser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('amo', 'Amo CRM accounts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('integration', 'Integrations'), 'url' => '/integration'];
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(['id' => 'integration___amo__api_credentials_index_grid', 'timeout' => false, 'enablePushState' => false, 'options' => ['url' => '/integration/amo/user/index']]);

?>
    <p class="small alert alert-info">
        Если вы используете наш виджет из каталога интеграций AmoCRM и в его настройках указали галочку &laquo;Авто-регистрация&raquo; &mdash; менеджеры будут автоматически регистрироваться
        и привязываться к вашему аккаунту при использовании виджета.
    </p>
<?php


$dataProvider = (new AmoUserSearch())->search();
$dataProvider->pagination->pageSize = $dataProvider->getTotalCount();

echo GridView::widget([
    'showHeader' => false,
    'layout' => "{items}\n{pager}",
    'dataProvider' => $dataProvider,
    'columns' => [
        'operator.first_name',
        'amouser',
//        'subdomain',
//        'amohash',
//        ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],
    ],
]);

Pjax::end();