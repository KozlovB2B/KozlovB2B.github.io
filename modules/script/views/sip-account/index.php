<?php
/**
 * @var View $this
 * @var ActiveDataProvider $data_provider
 * @var Operator $search_model
 * @var OperatorRegistrationForm $model
 */

use app\modules\user\models\profile\Operator;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
use \rmrevin\yii\fontawesome\FA;
use app\modules\script\components\SipAccountAssetBundle;

SipAccountAssetBundle::register($this);

$this->title = Yii::t("script", 'SIP accounts');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-8">
        Чтобы вы и ваши операторы смогли набирать номер и звонить прямо из интерфейса прогона скриптов - заполните SIP реквизиты.
        <br/>
        Операторы так же сами могут заполнить данные своего SIP аккаунта у себя в интерфейсе.
    </div>
    <div class="col-xs-4">
        <?php echo Html::tag('p', Html::a(Yii::t('script', 'Edit my sip account'), "/script/sip-account/update", ['class' => 'btn btn-success pull-right script___sip_account__update'])); ?>
    </div>
</div>
<br/>
<br/>
<?php Pjax::begin(['id' => 'script___sip_account__index_grid']); ?>
<?= GridView::widget([
    'dataProvider' => $data_provider,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        [
            "attribute" => 'user.username',
            "filter" => false,
            "format" => 'html',
            "value" => function (Operator $model) {
                $user = $model->user;

                if (!$user) {
                    return null;
                }

                if ($user->getIsBlocked()) {
                    return Html::tag("small", $user->username . "&nbsp;" . Yii::t("site", "(blocked)"), ["class" => "text-danger"]);
                }

                return $user->username;
            }
        ],
        [
            "attribute" => 'sip.display_name',
            "filter" => false,
            "format" => 'html',
        ],
        [
            "attribute" => 'sip.public_identity',
            "filter" => false,
            "format" => 'html',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
            'buttons' => [
                'update' => function ($url, Operator $model, $key) {
                    return Html::a(FA::icon('pencil'), '/script/sip-account/update?id=' . $model->id, ["class" => "script___sip_account__update", "title" => Yii::t("site", "Update operator's data")]);
                }
            ]
        ]
    ],
]); ?>

<?php Pjax::end() ?>
<?php $this->registerJs("window['sip-account'] = new SipAccount();"); ?>