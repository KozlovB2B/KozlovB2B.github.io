<?php

namespace app\modules\script\models\ar;

use app\modules\script\models\query\EditorSessionQuery;
use app\modules\core\helpers\UUID;
use app\modules\user\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * Работа со скриптом, изменение данных скрипта, реализация функционала undo/redo c учетом что документ могут
 * одновременно редактировать разные пользователи или даже один и тот же пользователь из разных окон браузера или вкладок.
 *
 * Каждый раз, когда пользователь открывает редактирование скрипта - создается сессия:
 * id - уникальный идентификатор сессии
 * user_id - кто открыл сессию
 * script_id - какой скрипт редактируется
 * undo_stack - стек команд, доступных для отката
 * redo_stack - стек команд, доступных для повторного выполнения
 * created_at - дата открытия сессии
 *
 * Даже если пользователь перезагрузил страницу редактирования - все равно открывается новая сессия.
 *
 * Любое изменение в моделях скрипта будь то на сервере или на клиенте делается через комманды.
 * Каждая команда принадлежит сессии и всегда имеет:
 * id - уникальный ID команды
 * class - тип команды (Имя класса, который конкретно реализует работу с данными скрипта)
 * applied_at - время с точностью до микросекунды, когда команда была выполнена
 * cancelled_at - время с точностью до микросекунды, когда команда была выполнена отменена
 *
 * Команды могут быть разных типов и в зависимости от типа они реализуют то или иное действие над данными скрипта.
 * Каждая реализация команды (type) имеет методы execute и rollback, которые непосредственно выполняют действия с данными скрипта.
 * На сервере execute и rollback оборачиваются в транзакцию и отменяются откатом траназкии если произошла ошибка.
 * Если не удалось выполнить код execute или rollback - сервер сигнализирует клиенту что команда не выполнена.
 * Любая ошибка сервера воспринимается как форс-мажор и дальнейшие действия со скриптом блокируются сообщением об ошибке.
 *
 * Выполнением команд и манипуляциями со стеками undo/redo занимается объект CommandInvoker
 * Рассмотрим общий принцип работы CommandInvoker:
 * При каждом вызове команды, не важно какой именно, CommandInvoker получает в виде аргумента сессию в которой 2 стека:
 * undo - содержит в себе команды, доступные для отмены
 * redo - содержит в себе команды доступные для повторного выполнения
 *
 * Если стек redo пуст - нельзя повторить никакое действие.
 * Если стек undo пуст - нельзя отменить никакое действие.
 *
 * Когда нужно добавить новую команду - объект очищает стек redo, выполняет команду (execute) и добавляет ее в стек undo
 *
 * Когда нужно отменить предыдущую команду - объект берет верхнюю команду из стека undo - откатывает ее (rollback), и кладет ее в стек redo
 *
 * Когда нужно применить команду повторно - объект берет верхнюю команду из стека redo, выполняет метод execute и кладет ее в стек undo
 *
 * CommandInvoker работает аналогично и на клиенте.
 * Различие у них только в том что серверный рассылает сообщение на клиенты а клиентский посылает сообщение на сервер.
 *
 *
 * Взаимодействие клиентского CommandInvoker с сервером:
 *
 * Так как валидация команд происходит на клиентской стороне - сервер всегда должен принимать данные и выдавать положительный ответ.
 * Любая ошибка сервера воспринимается как форс-мажор и очередь оптравки данных на сервер останавливается, а пользователю показывается сообщение.
 *
 * Очередь отправки данных на сервер работает с объектами запросов к серверу.
 * Объект запроса имеет:
 * url - адрес обращения
 * method - get или post
 * data - данные для передачи
 * onSuccess - анонимная функция, выполняющаяся при успешной передаче данных.
 * onError - анонимная функция, выполняющаяся при неудачной передаче данных.
 *
 * Очередь имеет внутренний массив, в котором хранятся все запросы, ожидающие своей очереди.
 * Имеет метку состояния - работает или остановлена.
 *
 * При любой ошибке запроса - очередь останавливается, ползователю показывается всплывающее сообщение об ошибке.
 * Когда сервер ответил на текущий запрос, отправляется следующий, если очередь включена
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $script_id
 * @property string $undo_stack
 * @property string $redo_stack
 * @property integer $created_at
 */
class EditorSession extends ActiveRecord
{
    public $redo_stack_data = [];
    public $undo_stack_data = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'editor_session';
    }

    /**
     * @inheritdoc
     * @return \app\modules\script\models\query\EditorSessionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EditorSessionQuery(get_called_class());
    }


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->on(static::EVENT_BEFORE_UPDATE, function () {
            $this->serializeData();
        });

        $this->on(static::EVENT_BEFORE_INSERT, function () {
            $this->serializeData();
        });

        $this->on(static::EVENT_AFTER_FIND, function () {
            $this->unSerializeData();
        });

        parent::init();

    }

    /**
     * Сериализация стеков
     */
    public function serializeData()
    {
        if ($this->undo_stack_data && is_array($this->undo_stack_data)) {
            $this->undo_stack = json_encode($this->undo_stack_data);
        }

        if ($this->redo_stack_data && is_array($this->redo_stack_data)) {
            $this->redo_stack = json_encode($this->redo_stack_data);
        }
    }

    /**
     * Восстановление стеков из сериализованных строк
     */
    public function unSerializeData()
    {
        if ($this->undo_stack && is_string($this->undo_stack)) {
            $this->undo_stack_data = json_decode($this->undo_stack, true);
        }

        if ($this->redo_stack && is_string($this->redo_stack)) {
            $this->redo_stack_data = json_decode($this->redo_stack, true);
        }

        if (!$this->undo_stack) {
            $this->undo_stack_data = [];
        }

        if (!$this->redo_stack) {
            $this->redo_stack_data = [];
        }
    }


    /**
     * @param $script_id
     * @return EditorSession
     * @throws \yii\base\InvalidConfigException
     */
    public static function start($script_id)
    {
        /** @var EditorSession $session */
        $session = Yii::createObject([
            'class' => EditorSession::className(),
            'id' => UUID::v4(),
            'user_id' => Yii::$app->getUser()->getId(),
            'username' => Yii::$app->getUser()->getIdentity()->username,
            'script_id' => $script_id,
            'created_at' => time()
        ]);

        $session->save(false);

        return $session;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['user_id', 'script_id', 'created_at'], 'integer'],
            [['id', 'username'], 'string', 'max' => 64],
            [['id'], 'unique'],
            [['script_id'], 'exist', 'skipOnError' => true, 'targetClass' => Script::className(), 'targetAttribute' => ['script_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
}
