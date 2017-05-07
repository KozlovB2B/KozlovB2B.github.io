<?php

namespace app\modules\script\components\editorCommand;


use Yii;
use yii\base\InvalidParamException;

/**
 * Class CommandFactory
 * @package app\modules\script\components\editorCommand
 */
class CommandFactory
{
    /**
     * @param array $data Данные для конструктора
     * @return Command
     * @throws \yii\base\InvalidConfigException
     */
    public static function getInstance(array $data)
    {
        if (empty($data['model_class'])) {
            throw new InvalidParamException('Для создания команды необходимо наличие ключа model_class');
        }

        if (empty($data['model_id'])) {
            throw new InvalidParamException('Для создания команды необходимо наличие ключа model_id');
        }

        $data['class'] = Command::className();

        /** @var Command $instance */
        return Yii::createObject($data);
    }
}