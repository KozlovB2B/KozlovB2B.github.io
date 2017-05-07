<?php

namespace app\modules\user\controllers;

use app\modules\core\components\CoreController;
use app\modules\user\models\LoginForm;
use app\modules\user\models\User;
use Yii;

use app\modules\user\models\PasswordRecoveryForm;
use app\modules\user\models\Token;
use app\modules\core\components\AjaxValidationTrait;
use yii\base\Exception;

/**
 * Class PasswordRecoveryController
 *
 * Восстановление пароля пользователя
 *
 * @package app\modules\user\controllers
 */
class PasswordRecoveryController extends CoreController
{
    use AjaxValidationTrait;

    /**
     * @param $id
     * @param $code
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRequest()
    {
        /** @var PasswordRecoveryForm $model */
        $model = Yii::createObject([
            'class' => PasswordRecoveryForm::className(),
            'scenario' => 'request',
        ]);

        $saved = false;

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $saved = $model->sendRecoveryMessage();
        }

        $this->layout = "@app/modules/site/views/layouts/standalone_public";

        return $this->render('request', [
            'model' => $model,
            'saved' => $saved
        ]);
    }


    /**
     * @param $id
     * @param $code
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionReset($id, $code)
    {
        /** @var Token $token */
        $token = Token::find()->where(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_PASSWORD_RECOVERY])->one();

        if ($token === null || $token->isExpired || $token->user === null) {
            throw new Exception('Ссылка для восстановления пароля недействительна или устарела. Пожалуйста запросите новую.');
        }

        /** @var PasswordRecoveryForm $model */
        $model = Yii::createObject([
            'class' => PasswordRecoveryForm::className(),
            'scenario' => 'reset',
        ]);

//        $login = false;

        if ($model->load(Yii::$app->getRequest()->post()) && $model->resetPassword($token)) {
            return $this->redirect('/login');
        }
//        $login = new LoginForm();
        $this->layout = "@app/modules/site/views/layouts/standalone_public";

        return $this->render('reset', [
            'model' => $model
        ]);
    }
}
