<?php

namespace app\modules\integration\modules\hookz\models;

use app\modules\integration\modules\hookz\components\HookEvent;
use Yii;

/**
 * This is the model class for table "integration_web_hook".
 *
 * @property integer $id
 * @property integer $head_id
 * @property integer $event
 * @property string $get
 * @property string $post
 */
class Hook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'integration_web_hook';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['head_id', 'event'], 'integer'],
            [['head_id', 'event', 'get'], 'required'],
            ['event', 'in', 'range' => array_keys(HookEvent::getList())],
            ['get', 'string', 'max' => 1024],
            ['get', 'url'],
            ['post', 'string', 'max' => 16000],
            ['post', function () {
                if ($this->post) {
                    json_decode($this->post);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $this->addError('post', 'Допускается только валидный JSON');
                        return false;
                    }
                }

                return true;
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event' => 'Событие',
            'get' => 'URL',
            'post' => 'POST-данные в формате JSON',
        ];
    }

    /**
     * @inheritdoc
     * @return HookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HookQuery(get_called_class());
    }
}
