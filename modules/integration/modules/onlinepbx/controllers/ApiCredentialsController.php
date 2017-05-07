<?php

namespace app\modules\integration\modules\onlinepbx\controllers;

use app\modules\core\components\CoreController;
use app\modules\user\models\UserHeadManager;
use Yii;
use app\modules\integration\modules\onlinepbx\models\ApiCredentials;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;

/**
 * ApiCredentialsController implements the CRUD actions for ApiCredentials model.
 */
class ApiCredentialsController extends CoreController
{

    /**
     * Lists all ApiCredentials models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAccess('integration___integration__manage');

        $model = ApiCredentials::find()->andWhere(['user_id' => UserHeadManager::findHeadManagerByUser()])->one();

        if (!$model) {
            $model = new ApiCredentials();
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new ApiCredentials model.
     * @return mixed
     */
    public function actionUpdate()
    {
        $this->checkAccess('integration___integration__manage');

        $head_manager = UserHeadManager::findHeadManagerByUser();

        $model = ApiCredentials::find()->andWhere(['user_id' => $head_manager->id])->one();

        if (!$model) {

            $model = Yii::createObject([
                'class' => ApiCredentials::className(),
                'user_id' => $head_manager->id,
                'created_at' => time(),
                'is_active' => 1
            ]);

        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->result(Yii::t('onlinepbx', 'Saved!'));
        } else {
            return $this->throwException(Html::errorSummary($model));
        }
    }


    /**
     * Deletes an existing ApiCredentials model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->checkAccess('integration___integration__manage');

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ApiCredentials model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ApiCredentials the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ApiCredentials::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
