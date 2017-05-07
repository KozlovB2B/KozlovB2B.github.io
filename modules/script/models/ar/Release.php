<?php

namespace app\modules\script\models\ar;

use app\modules\script\components\ScriptConverter;
use Yii;
use app\modules\script\models\query\ReleaseQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Схема работы с релизами.
 *
 * По-умолчанию ручное создание релизов у пользователя отключено.
 * Операторам доступны все скрипты а релизы создаются в автоматическом режиме по следующему алгоритму:
 * При загрузке данных для звонка скрипт сравнивает md5 хеши последнего релиза и билда скрипта.
 * Если нет последнего релиза или хеши не совпадают - создается новый релиз и данные релиза отдаются пользователю.
 *
 * Если включено ручное создание релизов - при запросе данных для звонка просто отдается последний релиз,
 * а операторы имеют доступ только к опубликованным скриптам.
 *
 *
 * @property integer $id
 * @property integer $script_id
 * @property string $name
 * @property string $version
 * @property string $build
 * @property integer $created_at
 * @property integer $deleted_at
 * @property string $build_md5
 *
 * @property Script $script
 */
class Release extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_release';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {

        // Очищаем поле последнего релиза у скрипта при удалении
        $this->on(static::EVENT_AFTER_UPDATE, function () {
            if ($this->deleted_at && $this->script->latest_release) {
                $this->script->latest_release = null;
                $this->script->update(false, ['latest_release']);
            }
        });

        // Записываем в последний релиз скрипта свой ID при создании релиза
        $this->on(static::EVENT_AFTER_INSERT, function () {
            $this->script->latest_release = $this->id;
            $this->script->update(false, ['latest_release']);
        });

        parent::init();
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['script_id', 'build', 'version'], 'required'],
            [['script_id', 'created_at', 'deleted_at'], 'integer'],
            [['build'], 'string'],
            [['name', 'build_md5'], 'string', 'max' => 64],
            [['version'], 'string', 'max' => 20],
            [['version'], 'unique', 'targetAttribute' => ['version', 'script_id'], 'message' => 'Для этого скрипта у вас уже есть публикация с такой же версией.'],
            [['script_id'], 'exist', 'skipOnError' => true, 'targetClass' => Script::className(), 'targetAttribute' => ['script_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'script_id' => 'Скрипт',
            'name' => 'Название',
            'version' => 'Версия',
            'build' => 'Build',
            'created_at' => 'Дата',
        ];
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
     * @return ReleaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReleaseQuery(get_called_class());
    }

    /**
     * @param Script $script
     * @return Release
     * @throws \yii\base\Exception
     */
    public static function autoCreate(Script $script)
    {
        $model = new Release();
        $model->script_id = $script->id;

        if (!$script->v2converted) {
            ScriptConverter::convert($script->id);
            $script->refresh();
        }

        $model->build = $script->getBuild();
        $model->build_md5 = $script->build_md5;
        $model->version = substr($model->build_md5, 0, 8);
        $model->save(false);

        return $model;
    }
}
