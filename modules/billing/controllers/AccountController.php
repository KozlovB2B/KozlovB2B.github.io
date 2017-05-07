<?php

namespace app\modules\billing\controllers;

use app\modules\billing\models\Account;
use app\modules\billing\models\Rate;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\helpers\Html;
use app\modules\core\components\CoreController;

/**
 * AccountController implements the CRUD actions for Script model.
 */
class AccountController extends CoreController
{

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'manage') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Script models.
     * @return mixed
     */
    public function actionManage()
    {
        $this->enableCsrfValidation = false;

        if(Yii::$app->getUser()->can("billing___account__manage_own")){
            return $this->render('manage', [
                'account' => Account::findOne(Yii::$app->user->getId()),
            ]);
        }else{
            return $this->render('login_as_admin');
        }
    }


    /**
     *
     *
     * @param $user_id
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSetRate($user_id)
    {
        $this->checkAccess("billing__rate__set");

        /** @var $user UserHeadManager */
        $user = UserHeadManager::findOne($user_id);

        /** @var $account Account */
        $account = Account::findOne($user_id);

        if (Yii::$app->getRequest()->post('Rate')['id']) {
            $rate = Rate::findOne(Yii::$app->getRequest()->post('Rate')['id']);
        } else {
            /** @var $rate Rate */
            $rate = Yii::createObject([
                'class' => Rate::className(),
                'user_id' => $user->id,
                'is_default' => 0,
                'division' => $user->division,
                'currency' => $user->balance->currency,
            ]);
            if (Yii::$app->request->post()) {
                if (!$rate->load(Yii::$app->request->post()) || !$rate->validate() || !$rate->save()) {
                    $this->throwException(Html::errorSummary([$rate]));
                }
            }
        }

        $account->is_trial = 0;

        if (Yii::$app->request->post()) {
            if ($account->applyRate($rate, [], Yii::t('billing', 'User change plan'))) {
                $this->result(Yii::t('billing', 'Plan is set!'));
            } else {
                if ($rate->user_id) {
                    $rate->delete();
                }

                $this->throwException(Html::errorSummary([$account]));
            }
        } else {
            return $this->renderPartial('_set_rate_modal', [
                'rate' => $rate,
                'account' => $account,
                'user' => $user,
            ]);
        }
    }

    /**
     * @throws \yii\base\Exception
     */
    public function actionChangeRate()
    {
        /** @var $account Account */
        $account = Account::findOne(Yii::$app->user->getId());

        $this->checkAccess("billing__rate__change", ['account' => $account]);

        /** @var $rate Rate */
        $rate = Rate::findOne(Yii::$app->getRequest()->post('Account')['rate_id']);

        $account->is_trial = 0;

        if ($account->applyRate($rate, [], Yii::t('billing', 'User change plan'))) {
            $this->result(Yii::t('billing', 'Plan changed!'));
        } else {
            $this->throwException(Html::errorSummary($account));
        }
    }

    /**
     * Edit props
     *
     * @return string
     */
    public function actionUpdate($id)
    {
        $this->checkAccess("user___account__manage");

        /** @var Account $model */
        $model = Account::find()->andWhere('id = ' . $id)->one();

        $model->trial_till = date('Y-m-d', $model->trial_till);

        if ($model->load(\Yii::$app->getRequest()->post())) {

            $this->ajaxValidation($model);

            if ($model->save()) {
                return $this->result('Данные аккаунта обновлены!');
            } else {
                $this->throwException(Html::errorSummary($model));
            }
        } else {
            return $this->renderPartial('_update_modal', [
                'model' => $model
            ]);
        }
    }

}
