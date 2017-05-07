/**
 * Узел
 *
 * @param data
 * @constructor
 */
var Variant = function (data) {

    var v = this;

    /**
     * @type {int} ID варианта
     */
    v.id = null;

    /**
     * @type {int} ID узла, к которому относится вариант
     */
    v.node_id = null;

    /**
     * @type {int} ID узла, к которому относится вариант
     */
    v.script_id = null;

    /**
     * @type {string} Содержимое варианта в формате plain text
     */
    v.content = null;

    /**
     * @type {int} К какому узлу привязан вариант
     */
    v.target_id = null;

    /**
     * @type {int} Был ли удален вариант и когда (UNIX timestamp)
     */
    v.deleted_at = null;

    /**
     * @type {int} Когда был создан вариант (UNIX timestamp)
     */
    v.created_at = null;

    $.extend(v, data);

    v.init();
};

/**
 * Инициализация объекта варианта
 */
Variant.prototype.init = function () {
    var v = this;

    checkRequired(v, ['id']);
};

/**
 * Отображение варианта на странице
 */
Variant.prototype.render = function () {
    var v = this;

    Yiij.trace('Рисую вариант ' + v.id);
};

/**
 * Устанавливает цель варианта (на какой узел он ведет)
 *
 * @param id
 */
Variant.prototype.setTarget = function (id) {
    this.target_id = id;
};