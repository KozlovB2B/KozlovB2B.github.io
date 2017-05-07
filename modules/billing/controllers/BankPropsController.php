<?php

namespace app\modules\billing\controllers;

use app\modules\billing\models\BankProps;
use app\modules\core\components\CoreController;
use Yii;
use yii\helpers\Html;

/**
 * BankPropsController
 */
class BankPropsController extends CoreController
{

    /**
     * Edit props
     *
     * @return string
     */
    public function actionEdit()
    {
        $this->checkAccess("billing___account__manage_own");

        /** @var BankProps $props */
        $props = BankProps::find()->andWhere('account_id = ' . Yii::$app->user->getId())->one();

        if (!$props) {
            $props = Yii::createObject([
                'class' => BankProps::className(),
                'account_id' => Yii::$app->user->getId(),
                'acting_on_the_basis' => 'Устава'
            ]);
        }

        if ($props->load(\Yii::$app->getRequest()->post())) {

            $this->ajaxValidation($props);

            if ($props->save()) {
                return $this->result(Yii::t('billing', 'Props data updated!'));
            } else {
                $this->throwException(Html::errorSummary($props));
            }
        } else {
            return $this->renderPartial('_edit_modal', [
                'props' => $props
            ]);
        }
    }
}
