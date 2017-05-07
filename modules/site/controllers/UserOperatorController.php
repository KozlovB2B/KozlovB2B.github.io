<?php

namespace app\modules\site\controllers;

use app\modules\user\models\profile\Operator;
use Yii;
use app\modules\core\components\CoreController;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use app\modules\user\helpers\Password;

class OperatorController extends CoreController
{



    /**
     * Block or unblock operator.
     * Head manager can block only own operators.
     *
     * @param int $id Operator ID
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionBlockUnblock($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess("site___user_operator__update", ["user-operator" => $model]);

        if ($model->user->getIsBlocked()) {
            if (!Yii::$app->getUser()->can('site___user_operator__create')) {
                $this->throwException('You cant add user');
            }
            $model->user->unblock();
            $this->result(Yii::t("site", "Operator unblocked!"));
        } else {
            $model->user->block();
            $this->result(Yii::t("site", "Operator blocked!"));
        }
    }

    /**
     * Block or unblock operator.
     * Head manager can block only own operators.
     *
     * @param int $id Operator ID
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess("site___user_operator__update", ["user-operator" => $model]);

        if (Yii::$app->request->post()) {
            $this->ajaxValidation($model);
//            var_dump($model->updateData());exit;

            if ($model->load(Yii::$app->request->post()) && $model->updateData()) {
                return $this->result(\Yii::t('site', 'Saved!'));
            } else {
                $this->throwException(Html::errorSummary($model->user));
            }
        }

        return $this->renderPartial('_update_modal', [
            'model' => $model
        ]);
    }

    /**
     * Finds the Operator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Operator the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Operator::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested operator does not exist.');
        }
    }
}
