<?php

namespace app\modules\billing\controllers;

use app\modules\billing\models\Rate;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\core\components\CoreController;
use app\modules\billing\models\Account;
use yii\helpers\Html;

/**
 * RateController implements the CRUD actions for Script model.
 */
class RateController extends CoreController
{

    /**
     * Lists all Script models.
     * @return mixed
     */
    public function actionManage()
    {
        $this->checkAccess("billing___rate__manage");

        $model = new Rate();
        $data_provider = new ActiveDataProvider(['query' => Rate::find()->orderBy(['id' => SORT_DESC])]);
        return $this->render('manage', [
            'model' => $model,
            'data_provider' => $data_provider,
        ]);
    }


    /**
     * Public rates list
     *
     * @return string
     */
    public function actionList()
    {
        $this->setPublicLayout();

        return $this->render('list', [
            'data_provider' => new ActiveDataProvider(['query' => Rate::find()->active()->forCurrentDivision()->orderBy(['id' => SORT_ASC])])
        ]);
    }

    /**
     * Public rates list
     *
     * @return string
     */
    public function actionChangeRestrictions($id)
    {
        return $this->renderPartial('_change_restrictions', [
            'rate' => Rate::find()->where('id=' . $id)->one()
        ]);
    }
}
