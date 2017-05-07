<?php
use yii\helpers\Html;
use app\modules\user\models\Avatar;
use rmrevin\yii\fontawesome\FA;

/* @var \app\modules\user\models\profile\Profile $profile */
/* @var \app\modules\user\models\ChangePasswordForm $change_password */
/* @var \app\modules\user\models\ChangeAvatarForm $change_avatar */
/* @var $this yii\web\View */

$this->title = 'Ваш профиль пользователя';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container">
    <div class="row">
        <div class="col-xs-6 col-xs-offset-1">
            <fieldset>
                <legend>Основная информация</legend>

                <div class="row">
                    <div class="col-xs-5">
                        <?= Html::img(Avatar::current()->getUrl(), [
                            'id' => 'user___user__avatar_img',
                            'class' => 'pointer',
                            'data-toggle' => 'modal', 'data-target' => '#user___user__change_avatar_form_modal'
                        ]); ?>
                    </div>
                    <div class="col-xs-7">

                        <?php

                        $other_profiles = [];

                        if (count($profile->user->profileRelations) > 1) {
                            foreach ($profile->user->profileRelations as $r) {
                                if ($r->profile_class !== $profile->user->getProfile()->getType()) {
                                    $other_profiles[] = $r->getProfile()->getName() . '&nbsp;' . Html::a(FA::i(FA::_SIGN_IN), ['/user/profile/switch?to=' . $r->profile_class], ['title' => 'Переключиться на этот профиль']);
                                }
                            }
                        }

                        $main_data = [
                            'Логин' => $profile->user->username,
                            'Email' => $profile->user->email,
                            'Дата регистрации' => Yii::$app->getFormatter()->asDate($profile->user->confirmed_at),
                            'Часовой пояс' => $profile->user->timezone_id,
                            'Профиль' => $profile->getName()
                        ];

                        if (count($other_profiles)) {
                            $main_data['Доп. профили'] = implode('<br/>', $other_profiles);
                        }
                        ?>

                        <table class="table table-borderless">
                            <?php foreach ($main_data as $label => $value) {
                                echo Html::tag('tr', Html::tag('td', Html::tag('strong', $label), ['style' => 'width:150px']) . Html::tag('td', $value));
                            }

                            echo Html::tag('tr', Html::tag('td', Html::button('Сменить пароль', ['class' => 'btn btn-primary btn-xs', 'data-toggle' => 'modal', 'data-target' => '#user___user__change_password_modal'])) . Html::tag('td', ''));
                            ?>
                        </table>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-xs-4">
            <fieldset>
                <legend>Данные профиля</legend>
                <?= $this->render('@app/modules/user/views/profile/profile-page-form/_form', ['model' => $profile]); ?>
            </fieldset>
        </div>
    </div>
</div>
<?= $this->render('@app/modules/user/views/user/_change_avatar_modal', ['model' => $change_avatar]); ?>
<?= $this->render('@app/modules/user/views/user/_change_password_modal', ['model' => $change_password]); ?>

