/**
 * Исполнитель команд редактора
 *
 * @param config
 * @constructor
 */
var CommandInvoker = {};

/**
 * Сессии с которыми работает инвокер
 * @type {{}}
 */
CommandInvoker.sessions = {};

/**
 * Можно ли отменить последнюю команду в переданной сессии
 *
 * @param session
 * @returns {boolean}
 */
CommandInvoker.canUndo = function (session) {
    return !!session.undo_stack.length;
};

/**
 * Можно ли выполнить заново последнюю отмену в переданной сессии
 *
 * @param session
 * @returns {boolean}
 */
CommandInvoker.canRedo = function (session) {
    return !!session.redo_stack.length;
};

/**
 * Загружает сессию в общий список сесси если ее там нет
 *
 * @param {EditorSession} session
 * @throws Error
 */
CommandInvoker.loadSession = function (session) {
    if (!CommandInvoker.sessions[session.id]) {
        CommandInvoker.sessions[session.id] = session;
    }

    return CommandInvoker.sessions[session.id];
};

/**
 * Создает команду и выполняет ее
 *
 * @param {EditorSession} session Сессия от имени которой выполняется команда
 * @param {Command} command Команда для добавления
 * @param {boolean} send Послылать команду на сервер или нет
 * @throws Error
 */
CommandInvoker.create = function (session, command, send) {

    var s = CommandInvoker.loadSession(session);

    s.redo_stack = [];
    s.undo_stack.push(command);

    this.invoke('create', s, command, send);
};

/**
 * Выполняет откат команды
 *
 * @param {EditorSession} session Сессия от имени которой выполняется команда
 * @param {string} id Ид команды, которую имел ввиду клиент, когда посылал запрос.
 * Если то что имел ввиду клиент не совпадает с тем, что есть на сервере - генерируется Error
 * @param {boolean} send Послылать команду на сервер или нет
 * @throws Error
 */
CommandInvoker.undo = function (session, id, send) {

    var s = CommandInvoker.loadSession(session);

    if (!CommandInvoker.canUndo(s)) {
        return false;
    }

    var command = s.undo_stack.pop();

    s.redo_stack.push(command);

    this.checkCommand(command, id);

    this.invoke('undo', s, command, send);
};


/**
 * Повторно выполняет команду
 *
 * @param {EditorSession} session Сессия от имени которой выполняется команда
 * @param {string} id Ид команды, которую имел ввиду клиент, когда посылал запрос.
 * Если то что имел ввиду клиент не совпадает с тем, что есть на сервере - генерируется Error
 * @param {boolean} send Послылать команду на сервер или нет
 * @throws Error
 */
CommandInvoker.redo = function (session, id, send) {

    var s = CommandInvoker.loadSession(session);

    if (!CommandInvoker.canRedo(s)) {
        return false;
    }

    var command = s.redo_stack.pop();

    s.undo_stack.push(command);

    this.checkCommand(command, id);

    this.invoke('redo', s, command, send);
};

/**
 * Проверяет все ли в порядке с командой
 *
 * @param {Command} command
 * @param id
 */
CommandInvoker.checkCommand = function (command, id) {
    if (command.id != id) {
        throw new Error('Команда ' + id + ' не прошла верификацию: верхний элемент стека имеет ID ' + command.id);
    }
};

/**
 * Производит выполнение команды
 *
 * @param {string} type
 * @param {EditorSession} session
 * @param {Command} command
 * @param {boolean} send Послылать команду на сервер или нет
 * @throws Error
 * @throws \yii\db\Error
 */
CommandInvoker.invoke = function (type, session, command, send) {
    switch (type) {
        case 'create':
        case 'redo':
            if (!command.perform())
                throw new Error('Не удалось выполнить команду!');
            break;
        case 'undo':
            if (!command.rollback())
                throw new Error('Не удалось откатить команду!');
            break;
        default:
            throw new Error('Неизвестный параметр type: ' + type);
            break;
    }


    switch (command.model_class) {
        case 'Node':

            var node = Yiij.app.getModule('editor').nodeController.get(command.model_id);
            Yiij.app.getModule('editor').nodeController.refreshNodeSelects();
            Yiij.app.getModule('editor').relationsManager.showHide(node.id, !!node.deleted_at);
            Yiij.app.getModule('editor').nodeController.repaintNodeRelations(command.model_id);

            break;
        case 'Variant':
            Yiij.app.getModule('editor').nodeController.repaintNodeRelations(Yiij.app.getModule('editor').variantController.get(command.model_id).node_id);
            break;
        case 'Group':
            var group = Yiij.app.getModule('editor').groupController.get(command.model_id);
            Yiij.app.getModule('editor').relationsManager.showHide(group.id, !!group.deleted_at);
            Yiij.app.getModule('editor').groupController.repaintGroupRelations(command.model_id);

            break;
        case 'GroupVariant':
            Yiij.app.getModule('editor').groupController.repaintGroupRelations(Yiij.app.getModule('editor').groupvariantController.get(command.model_id).group_id);
            break;
        default:
            break;
    }

    if (send) {
        switch (type) {
            case 'create':

                RequestQueue.add(new Request({
                    'method': "POST",
                    'url': "/script/editor/" + type + "?session_id=" + session.id,
                    'data': {'create': command}
                }));

                break;
            case 'redo':
            case 'undo':

                RequestQueue.add(new Request({
                    'method': "POST",
                    'url': "/script/editor/" + type + "?session_id=" + session.id + '&command_id=' + command.id
                }));

                break;
            default:
                throw new Error('Неизвестный параметр type: ' + type);
                break;
        }
    }
};
