<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\integration\modules\onlinepbx\models\UserSettings;
use app\modules\user\models\profile\Operator;
use yii\data\ArrayDataProvider;
use app\modules\core\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\integration\modules\amo\models\ApiCredentialsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php

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
        'id',
        [
            "attribute" => 'user.username',
            "filter" => false,
            "format" => 'html',
            "value" => function (\app\modules\user\models\profile\Profile $model) {
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
            "header" => Yii::t('onlinepbx', 'Online PBX user number'),
            "filter" => false,
            "format" => 'raw',
            "value" => function (\app\modules\user\models\profile\Profile $model) {
                $user_settings = UserSettings::settings($model->id);

                return Html::input("text", 'UserSettings', $user_settings ? $user_settings->number : null, ["class" => "form-control input-sm integration___onlinepbx__user_settings_number", "data-id" => $model->id]);
            }
        ],
    ],
]);
?>
