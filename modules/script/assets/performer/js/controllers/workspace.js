/**
 * Контроллер рабочей области
 *
 * @param config
 * @constructor
 */
var WorkspaceController = function (config) {
    YiijBaseController.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
WorkspaceController.prototype = Object.create(YiijBaseController.prototype);
WorkspaceController.prototype.constructor = WorkspaceController;

/**
 * Объект, отвечающий за представление
 *
 * @type {WorkspaceView}
 */
WorkspaceController.prototype.view;

/**
 * @type {Workspace}
 */
WorkspaceController.prototype.workspace;

/**
 * Инициализация контроллера групп
 */
WorkspaceController.prototype.init = function () {
    Yiij.trace('Инициализация контроллера рабочей области прогонщика');

    this.workspace = new Workspace();
    this.view = new WorkspaceView();
};

/**
 * Поазать ошибку
 * @param message
 */
WorkspaceController.prototype.showError = function (message) {
    Yiij.trace('Отображаю ошибку: ' + message);
    this.workspace.error = message;
    this.workspace.state = Workspace.STATE_ERROR;
    this.view.applyChanges(this.workspace);
};

/**
 * Смена состояния рабочей области
 *
 * @param {int} state
 */
WorkspaceController.prototype.stateTo = function (state) {
    if (this.workspace.state !== state) {
        this.workspace.state = state;
        this.view.applyChanges(this.workspace);
    }
};

/**
 * Обновление рабочей области
 */
WorkspaceController.prototype.updateView = function () {
    this.view.applyChanges(this.workspace);
};