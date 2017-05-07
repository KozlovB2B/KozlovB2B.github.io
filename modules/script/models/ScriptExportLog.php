<?php

namespace app\modules\script\models;

use Yii;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use app\modules\user\models\User;
use app\modules\core\components\ExcelExport;
use juffin_halli\dataProviderIterator\DataProviderIterator;
use app\modules\script\models\ar\Script;

/**
 * This is the model class for table "ScriptExportLog".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type_id
 * @property integer $script_id
 * @property integer $source_script_id
 * @property integer $success
 * @property integer $created_at
 * @property string $ip
 * @property string $username
 * @property string $script_name
 *
 *
 * @property Script $script
 * @property User $user
 */
class ScriptExportLog extends ActiveRecord
{
    /**
     * @const int Record type export
     */
    const TYPE_EXPORT = 1;

    /**
     * @const int Record type import
     */
    const TYPE_IMPORT = 2;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScript()
    {
        return $this->hasOne(Script::className(), ['id' => 'script_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ScriptExportLog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'script_id'], 'required'],
            [['type_id'], 'safe'],
            [['user_id', 'script_id', 'success', 'created_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'username' => 'Логин',
            'script_id' => 'Скрипт',
            'script_name' => 'Название скрипта',
            'success' => 'Удачно или нет',
            'created_at' => 'Дата',
            'type_id' => 'Тип операции',
            'source_script_id' => 'Изначальный скрипт',
            'ip' => 'IP',
        ];
    }

    /**
     * Write script export event
     *
     * @param Script $script Script
     * @param bool $success Is successful attempt or not
     * @param int $type Operation type
     * @throws ErrorException
     */
    public static function write(Script $script, $success = true, $type = ScriptExportLog::TYPE_EXPORT)
    {
        $rec = new ScriptExportLog();
        $rec->script_id = $script->id;
        $rec->source_script_id = $script->original_id;
        $rec->type_id = $type;
        $rec->user_id = Yii::$app->getUser()->getId();
        $rec->username = $rec->user ? $rec->user->username : null;
        $rec->script_name = $rec->script ? $rec->script->name : null;
        $rec->success = $success;
        $rec->created_at = time();
        $rec->ip = Yii::$app->getRequest()->getUserIP();
        $rec->save();
    }


    /**
     * As excel
     *
     * @param ActiveDataProvider $data_provider
     * @throws \yii\db\Exception
     */
    public static function asExcel(ActiveDataProvider $data_provider)
    {
        $filename = 'script_export_log';

        $model = new ScriptExportLog();
        $excel = new ExcelExport();
        Yii::$app->getDb()->createCommand("SET NAMES cp1251")->execute();
        $data = new DataProviderIterator($data_provider, 1000);

        $excel->totalCol = 10;
        $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getAttributeLabel('id')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getAttributeLabel('type_id')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getAttributeLabel('user_id')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getAttributeLabel('username')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getAttributeLabel('ip')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getAttributeLabel('script_id')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getAttributeLabel('source_script_id')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getAttributeLabel('script_name')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getAttributeLabel('success')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $model->getAttributeLabel('created_at')));

        $excel->GoNewLine();

        /** @var ScriptExportLog $model */
        foreach ($data as $model) {

            $changed = false;

            if (!$model->username) {
                $model->username = $model->user ? $model->user->username : null;
                $changed = true;
            }

            if (!$model->script_name) {
                $model->script_name = $model->script ? $model->script->name : null;
                $changed = true;
            }

            if (!$model->ip) {
                $model->ip = $model->user ? $model->user->registration_ip : null;
                $changed = true;
            }

            if ($changed) {
                $model->save(false);
            }

            $excel->InsertText($model->id);
            $excel->InsertText($model->type_id == ScriptExportLog::TYPE_IMPORT ? 'import' : 'export');
            $excel->InsertText($model->user_id);
            $excel->InsertText($model->username);
            $excel->InsertText($model->ip);
            $excel->InsertText($model->script_id);
            $excel->InsertText($model->source_script_id);
            $excel->InsertText($model->script_name);
            $excel->InsertText($model->success ? 'success' : 'fail');
            $excel->InsertText(date('d-m-Y H:i:s', $model->created_at));
            $excel->GoNewLine();
        }

        $excel->SaveFile($filename);
    }
}
