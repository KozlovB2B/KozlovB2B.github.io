<?php

namespace app\modules\site\controllers;

use app\modules\site\models\Instruction;
use Yii;
use app\modules\core\components\CoreController;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

class InstructionController extends CoreController
{
    /**
     * List of instructions
     */
    public function actionIndex()
    {
        $this->checkAccess("site___instruction__view");

        /** @var Instruction $searchModel */
        $searchModel = Yii::createObject(Instruction::className());
        $dataProvider = $searchModel->publicSearch(Yii::$app->request->get());
        $model = \Yii::createObject(Instruction::className());

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'module' => $this->module,
        ]);
    }


    /**
     * Instruction management
     */
    public function actionManage()
    {
        $this->checkAccess("site___instruction__manage");

        /** @var Instruction $searchModel */
        $searchModel = Yii::createObject(Instruction::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $model = \Yii::createObject(Instruction::className());

        return $this->render('manage', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'module' => $this->module,
        ]);
    }


    /**
     * Instruction view page
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $this->checkAccess("site___instruction__view");

        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }


    /**
     * Instruction creating
     *
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $this->checkAccess("site___instruction__manage");


        /** @var Instruction $model */
        $model = \Yii::createObject(Instruction::className());

        if (Yii::$app->request->post()) {
            $this->ajaxValidation($model);

            if ($model->save()) {
                return $this->result(\Yii::t('site', 'Saved!'));
            } else {
                $this->throwException(Html::errorSummary($model));
            }
        }

        return $this->renderPartial('_create_modal', [
            'model' => $model
        ]);
    }


    /**
     * Update instruction data
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess("site___instruction__manage");

        if (Yii::$app->request->post()) {
            $this->ajaxValidation($model);

            if ($model->save()) {
                return $this->result(\Yii::t('site', 'Saved!'));
            } else {
                $this->throwException(Html::errorSummary($model));
            }
        }

        return $this->renderPartial('_update_modal', [
            'model' => $model
        ]);
    }

    /**
     * Update instruction data
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess("site___instruction__manage");

        $model->safeDelete();

        $this->result("OK");
    }

    /**
     * Finds the Operator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Instruction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Instruction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested instruction does not exist.');
        }
    }
}
