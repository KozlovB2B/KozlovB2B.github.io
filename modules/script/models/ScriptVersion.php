<?php

namespace app\modules\script\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "script_version".
 *
 * @property integer $id
 * @property integer $script_id
 * @property integer $version
 * @property integer $start_node
 * @property string $md5
 * @property string $data
 * @property integer $created_at
 */
class ScriptVersion extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                "class" => TimestampBehavior::className(),
                "updatedAtAttribute" => false,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['script_id', 'version', 'md5', 'data', 'created_at'], 'required'],
            [['script_id', 'version', 'created_at', 'start_node'], 'integer'],
            [['data'], 'string'],
            [['md5'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('script', 'ID'),
            'script_id' => Yii::t('script', 'Script'),
            'start_node' => Yii::t('script', 'Start node'),
            'version' => Yii::t('script', 'Script version'),
            'md5' => Yii::t('script', 'Script data hash'),
            'data' => Yii::t('script', 'Script data'),
            'created_at' => Yii::t('script', 'Created'),
        ];
    }

    /**
     * @return array Script data as map
     */
    public function map()
    {
        $data = json_decode($this->data);
        $result = [
            'nodes' => [],
            'edges' => []
        ];

        foreach ($data->nodes as $n) {
            if(!isset($n->id)){
                continue;
            }

            $result['nodes'][$n->id] = $n;
            foreach($n->columns as $c){
                if(!isset($c->id)){
                    continue;
                }

                $result['edges'][$c->id] = $c;
            }
        }

        return $result;
    }
}
