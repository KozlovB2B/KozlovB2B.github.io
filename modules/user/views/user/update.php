<?php
use yii\helpers\Html;
use app\modules\user\models\Avatar;
use yii\helpers\ArrayHelper;
use app\modules\user\components\UserUpdateAssetBundle;
use rmrevin\yii\fontawesome\FA;

/* @var \app\modules\user\models\profile\Profile $profile */
/* @var \app\modules\user\models\User $user
/* @var \app\modules\user\models\ChangePasswordForm $change_password */
/* @var \app\modules\user\models\ChangeAvatarForm $change_avatar */
/* @var $this yii\web\View */

$this->title = 'Редактировать профиль пользвоателя';
$this->params['breadcrumbs'][] = $this->title;

UserUpdateAssetBundle::register($this);
$this->registerJs('window["user-update"] = new UserUpdate()');
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
//                            'class' => 'pointer',
                            'data-toggle' => 'modal', 'data-target' => '#user___user__set_avatar_form_modal'
                        ]); ?>
                    </div>
                    <div class="col-xs-7">

                        <?php

                        $profiles = [];

                        foreach ($user->profileRelations as $r) {
                            $profiles[] = $r->getProfile()->getName() . '&nbsp;' . Html::a(FA::i(FA::_TRASH_O), ['/user/profile/delete', 'id' => $user->id, 'profile' => $r->profile_class], ['title' => 'Удалить профиль', 'class' => 'user___profile__delete_button']);
                        }

                        $main_data = [
                            'ID' => $user->id,
                            'Логин' => $user->username,
                            'Email' => $user->email,
                            'Часовой пояс' => $profile->user->timezone_id,
                            'Создатель' => $user->creator ? $user->creator->username : 'сам',
                            'Дата регистрации' => Yii::$app->getFormatter()->asDate($user->confirmed_at),
                            'Профили' => implode('<br/>', $profiles)
                        ];
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
                <legend>
                    Данные профиля

                    <?= Html::a('Добавить профиль', '/user/profile/create?id=' . $user->id, ['class' => 'btn btn-primary btn-xs pull-right', 'id' => 'user___profile___create_button']) ?>

                </legend>
                <?= $this->render('@app/modules/user/views/profile/update-form/_form', ['model' => $profile]); ?>
            </fieldset>
        </div>
    </div>
</div>

<?= '';//$this->render('@app/modules/user/views/user/_set_avatar_modal', ['model' => $set_avatar]);      ?>
<?= '';//$this->render('@app/modules/user/views/user/_set_password_modal', ['model' => $set_password]);      ?>

