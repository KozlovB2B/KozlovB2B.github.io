<?php

namespace app\modules\user\models;

use app\modules\script\models\SipAccount;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "SiteUserOperator".
 *
 * @property integer $id
 * @property integer $head_id
 * @property integer $first_name
 * @property integer $last_name
 *
 * Relations
 * @property User $user
 * @property SipAccount $sip
 */
class UserOperator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SiteUserOperator';
    }


}
