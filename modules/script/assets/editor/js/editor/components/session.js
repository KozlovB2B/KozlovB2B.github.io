/**
 * Базовый объект для исполнителей команд редактора
 *
 * @param config
 * @constructor
 */
var EditorSession = function (config) {
    YiijBaseComponent.apply(this, [config]);

    if(!this.redo_stack){
        this.redo_stack = [];
    }

    if(!this.undo_stack){
        this.undo_stack = [];
    }
};

/**
 * Extends
 * @type {YiijBaseComponent}
 */
EditorSession.prototype = Object.create(YiijBaseComponent.prototype);
EditorSession.prototype.constructor = EditorSession;

/**
 * ID сессии
 *
 * @type {string}
 */
EditorSession.prototype.id;

/**
 * ID сессии
 * @type {string}
 */
EditorSession.prototype.username;

/**
 * Стек действий, доступных для отмены
 * @type {Array}
 */
EditorSession.prototype.undo_stack = [];

/**
 * Стек действий, доступных для повторного выполнения
 * @type {Array}
 */
EditorSession.prototype.redo_stack = [];

