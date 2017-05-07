<?php

namespace app\modules\sales\controllers;

use Yii;
use app\modules\sales\models\UserStat;
use app\modules\sales\models\UserStatSearch;
use app\modules\core\components\CoreController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserStatController implements the CRUD actions for UserStat model.
 */
class UserStatController extends CoreController
{
    /**
     * Lists all UserStat models.
     * @return mixed
     */
    public function actionIndex($excel = 0)
    {
        $this->checkAccess('sales___access');
        $searchModel = new UserStatSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if($excel){
            $searchModel->asExcel($dataProvider);
            exit;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserStat model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->checkAccess('sales___access');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the UserStat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserStat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserStat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
