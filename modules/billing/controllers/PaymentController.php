<?php

namespace app\modules\billing\controllers;

use app\modules\billing\components\PaymentTopUpOrder;
use app\modules\billing\models\BalanceOperations;
use app\modules\core\components\CoreController;
use app\modules\billing\models\Payment;
use Yii;
use yii\base\Exception;
use yii\helpers\Html;
use app\modules\billing\models\Balance;

/**
 * Class PaymentController
 * @package app\modules\billing\controllers
 */
class PaymentController extends CoreController
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'callback') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Payment service callback
     *
     * @param $component
     * @return \yii\web\Response
     * @throws Exception
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionCallback($component)
    {
        /** @var Payment $model */
        $model = \Yii::createObject(Payment::className());

        $model->component = $component;

        if (!$model->isActiveComponent()) {
            $this->throwException('Something went wrong.');
        }

        $t = Yii::$app->getDb()->beginTransaction();

        try {
            /** @var \app\modules\billing\components\PayServiceInterface $srv */
            $srv = $this->module->get($model->component);

            $order = $srv->callback();

            if ($order instanceof PaymentTopUpOrder) {
                if (!BalanceOperations::topUpBalance($order)) {
                    throw new Exception('Cant change balance');
                }

                if ($order->success_url) {
                    $t->commit();
                    return $this->redirect($order->success_url);
                }
            }
        } catch (Exception $e) {
            $t->rollBack();
            $this->throwException($e->getMessage());
        }

        $t->commit();

        Yii::$app->end();
    }

    /**
     * @return string
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPay()
    {
        /** @var Payment $model */
        $model = \Yii::createObject(Payment::className());

        $model->load(Yii::$app->getRequest()->post());

        /** @var \app\modules\billing\components\PayServiceInterface $pay_service */
        $pay_service = $this->module->get($model->component);

        $pay_service->setPayment($model);

        $this->ajaxValidation($pay_service->getPayment());

        if (!$pay_service->checkPayment()) {
            $this->throwException(Html::errorSummary($pay_service->getPayment(), ['header' => Yii::t('billing', 'Please change:')]));
        }

        if ($pay_service->getPayment()->validate()) {

            $balance = Balance::currentUserBalance();

            if (!$balance || $balance->currency !== $pay_service->getCurrency()) {
                $this->throwException($pay_service->getCurrency() . ' balance not found!');
            }

            $form = $pay_service->getFormCode();

            if ($form === false) {
                $this->throwException($pay_service->getErrors());
            }

            $this->result($form);
        } else {
            $this->throwException(Html::errorSummary($pay_service->getPayment(), ['header' => Yii::t('billing', 'Please change:')]));
        }
    }
}
