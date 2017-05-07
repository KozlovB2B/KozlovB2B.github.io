<?php

namespace app\modules\site\models;

use Yii;
use yii\base\Exception;
use yii\bootstrap\Html;
use yii\web\NotFoundHttpException;
use app\modules\user\models\User;
use app\modules\user\models\UserHeadManager;

/**
 * This is the model class for table "MultiSessionGuard".
 *
 * @property integer $id
 * @property string $token
 * @property integer $user_id
 * @property string $ip
 * @property integer $created_at
 *
 * @property User $user
 */
class MultiSessionGuard extends \yii\db\ActiveRecord
{
    /**
     * @const integer Token TTL
     */
    const TOKEN_TTL = 300;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'MultiSessionGuard';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token'], 'required'],
            [['token'], 'unique'],
            [['user_id', 'created_at'], 'integer'],
            [['token'], 'string', 'max' => 40],
            [['ip'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'user_id' => 'User ID',
            'ip' => 'Ip',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Checking if another user session is active
     *
     * @param int $user_id User's id
     * @return boolean
     */
    public static function check($user_id)
    {
        // todo включить после релиза
        return false;

        return Session::find()->andWhere('user_id=' . $user_id)->exists();
    }

    /**
     * Checking if another user session is active
     *
     * @param int $user_id User's id
     * @return boolean
     */
    public static function checkOtherSessions($user_id)
    {
        // todo включить после релиза
        return false;

        if (!isset($_COOKIE[Yii::$app->getSession()->getName()])) {
            return false;
        }

        return Session::find()->andWhere('user_id = :user_id AND id != :id', ['user_id' => $user_id, 'id' => $_COOKIE[Yii::$app->getSession()->getName()]])->exists();
    }

    /**
     * Creating token to terminate all user old sessions
     *
     * @param  integer $user_id
     * @return MultiSessionGuard
     * @throws Exception
     */
    public static function create($user_id)
    {
        $t = new MultiSessionGuard();
        $t->token = bin2hex(openssl_random_pseudo_bytes(20));
        $t->user_id = $user_id;
        $t->ip = Yii::$app->getRequest()->getUserIP();
        $t->created_at = time();

        MultiSessionGuard::deleteAll('user_id=' . $t->user_id);

        if (!$t->save()) {
            throw new Exception(Html::errorSummary($t));
        }

        return $t;
    }

    /**
     * Terminating all user active sessions
     *
     * @return false|int
     * @throws Exception
     */
    public function useToken()
    {
        if (!$this->user) {
            throw new Exception('User not found!');
        }

        if ($this->ip !== Yii::$app->getRequest()->getUserIP()) {
            throw new Exception('Access denied!');
        }

        if ($this->created_at < time() - MultiSessionGuard::TOKEN_TTL) {
            $this->delete();
            throw new Exception('Token is expired and has been deleted!');
        }

        Session::deleteAll('user_id=' . $this->user_id);
        Yii::$app->getUser()->login($this->user, Yii::$app->getModule('user')->rememberFor);
        $this->delete();
    }


    /**
     * Terminating all user active sessions
     *
     * @return false|int
     * @throws Exception
     */
    public function terminateOtherSessions()
    {
        if (!isset($_COOKIE[Yii::$app->getSession()->getName()])) {
            throw new NotFoundHttpException();
        }

        $sid = $_COOKIE[Yii::$app->getSession()->getName()];

        if (!$this->user) {
            throw new Exception('User not found!');
        }

        if ($this->ip !== Yii::$app->getRequest()->getUserIP()) {
            throw new Exception('Access denied!');
        }

        if ($this->created_at < time() - MultiSessionGuard::TOKEN_TTL) {
            $this->delete();
            throw new Exception('Token is expired and has been deleted!');
        }

        Session::deleteAll('user_id = :user_id AND id != :id', ['user_id' => $this->user_id, 'id' => $sid]);
        $this->delete();
    }

    /**
     * Check is owner of this token head manager or not
     *
     * @return bool
     */
    public function isHeadManager()
    {
        return UserHeadManager::find()->andWhere('id=' . $this->user_id)->exists();
    }
}
