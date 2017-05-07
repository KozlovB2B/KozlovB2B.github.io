<?php

namespace app\modules\site\models;

use app\modules\core\components\Publishable;
use app\modules\core\components\ActiveRecord;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "instruction".
 *
 * @property integer $id
 * @property integer $status_id
 * @property string $video
 * @property string $description
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 */
class Instruction extends ActiveRecord implements Publishable
{

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->status_id == Publishable::STATUS_PUBLISHED;
    }

    /**
     * @return boolean
     */
    public function isDraft()
    {
        return $this->status_id == Publishable::STATUS_DRAFT;
    }

    /**
     * @return boolean
     */
    public function isCreating()
    {
        return $this->status_id == Publishable::STATUS_CREATING;
    }

    public static function getStatuses()
    {
        return [
            Publishable::STATUS_PUBLISHED => Yii::t('site', 'Published'),
            Publishable::STATUS_DRAFT => Yii::t('site', 'Draft'),
        ];
    }

    public function getStatusName()
    {
        $statuses = $this->getStatuses();

        return isset($statuses[$this->status_id]) ? $statuses[$this->status_id] : $this->status_id;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instruction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id', 'description'], 'required'],
            [['status_id', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['content'], 'string'],
            [['video'], 'string', 'max' => 1000],
            [['video'], 'checkIsYoutube'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * Checking that video is really youtube embed link
     *
     * @return bool
     */
    public function checkIsYoutube(){
        if(strpos($this->video, "https://www.youtube.com/embed/") !== 0){
            $this->addError("Video", "Ссылка на видео должна начинаться с https://www.youtube.com/embed/! Для этого на Youtube выберите Поделиться -> HTML-код и скопируйте только ссылку на видео без сопутствующего кода.");
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('site', 'ID'),
            'status_id' => Yii::t('site', 'Status'),
            'video' => Yii::t('site', 'Youtube video url'),
            'description' => Yii::t('site', 'Description'),
            'content' => Yii::t('site', 'Content'),
            'created_at' => Yii::t('site', 'Created'),
            'updated_at' => Yii::t('site', 'Updated'),
            'deleted_at' => Yii::t('site', 'Deleted'),
        ];
    }

    /**
     * @inheritdoc
     * @return InstructionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InstructionQuery(get_called_class());
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);
        $query = new InstructionQuery($this);

        if(!empty($this->status_id)){
            $query->andWhere("[[status_id]] = :status_id", ["status_id" => $this->status_id]);
        }

        $query->active();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function publicSearch($params)
    {
        $this->load($params);
        $query = new InstructionQuery($this);
        $query->andWhere("[[status_id]] = :status_id", ["status_id" => Publishable::STATUS_PUBLISHED]);
        $query->active();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
