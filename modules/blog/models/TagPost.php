<?php

namespace app\modules\blog\models;

use Yii;

/**
 * This is the model class for table "BlogTagPost".
 *
 * @property integer $post_id
 * @property integer $tag_id
 *
 * @property Post $post
 * @property Tag $tag
 */
class TagPost extends \yii\db\ActiveRecord
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'BlogTagPost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'tag_id'], 'required'],
            [['post_id', 'tag_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_id' => 'Post ID',
            'tag_id' => 'Tag ID',
        ];
    }
}
