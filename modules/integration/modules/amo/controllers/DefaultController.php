<?php

namespace app\modules\integration\modules\amo\controllers;

use app\modules\core\components\CoreController;
use app\modules\user\models\UserHeadManager;
use Yii;
use app\modules\integration\modules\amo\models\AmoUser;

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

        $head = AmoUser::find()->andWhere(['user_id' => $hm->id])->one();

        if (!$head) {
            $head = new AmoUser();
            $head->user_id = $hm->id;
            $head->head_id = $hm->id;
        }

        return $this->render('index', [
            'head' => $head
        ]);
    }
}
