<?php

namespace app\modules\billing\controllers;

use app\modules\billing\models\BillingRateChangeHistory;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\core\components\CoreController;

/**
 * RateChangeHistoryController implements the CRUD actions for Script model.
 */
class RateChangeHistoryController extends CoreController
{
    /**
     * Rate change history for admin
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->checkAccess("billing___rate_change_history__index_all");

        return $this->render('index', [
            'data_provider' => new ActiveDataProvider([
                'query' => BillingRateChangeHistory::find(),
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
            ])
        ]);
    }
}
