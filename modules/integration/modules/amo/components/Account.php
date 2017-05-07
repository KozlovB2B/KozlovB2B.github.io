<?php

namespace app\modules\integration\modules\amo\components;

use app\modules\integration\modules\amo\models\AmoUser;
use app\modules\user\models\profile\Operator;
use app\modules\user\models\User;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use app\modules\user\helpers\Password;
use app\modules\user\models\profile\Head;
use app\modules\user\models\UserHeadManager;
use app\modules\aff\models\Account as AffAccount;
use app\modules\billing\models\Account as BillingAccount;
use app\modules\user\components\AmoLeadCreator;

/**
 * Class Widget
 * @package app\modules\integration\modules\amo\components
 */
class Account extends Component
{
    /**
     * Регистрирует нового пользователя с username как email аккаунта Amo
     *
     * Если head уже есть для такого $subdomain - регистрирует оператора, иначе регистрирует как head
     *
     * @param string $amouser
     * @param string $subdomain
     * @param string $name
     * @param string $phone
     * @throws Exception
     */
    public static function register($amouser, $subdomain, $name, $phone)
    {
        if (!$phone) {
            $phone = '';
        }

        if (!$name) {
            $name = $amouser;
        }

        $head = AmoUser::find()->where('user_id = head_id AND subdomain = :subdomain', [':subdomain' => $subdomain])->one();

        if ($head) {
            static::registerOperator($amouser, $name, $head->user_id);
        } else {
            static::registerHead($amouser, $name, $phone);
        }
    }

    /**
     * Регистрирует аккаунт оператора
     *
     * @param $amouser
     * @param $name
     * @param $head_id
     * @return bool
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected static function registerOperator($amouser, $name, $head_id)
    {
        /** @var User $user */
        $user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'create',
            'username' => $amouser,
            'confirmed_at' => time(),
            'v2' => 1,
            'password' => Password::generate(8),
            'registration_ip' => Yii::$app->getRequest()->getUserIP(),
            'email' => $amouser
        ]);

        /** @var Operator $operator */
        $operator = Yii::createObject([
            'class' => Operator::className(),
            'scenario' => 'create',
            'head_id' => $head_id,
            'first_name' => $name
        ]);

        if (!$user->create($operator)) {
            throw new Exception('Не удалось зарегистрировать оператора: ' .$head_id.' '. implode(',', $user->getFirstErrors()) . ' ' . implode(',', $operator->getFirstErrors()));
        }

        Yii::$app->getUser()->login($user);

        return true;
    }

    /**
     * Регистрирует новый аккаунт в системе по указанным данным.
     *
     * @param string $amouser
     * @return string
     * @throws Exception
     */
    protected static function registerHead($amouser, $name, $phone)
    {
        /** @var User $user */
        $user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'create',
            'username' => $amouser,
            'confirmed_at' => time(),
            'v2' => 1,
            'password' => Password::generate(8),
            'registration_ip' => Yii::$app->getRequest()->getUserIP(),
            'email' => $amouser
        ]);

        /** @var Head $head */
        $head = Yii::createObject([
            'class' => Head::className(),
            'scenario' => 'amo-register',
            'phone' => $phone,
            'first_name' => $name,
            'accept_terms' => 1
        ]);

        if (!$user->create($head)) {
            throw new Exception('Не удалось зарегистрировать аккаунт: ' . implode(',', $user->getFirstErrors()) . ' ' . implode(',', $head->getFirstErrors()));
        }

        $user->subscribeToUnisenderLetters();

        /** @var UserHeadManager $head_manager */
        $head_manager = Yii::createObject([
            'class' => UserHeadManager::className(),
            'phone' => $phone
        ]);

        $head_manager->id = $user->id;
        $head_manager->save(false);

        Yii::$app->getModule('billing');
        Yii::$app->getModule('script');
        AffAccount::register($head_manager);
        BillingAccount::register($head_manager);

        try {
            AmoLeadCreator::create($user->id, $name, $phone, $amouser);
        } catch (\Exception $e) {

        }

        Yii::$app->getUser()->login($user);

        return true;
    }

    public static function managers(AmoUser $user)
    {
        $api = new AmoApi($user);
        $api->auth();
        $result = $api->request(new AmoRequest(AmoRequest::GET, null, ['accounts', 'current']));
        echo json_encode($result);
        exit;
        var_dump($result->result->account);
        exit;
    }

}