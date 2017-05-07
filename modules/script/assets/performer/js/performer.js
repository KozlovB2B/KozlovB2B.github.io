/**
 * Модуль прогонщик скриптов
 *
 * @param id
 * @param parent
 * @param config
 * @constructor
 */
var Performer = function (id, parent, config) {
    YiijBaseModule.apply(this, [id, parent, config]);
};

/**
 * Extends
 * @type {YiijBaseObject}
 */
Performer.prototype = Object.create(YiijBaseModule.prototype);
Performer.prototype.constructor = Performer;


/**
 * @type {WorkspaceController}
 */
Performer.prototype.workspaceController;

/**
 * @type {CallController}
 */
Performer.prototype.callController;

/**
 * @type {CallController}
 */
Performer.prototype.perform_page = null;

/**
 * @type {string}
 */
Performer.prototype.account = '';

/**
 * Инициализация прогонщика
 */
Performer.prototype.start = function () {
    this.recorder = new Recorder(this.recorder);
    this.workspaceController = new WorkspaceController();
    this.callController = new CallController();
};