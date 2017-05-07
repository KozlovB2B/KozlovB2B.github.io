/**
 * Вариант ответа из группы
 *
 * @param {{}} config
 * @constructor
 */
var GroupVariant = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
GroupVariant.prototype = Object.create(YiijBaseModel.prototype);
GroupVariant.prototype.constructor = GroupVariant;

/**
 * @type {int} ID группы
 */
GroupVariant.prototype.id;

/**
 * @type {string} ID группы, к которой относится вариант
 */
GroupVariant.prototype.group_id;

/**
 * @type {float} ID скрипта, к которому относится вариант
 */
GroupVariant.prototype.script_id;

/**
 * @type {float} На какой узел указывает вариант
 */
GroupVariant.prototype.target_id;

/**
 * @type {int} Метка удаления  (UNIX timestamp)
 */
GroupVariant.prototype.deleted_at;

/**
 * @type {int} Метка создания  (UNIX timestamp)
 */
GroupVariant.prototype.created_at;