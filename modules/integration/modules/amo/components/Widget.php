<?php

namespace app\modules\integration\modules\amo\components;

use app\modules\integration\modules\amo\models\AmoUser;
use app\modules\user\models\User;
use Yii;
use yii\base\Component;
use yii\base\Exception;

/**
 * Class Widget
 * @package app\modules\integration\modules\amo\components
 */
class Widget extends Component
{

    /**
     * Процедура автоматической регистрации и авторизации пользователей, установивших виджет ScriptDesigner в AmoCRM:
     *
     * Каждый пользователь в Амо имеет amouser (amouser), amohash (ключу), subdomain (поддомен рабочего аккаунта)
     *
     * Проверяет есть ли такой аккаунт в системе ScriptDesigner, и интегрирован ли он с текущим аккаунтом Amo.
     *     Если аккаунта нет:
     *         Происходит регистрация, интеграция и авторизация пользователя в системе ScriptDesigner
     *     Если аккаунт есть:
     *         Если включен автологин:
     *             Если интегрирован с текущим аккаунтом:
     *                 Происходит авторизация с использованием amohash.
     *             Если не интегрирован или интегрирован с другим пользователем Amo:
     *                 Выдает ошибку.
     *
     *
     * Если при регистрации пользователя система обнаруживает, что для такого subdomain нет других пользователей - она регистрирует его как head иначе как operator.
     * Когда пользователь сохраняет настройки виджета и отмечает галочку что он хочет зарегистрироваться - система пытается регистрировать его по стандартной процедуре при инициализации включенного виджета.
     * Процедура регистрации создает сразу подтвержденного пользователя.
     * Процедура регистрации логинит пользователя независимо от того указал он автологин в настройках виджета или нет.
     * Если аккаунт уже есть, процедура проверки авторизует его только если включена опция - авто-логин.
     *
     *
     * @param string $amouser
     * @param string $amohash
     * @param string $subdomain
     * @param int $auto_log_in
     * @param int $create_account
     * @param string $name
     * @param string $phone
     * @return string
     * @throws Exception
     */
    public static function auth($amouser, $amohash, $subdomain, $auto_log_in, $create_account, $name, $phone)
    {
        $result = '';

        if (Yii::$app->getUser()->getIsGuest()) {

            $user = User::find()->byUsername($amouser)->one();

            if ($user) {
                $exist = AmoUser::findOne($user->id);

                if (!$exist || $exist->amouser != $amouser) {
                    throw new Exception('Вы уже имеете учетную запись в ScriptDesigner ' . $user->username . ', но она не интегрирована с текущей учетной записью в AmoCRM. Чтобы выполнить процедуру автоматической интеграции с вашей текущей учетной записью в AmoCRM, пожалуйста, авторизуйтесь в ScriptDesigner как ' . $user->username);
                }

                if (!!(int)$auto_log_in) {
                    Yii::$app->getUser()->login($user);

                    $result = static::already($amouser, $amohash, $subdomain, $create_account);
                } else {
                    $result = "Авто-авторизация отключена";
                }
            } else {
                if (!!(int)$create_account) {
                    Account::register($amouser, $subdomain, $name, $phone);
                    $result = static::already($amouser, $amohash, $subdomain, $create_account);
                } else {
                    $result = "Авто-регистрация отключена";
                }
            }
        } else {
            $result = static::already($amouser, $amohash, $subdomain, $create_account);
        }

        return $result;
    }


    /**
     * Сценарий если пользователь уже авторизован.
     *
     * @param string $amouser
     * @param string $amohash
     * @param string $subdomain
     * @param int $create_account
     * @return string
     * @throws Exception
     */
    public static function already($amouser, $amohash, $subdomain, $create_account)
    {
        if (!User::identity()) {
            return false;
        }

        if (User::identity()->username !== $amouser && $create_account) {
            throw new Exception('Вы уже авторизованы в как ' . User::identity()->username . '. Чтобы выполнить процедуру автоматической регистрации и настройки для ' . $amouser . ', пожалуйста, завершите текущую сессию в ScriptDesigner. Если вы не хотите регистрировать новый аккаунт - выключите автоматическую регистрацию в настройках виджета.');
        }

        $result = "Вы авторизованы как " . User::identity()->username . '.';

        if (User::identity()->username == $amouser) {
            $exist = AmoUser::findOne(Yii::$app->getUser()->getId());

            if (!$exist) {
                AmoUser::add(Yii::$app->getUser()->getId(), $amouser, $amohash, $subdomain);

                $result .= " Ваша учетная запись в системе ScriptDesigner настроена.";
            } elseif ($exist->amouser != $amouser || $exist->amohash != $amohash || $exist->subdomain != $subdomain) {
                $exist->change($amouser, $amohash, $subdomain);

                $result .= " Ваша учетная запись в ScriptDesigner перенастроена. ";
            }
        }

        return $result;
    }
}