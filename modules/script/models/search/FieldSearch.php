<?php

namespace app\modules\script\models\search;

use app\modules\user\models\UserHeadManager;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\script\models\ar\Field;

/**
 * FieldSearch represents the model behind the search form of `app\modules\script\models\ar\Field`.
 */
class FieldSearch extends Model
{
    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        return new ActiveDataProvider([
            'query' => Field::find()->byAccount(UserHeadManager::findHeadManagerByUser()->id)->orderBy(['id' => SORT_DESC])
        ]);
    }
}
