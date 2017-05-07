<?php

namespace app\modules\script\models\ar;

use Yii;
use app\modules\script\models\Call;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "call_data".
 *
 * @property integer $id
 * @property string $data
 */
class CallData extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'call_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data'], 'string'],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Call::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @param $id
     * @param $data
     */
    public static function write($id, $data)
    {
        $model = CallData::findOne($id);

        if (!$model) {
            $model = new CallData();
            $model->id = $id;
        }

        $model->data = $data;

        $model->save(false);
    }
}
