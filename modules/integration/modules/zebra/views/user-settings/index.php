<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\integration\modules\zebra\models\UserSettings;
use app\modules\user\models\profile\Operator;
use yii\data\ArrayDataProvider;
use app\modules\core\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\integration\modules\amo\models\ApiCredentialsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php

$this->registerJs('
setEvent("change", ".integration___zebra__user_settings_value", function () {
    var parent = $(this).closest("tr");
    ajax("/integration/zebra/user-settings/save?id=" + parent.find(".integration___zebra__user_settings_number").data("id") + "&number=" + parent.find(".integration___zebra__user_settings_number").val() + "&name=" + parent.find(".integration___zebra__user_settings_name").val());
    return true;
});
');

$users = UserSettings::usersList();

$dataProvider = new ArrayDataProvider([
    'models' => UserSettings::usersList(),
    'pagination' => [
        'pageSize' => count($users)
    ]
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}",
    'columns' => [
        [
            "attribute" => 'user.username',
            "filter" => false,
            "format" => 'html',
            "value" => function ($model) {
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
        'first_name',
        'last_name',
        [
            "header" => Yii::t('zebra', 'Zebra user number'),
            "filter" => false,
            "format" => 'raw',
            "value" => function ($model) {
                $user_settings = UserSettings::settings($model->id);

                return Html::input("text", 'UserSettings', $user_settings ? $user_settings->number : null, ["class" => "form-control input-sm integration___zebra__user_settings_value integration___zebra__user_settings_number", "data-id" => $model->id]);
            }
        ],
        [
            "header" => Yii::t('zebra', 'Zebra user name'),
            "filter" => false,
            "format" => 'raw',
            "value" => function ($model) {
                $user_settings = UserSettings::settings($model->id);

                return Html::input("text", 'UserSettings', $user_settings ? $user_settings->name : null, ["class" => "form-control input-sm integration___zebra__user_settings_value integration___zebra__user_settings_name", "data-id" => $model->id]);
            }
        ],
    ],
]);