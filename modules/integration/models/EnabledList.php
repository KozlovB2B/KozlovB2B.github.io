<?php

namespace app\modules\integration\models;

use Yii;
use app\modules\user\models\User;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "integration_enabled_list".
 *
 * @property integer $id
 * @property string $list
 */
class EnabledList extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'integration_enabled_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['list'], 'string', 'max' => 16000],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @param $id
     * @return EnabledList|static
     */
    public static function findOrCreate($id)
    {
        $list = EnabledList::findOne($id);

        if (!$list) {
            $list = new EnabledList();
            $list->id = $id;
        }

        return $list;
    }

    /**
     * @return array
     */
    public function getListAsArray()
    {
        return $this->list ? explode(',', $this->list) : [];
    }

    /**
     * @param $module
     * @return bool
     */
    public function isEnabled($module)
    {
        return in_array($module, $this->getListAsArray());
    }

    /**
     * @param $id
     * @param $module
     * @return bool
     */
    public static function enable($id, $module)
    {
        $list = static::findOrCreate($id);

        if ($list->isEnabled($module)) {
            return false;
        }

        $current = $list->getListAsArray();

        $current[] = $module;

        $list->list = implode(',', $current);

        return $list->save(false);
    }

    /**
     * @param $id
     * @param $module
     * @return bool
     */
    public static function disable($id, $module)
    {
        $list = static::findOrCreate($id);

        if (!$list->isEnabled($module)) {
            return false;
        }

        $current = $list->getListAsArray();

        for ($i = 0, $count = count($current); $i < $count; $i++) {
            if ($current[$i] == $module) {
                unset($current[$i]);
                $list->list = implode(',', $current);
                return $list->save(false);
            }
        }

        return false;
    }
}
