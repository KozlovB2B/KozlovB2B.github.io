<?php

namespace app\modules\script\models\ar;

use Yii;
use app\modules\script\models\query\VariantQuery;
use yii\db\ActiveRecord;
use app\modules\script\models\ar\Script;

/**
 * Концепция работы узлов и вариантов ответов в скрипте.
 *
 * В скрипте может быть множество узлов. У каждого узла есть неограниченное количество вариантов ответов.
 * Каждый вариант ответа может указывать на любой узел кроме родительского.
 *
 * Узлы и варианты будучи созданными не могут быть полностью удалены. Они лишь помечаются как удаленные с использованиием
 * метки deleted_at.
 *
 * Метка deleted_at указывает на UNIX Timestamp когда произошло событие удаления. Если вариант указывал на узел, который
 * был удален - эта ссылка сохраняется и при восстановлении узла нет нужны исправлять данные в варианте.
 *
 * Удаленные узлы просто не показываются в редакторе и не используются в прогонщике точно так же как и удаленные варианты.
 * Но их можно легко восстановить просто обнулив метку deleted_at.
 *
 * В системе может присутствовать сборщик мусора, который удаляет из базы данных все узлы и варианты имеющие метку deleted_at
 * старше недели или любого другого периода определяющего достаточность давности удаления узла чтобы его можно было удалить.
 * Предполагается что восстановить столь давно удаленные данные пользователь уже не может средствами своего интерфейса и их можно затирать.
 * При этом база данных автоматически обнуляет ссылки на удаляемые узлы через триггер MySql: ON DELETE SET NULL
 *
 *
 *
 * This is the model class for table "script_variant".
 *
 * @property integer $id
 * @property integer $script_id
 * @property integer $node_id
 * @property integer $target_id
 * @property string $content
 * @property integer $deleted_at
 * @property integer $created_at
 *
 * @property Node $target
 * @property Node $parent
 * @property Script $script
 */
class Variant extends ActiveRecord
{
    /**
     * @var int Need to convert from old data
     */
    public $old_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_variant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'unique'],
            [['id', 'node_id', 'target_id'], 'string', 'max' => 64],
            [['script_id', 'node_id'], 'required'],
            [['script_id', 'deleted_at', 'created_at'], 'integer'],
            [['content'], 'string', 'max' => 128],
            [['target_id'], 'exist', 'skipOnError' => true, 'targetClass' => Node::className(), 'targetAttribute' => ['target_id' => 'id']],
            [['node_id'], 'exist', 'skipOnError' => true, 'targetClass' => Node::className(), 'targetAttribute' => ['node_id' => 'id']],
            [['script_id'], 'exist', 'skipOnError' => true, 'targetClass' => Script::className(), 'targetAttribute' => ['script_id' => 'id']],
        ];
    }

    public function init()
    {
        $this->on(static::EVENT_BEFORE_VALIDATE, function () {

            if (!$this->script_id) {
                $this->script_id = $this->parent->script_id;
            }

            if (!$this->target_id) {
                $this->target_id = null;
            }

        });

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('script', 'ID'),
            'status_id' => Yii::t('script', 'Status (1 - Draft, 2 - Published)'),
            'script_id' => Yii::t('script', 'Parent script'),
            'source' => Yii::t('script', 'Source node'),
            'target_id' => Yii::t('script', 'Target node'),
            'content' => Yii::t('script', "Client's possible reply"),
            'created_at' => Yii::t('script', 'Created At'),
            'updated_at' => Yii::t('script', 'Updated At'),
            'deleted_at' => Yii::t('script', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarget()
    {
        return $this->hasOne(Node::className(), ['id' => 'target_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Node::className(), ['id' => 'node_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScript()
    {
        return $this->hasOne(Script::className(), ['id' => 'script_id']);
    }

    /**
     * @inheritdoc
     * @return VariantQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VariantQuery(get_called_class());
    }
}
