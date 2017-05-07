<?php
namespace app\modules\integration\modules\amo\controllers;

use app\modules\core\components\BaseCoreController;
use app\modules\user\models\UserHeadManager;
use Yii;
use app\modules\integration\modules\amo\models\AmoUser;
use app\modules\integration\models\EnabledList;
use yii\web\NotFoundHttpException;

/**
 * ScriptController
 */
class UserController extends BaseCoreController
{
    /**
     * Lists all AmoUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAccess('integration___integration__manage');

        $hm = UserHeadManager::findHeadManagerByUser();

        $head = AmoUser::findOne($hm->id);

        if (!$head) {
            $head = new AmoUser();
            $head->user_id = $hm->id;
            $head->head_id = $hm->id;
        }

        return $this->render('index', [
            'head' => $head
        ]);
    }

    /**
     * Creates a new ApiCredentials model.
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->checkAccess('integration___integration__manage');

        $head_manager = UserHeadManager::findHeadManagerByUser();

        /** @var AmoUser $model */
        $model = AmoUser::findOne($id);

        if (!$model) {
            $model = Yii::createObject([
                'class' => AmoUser::className(),
                'user_id' => $id,
                'head_id' => $head_manager->id
            ]);
        }

        $saved = false;

        if (Yii::$app->request->post()) {

            $model->load(Yii::$app->request->post());

            if (!$model->amouser && !$model->amohash && !$model->subdomain && !$model->getIsNewRecord()) {
                $saved = $model->delete();
            } else {
                $saved = $model->save();
            }
        }

        return $this->render('_update_form', ['model' => $model, 'saved' => $saved]);
    }


    /**
     * Deletes an existing AmoUser model.
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
     * Finds the AmoUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AmoUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AmoUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
