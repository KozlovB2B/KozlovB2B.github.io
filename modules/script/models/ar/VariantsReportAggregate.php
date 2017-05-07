<?php

namespace app\modules\script\models\ar;

use app\modules\script\models\Call;
use Yii;

/**
 * This is the model class for table "call_hits".
 *
 * @property integer $id
 * @property integer $call_id
 * @property integer $script_id
 * @property string $node_id
 * @property string $variant_id
 *
 * @property Call $call
 */
class VariantsReportAggregate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'call_hits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['call_id'], 'integer'],
            [['node_id', 'variant_id'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('crm', 'ID'),
            'call_id' => Yii::t('crm', 'Call ID'),
            'node_id' => Yii::t('crm', 'Node ID'),
            'variant_id' => Yii::t('crm', 'Variant ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCall()
    {
        return $this->hasOne(Call::className(), ['id' => 'call_id']);
    }

    public static function collectData(Call $call)
    {
        if(!$call->call_history){
            return;
        }
        $history = json_decode($call->call_history);
        if(!$history){
            return;
        }

        foreach ($history as $h) {
            $model = new VariantsReportAggregate();
            $model->call_id = $call->id;
            $model->script_id = $call->script_id;
            $model->variant_id = $h->e;
            $variant = Variant::findOne($model->variant_id);

            if ($variant) {
                $model->node_id = $variant->node_id;
                $model->save(false);
            }
        }
    }
}
