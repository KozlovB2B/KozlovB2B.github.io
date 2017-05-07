<?php

namespace app\modules\billing\controllers;

use app\modules\billing\models\UseWithdraw;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\core\components\CoreController;

/**
 * UseWithdrawController implements the CRUD actions for Script model.
 */
class UseWithdrawController extends CoreController
{
    /**
     * Rate change history for admin
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->checkAccess("billing___use_withdraw__index");

        return $this->render('index', [
            'data_provider' => new ActiveDataProvider([
                'query' => UseWithdraw::find(),
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
            ])
        ]);
    }
}
