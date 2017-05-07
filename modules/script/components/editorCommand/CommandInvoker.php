<?php

namespace app\modules\script\components\editorCommand;

use app\modules\script\components\WSConnection;
use app\modules\script\models\ar\EditorSession;

use app\modules\script\models\ar\Script;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\base\Exception;

/**
 * Class CommandInvoker
 *
 * Invoker Refresher https://www.youtube.com/watch?v=OG-j445yCHw
 *
 * @package app\modules\script\components\editorCommand
 */
class CommandInvoker extends Component
{
    /**
     * Создает команду и выполняет ее
     *
     * @param EditorSession $session Сессия от имени которой выполняется команда
     * @param array $data Данные для конструирования команды
     * @throws Exception
     */
    public static function create(EditorSession $session, array $data)
    {
        static::checkSession($session);
        $command = CommandFactory::getInstance($data);
        $session->redo_stack_data = [];
        $session->undo_stack_data[] = $command;

        static::invoke('create', $session, $command);
    }


    /**
     * Выполняет откат команды
     *
     * @param EditorSession $session Сессия от имени которой выполняется команда
     * @param string $id Ид команды, которую имел ввиду клиент, когда посылал запрос.
     * Если то что имел ввиду клиент не совпадает с тем, что есть на сервере - генерируется Exception
     * @throws Exception
     */
    public static function undo(EditorSession $session, $id)
    {
        if(!static::canUndo($session)){
            throw new Exception('Нельзя отменить действие в запрашиваемой сессии!');
        }

        static::checkSession($session);
        $command = CommandFactory::getInstance(array_pop($session->undo_stack_data));
        $session->redo_stack_data[] = $command;
        static::checkCommand($command, $id);

        static::invoke('undo', $session, $command);
    }


    /**
     * Повторно выполняет команду
     *
     * @param EditorSession $session Сессия от имени которой выполняется команда
     * @param string $id Ид команды, которую имел ввиду клиент, когда посылал запрос.
     * Если то что имел ввиду клиент не совпадает с тем, что есть на сервере - генерируется Exception
     * @throws Exception
     */
    public static function redo(EditorSession $session, $id)
    {
        if(!static::canRedo($session)){
            throw new Exception('Нельзя отменить действие в запрашиваемой сессии!');
        }

        static::checkSession($session);
        $command = CommandFactory::getInstance(array_pop($session->redo_stack_data));
        $session->undo_stack_data[] = $command;
        static::checkCommand($command, $id);

        static::invoke('redo', $session, $command);
    }

    /**
     * Можно ли отменить последнюю команду в переданной сессии
     *
     * @param EditorSession $session
     * @return bool
     */
    public static function canUndo(EditorSession $session){
        return !!$session->undo_stack_data;
    }

    /**
     * Можно ли выполнить заново последнюю отмену в переданной сессии
     *
     * @param EditorSession $session
     * @return bool
     */
    public static function canRedo(EditorSession $session){
        return !!$session->redo_stack_data;
    }

    /**
     * Проверяет все ли в порядке с сессией
     *
     * @param EditorSession $session
     * @throws Exception
     */
    protected static function checkSession(EditorSession $session)
    {
        if ($session->user_id != Yii::$app->getUser()->getId()) {
            throw new Exception('Нельзя выполнять команды, используя чужую сессию!');
        }
    }

    /**
     * Проверяет все ли в порядке с командой
     *
     * @param Command $command
     * @param $id
     */
    protected static function checkCommand(Command $command, $id)
    {
        if ($command->id != $id) {
            throw new InvalidParamException('Команда ' . $id . ' не прошла верификацию: верхний элемент стека имеет ID ' . $command->id);
        }
    }

    /**
     * Производит выполнение команды
     *
     * @param $type
     * @param EditorSession $session
     * @param Command $command
     * @throws Exception
     * @throws \yii\db\Exception
     */
    protected static function invoke($type, EditorSession $session, Command $command)
    {
        $t = Yii::$app->getDb()->beginTransaction();

        try {

            switch ($type) {
                case 'create':
                case 'redo':
                    if (!$command->perform())
                        throw new Exception($command->getErrorsAsString());
                    break;
                case 'undo':
                    if (!$command->rollback())
                        throw new Exception($command->getErrorsAsString());
                    break;
                default:
                    throw new InvalidParamException('Неизвестный параметр type: ' . $type);
                    break;
            }

            if (!$session->save())
                throw new Exception($session->getFirstErrors());

            $t->commit();

        } catch (\Exception $e) {

            $t->rollBack();

            throw new Exception($e->getMessage());
        }

        Script::flushBuild($session->script_id);

        WSConnection::getClient()->publish(WSConnection::scriptEditingChannelName($session->script_id), [
            'CommandInvoker' => [
                "action" => $type,
                "session" => $session->getAttributes(null, ['redo_stack', 'undo_stack', 'redo_stack_data', 'undo_stack_data']),
                "data" => $command
            ]
        ]);
    }

}