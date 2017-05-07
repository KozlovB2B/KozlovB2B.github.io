<?php
namespace app\modules\integration\modules\apiv2\controllers;

use app\modules\integration\modules\apiv2\components\BaseController;
use app\modules\user\models\profile\Designer;
use app\modules\user\models\profile\Head;
use app\modules\user\models\profile\Operator;
use app\modules\user\models\User;
use Yii;

/**
 * CheckController
 */
class UserController extends BaseController
{
    /**
     * @return array
     * @throws \Exception
     */
    public function actionCheck()
    {
        return [
            'key' => $this->_key,
            'user_id' => $this->_user_head_manager->id,
        ];
    }

    /**
     * @return array
     */
    public function actionList()
    {
        $head = Head::findOne($this->_user_head_manager->id);
        $operators = Operator::find()->with('user')->byHead($this->_user_head_manager->id)->all();
        $designers = Designer::find()->with('user')->byHead($this->_user_head_manager->id)->all();

        $result = [
            [
                'id' => $this->_user_head_manager->id,
                'role' => $head->getRole(),
                'login' => $head->user->username,
                'auth_token' => $head->user->auth_key
            ]
        ];

        foreach ($operators as $o) {
            $result[] = [
                'id' => $o->user->id,
                'role' => $o->getRole(),
                'login' => $o->user->username,
                'auth_token' => $o->user->auth_key
            ];
        }

        foreach ($designers as $d) {
            $result[] = [
                'id' => $d->user->id,
                'role' => $d->getRole(),
                'login' => $d->user->username,
                'auth_token' => $d->user->auth_key
            ];
        }

        return $result;
    }

    /**
     * @param $id
     * @param $key
     * @return \yii\web\Response
     */
    public function actionAuth($id, $token)
    {
        $user = User::findOne($id);

        if (!$user || $token != $user->auth_key) {
            $this->error('Неверный ID пользователя или ключ авторизации!');
        }

        Yii::$app->getUser()->login($user);

        return $this->redirect('/');
    }
}