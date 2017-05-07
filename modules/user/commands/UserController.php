<?php

namespace app\modules\user\commands;

use app\modules\user\models\profile\ProfileRelation;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use app\modules\user\models\User;
use app\modules\user\models\profile\Profile;
use yii\base\Exception;
use app\modules\user\models\profile\Head;
use app\modules\user\models\profile\Operator;
use app\modules\user\components\OldProfile;
use app\modules\user\models\UserOperator;


/**
 * Class CreateController
 * @package app\modules\user\commands
 */
class UserController extends Controller
{

    public function actionConvert()
    {
        /** @var UserHeadManager[] $heads */
        $heads = UserHeadManager::find()->all();

        foreach ($heads as $hm) {
            if (!User::findOne($hm->id)) {
                continue;
            }

            if (ProfileRelation::findOne($hm->id)) {
                continue;
            }

            $profile_old = OldProfile::findOne($hm->id);

            if (!$profile_old) {
                continue;
            }

            $relation = new ProfileRelation();
            $relation->is_current = 1;
            $relation->user_id = $hm->id;
            $relation->profile_class = 'Head';

            if (!$relation->save(false)) {
                $this->stdout("Не удалось сохранить реляцию head " . $relation->user_id . ': ' . implode(',', $relation->getFirstErrors()) . "\n", Console::FG_RED);
            }

            $head = new Head();
            $head->user_id = $relation->user_id;
            $head->accept_terms = 1;
            $head->first_name = $profile_old->name;
            $head->phone = $hm->phone;

            if (!$head->save(false)) {
                $this->stdout("Не удалось сохранить профиль head " . $hm->id . ': ' . implode(',', $head->getFirstErrors()) . "\n", Console::FG_RED);
            } else {
                $this->stdout("Head {$hm->id}\n");
            }

            /** @var UserOperator[] $operators */
            $operators = UserOperator::find()->where('head_id=:head_id', [':head_id' => $head->id])->all();

            if (!$operators) {
                continue;
            }

            foreach ($operators as $op) {

                $user = User::findOne($op->id);

                if ($user) {
                    $rel = new ProfileRelation();
                    $rel->is_current = 1;
                    $rel->user_id = $op->id;
                    $rel->profile_class = 'Operator';

                    if (!$rel->save(false)) {
                        $this->stdout("Не удалось сохранить реляцию профиля оператора " . $rel->user_id . ': ' . implode(',', $rel->getFirstErrors()) . "\n", Console::FG_RED);
                    }

                    $operator = new Operator();
                    $operator->head_id = $head->user_id;
                    $operator->user_id = $rel->user_id;
                    $operator->first_name = $op->first_name;
                    $operator->last_name = $op->last_name;

                    if (!$operator->save(false)) {
                        $this->stdout("Не удалось сохранить профиль оператора " . $operator->user_id . ': ' . implode(',', $operator->getFirstErrors()) . "\n", Console::FG_RED);
                    } else {
                        $this->stdout("Operator {$operator->user_id}\n");
                    }
                }
            }
        }
    }

    /**
     * Создание пользователя. [Профиль], [логин], [email], [пароль], [поле_профиля:значение], ... Пример: php yii user/user/create Admin admin admin@god.ru asdasd first_name:Остап last_name:Бендер
     *
     * Пользователь в системе может быть создан только с определенным профилем.
     * Пользователь без профиля существовать не может.
     *
     * Первым аргументом указывается название класса профиля будущего пользователя - например: Admin, Operator, Owner и т. п.
     * потом указывается логин, пароль а затем спецефичные поля профиля в виде ключ:значение
     *
     * @param string $profile Профиль
     * @param string $username Имя пользователя
     * @param string $email Почта
     * @param string $password Пароль
     * @return int
     */
    public function actionCreate($profile, $username, $email, $password)
    {
        $profile_class = ProfileRelation::profileClassFullName($profile);

        if (!class_exists($profile_class)) {
            $this->stdout("Не найден класс для профиля $profile\n", Console::FG_RED);
            return 1;
        }

        /** @var User $user */
        $user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'create',
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'confirmed_at' => time()
        ]);

        /** @var Profile $profile */
        $profile = Yii::createObject([
            'class' => $profile_class,
            'scenario' => 'insert',
        ]);

        $args = func_get_args();

        // Если у нас передано больше 4 параметров - значит были введены данные профиля
        for ($i = 4, $args_count = count($args); $i < $args_count; $i++) {

            if (strpos($args[$i], ':') === false) {
                $this->stdout("Данные профиля должны быть указаны как аргументы ключ:значение. Например - fist_name:Роман phone:+79132229945\n", Console::FG_RED);

                return 1;
            }

            list($attribute, $value) = explode(':', $args[$i]);

            $profile->$attribute = $value;
        }

        if ($user->create($profile)) {
            $this->stdout("Пользователь был создан!\n", Console::FG_GREEN);
        } else {
            $this->stdout("Пожалуйста исправьте следующие ошибки:\n", Console::FG_RED);

            if (count($user->errors)) {
                $this->stdout("Корневая запись:\n", Console::FG_RED);

                foreach ($user->errors as $errors) {
                    foreach ($errors as $error) {
                        $this->stdout(' - ' . $error . "\n", Console::FG_RED);
                    }
                }
            }

            if (count($profile->errors)) {
                $this->stdout("Запсь профиля:\n", Console::FG_RED);

                foreach ($profile->errors as $errors) {
                    foreach ($errors as $error) {
                        $this->stdout(' - ' . $error . "\n", Console::FG_RED);
                    }
                }
            }

            return 1;
        }

        return 0;
    }

    /**
     * Удаление пользователя по ид или логину
     *
     * @param string $search ID пользователя или логин
     * @return int
     */
    public function actionDelete($search)
    {
        $user = User::find()->byIdUsernameOrEmail($search)->one();

        if ($user === null) {
            $this->stdout("Пользователь $search не найден!\n", Console::FG_RED);
            return 1;
        }

        if ($this->confirm('Вы действительно хотите удалить пользователя ' . $user->username . ' из системы? Восстановить учетную запись будет невозможно!')) {

            if ($user->delete()) {
                $this->stdout("Пользователь был удален!\n", Console::FG_GREEN);
                return 0;
            } else {
                $this->stdout("При удалении пользователя возникли проблемы.\n", Console::FG_RED);
                return 1;
            }
        }

        return 0;
    }

    /**
     * Установка нового пароля для пользователя
     *
     * @param string $search ID пользователя или логин
     * @param string $password
     * @return int
     */
    public function actionPassword($search, $password)
    {
        $user = User::find()->byIdUsernameOrEmail($search)->one();

        if ($user === null) {
            $this->stdout("Пользователь $search не найден!\n", Console::FG_RED);
            return 1;
        }

        if ($this->confirm('Вы действительно хотите поменять пароль пользователя ' . $user->username . ' на ' . $password . ' ?')) {
            if ($user->resetPassword($password)) {
                $this->stdout("Пароль пользователя {$user->username}  был изменен! Новый пароль: $password\n", Console::FG_GREEN);
            } else {
                $this->stdout("Во время изменения пароля возникли проблемы.\n", Console::FG_RED);
            }
        }

        return 0;
    }
}
