<?php

namespace app\modules\user\controllers;

use app\modules\user\models\form\InviteOperatorForm;
use app\modules\user\models\profile\Operator;
use Yii;
use app\modules\core\components\AjaxValidationTrait;
use app\modules\core\components\CoreController;
use yii\base\Exception;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use app\modules\user\helpers\Password;
use yii\web\Response;

/**
 * Class OperatorController
 *
 * Разные действия с аккаунтом оператора
 *
 * @package app\modules\user\controllers.
 */
class OperatorController extends CoreController
{
    use AjaxValidationTrait;

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['block-unblock'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionInvite()
    {
        $this->checkAccess('site___user_operator__create');

        $model = new InviteOperatorForm();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->invite();
        }

        return $this->renderAjax('_invite_modal', [
            'model' => $model
        ]);
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess("site___user_operator__update", ["user-operator" => $model]);


        $saved = false;

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $saved = $model->updateData();
        }

        return $this->renderAjax('_update_modal', [
            'model' => $model,
            'saved' => $saved,
        ]);
    }


    /**
     * @return string
     */
    public function actionHeadDashboardList()
    {
        $this->checkAccess('user___head__dashboard');

        return $this->renderAjax('_head_dashboard_list');
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionBlockUnblock($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess("site___user_operator__update", ["user-operator" => $model]);

        if ($model->user->getIsBlocked()) {

            if (!Yii::$app->getUser()->can('site___user_operator__create')) {
                throw new Exception('You cant add user');
            }

            return $model->user->unblock();
        } else {
            return $model->user->block();
        }
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
