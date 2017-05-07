/**
 * Группа ответов
 *
 * @param {{}} config
 * @constructor
 */
var Group = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
Group.prototype = Object.create(YiijBaseModel.prototype);
Group.prototype.constructor = Group;

/**
 * @type {int} ID группы
 */
Group.prototype.id;

/**
 * @type {string} Название группы
 */
Group.prototype.name;

/**
 * @type {float} Отступ от верхнего края
 */
Group.prototype.top;

/**
 * @type {float} Отступ от левого края
 */
Group.prototype.left;

/**
 * @type {int} Метка удаления  (UNIX timestamp)
 */
Group.prototype.deleted_at;

/**
 * @type {string} Индекс сортировки вариантов
 */
Group.prototype.variants_sort_index = '';