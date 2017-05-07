<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 2015-10-09
 * Time: 03:11
 */

namespace app\modules\core\components;

use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "node".
 *
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * Mark record as deleted
     *
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function safeDelete()
    {
        if (!array_key_exists('deleted_at', $this->getAttributes())) {
            throw new InvalidConfigException('You can safe delete only models with "deleted_at" property!');
        }

        $this->deleted_at = time();
        $this->update(false, ['deleted_at']);
    }
} 