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
class DefaultController extends CoreController
{

    /**
     * Lists all ApiCredentials models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAccess('integration___integration__manage');

        $hm = UserHeadManager::findHeadManagerByUser();

        $model = ApiCredentials::find()->andWhere(['user_id' => $hm->id])->one();

        if (!$model) {
            $model = new ApiCredentials();
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }
}
