<?php
use yii\helpers\Url;
use yii\bootstrap\Tabs;

/**
 * @var \app\modules\user\models\UserHeadManager $model
 */

$this->title = 'Просмотр данных пользователя - ' . $model->user->username;
$this->params['breadcrumbs'][] = ['url' => Url::previous('actions-admin'), 'label' => 'Управление пользователями'];
$this->params['breadcrumbs'][] = $this->title;

echo Tabs::widget([
    'items' => [
        [
            'label' => 'Основная информация',
            'content' => Yii::$app->controller->renderPartial('_main_info', ['model' => $model]),
            'active' => true
        ],
        [
            'label' => 'Финансовые операции',
            'content' => Yii::$app->controller->renderPartial('@app/modules/billing/views/balance-operations/_user_list', ['user' => $model])
        ],
        [
            'label' => 'История изменений тарифов',
            'content' => Yii::$app->controller->renderPartial('@app/modules/billing/views/rate-change-history/_user_list', ['user' => $model])
        ],
//          [
//              'label' => 'Dropdown',
//              'items' => [
//                   [
//                       'label' => 'DropdownA',
//                       'content' => 'DropdownA, Anim pariatur cliche...',
//                   ],
//                   [
//                       'label' => 'DropdownB',
//                       'content' => 'DropdownB, Anim pariatur cliche...',
//                   ],
//              ],
//          ],
    ],
]);