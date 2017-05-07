/**
 * Узел
 *
 * @param data
 * @constructor
 */
var Node = function (config) {

    var n = this;

    /**
     * @type {int} ID узла
     */
    n.id = null;

    /**
     * @type {string} Содержимое узла в формате plain text
     */
    n.content = null;

    /**
     * @type {float} Отступ от верхнего края
     */
    n.top = null;

    /**
     * @type {float} Отступ от левого края
     */
    n.left = null;

    /**
     * @type {int} Этап продаж
     */
    n.call_stage_id = null;

    /**
     * @type {boolean} Является ли этот узел целью
     */
    n.is_goal = 0;

    /**
     * @type {boolean} Возможно ли нормальное завершение звонка на этом узле
     */
    n.normal_ending = 0;

    /**
     * @type {string} Индекс сортировки вариантов
     */
    n.variants_sort_index = '';

    /**
     * @type {int} Был ли удален узел и когда  (UNIX timestamp)
     */
    n.deleted_at = null;

    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
Node.prototype = Object.create(YiijBaseModel.prototype);
Node.prototype.constructor = Node;

/**
 * @type {string} Группы ответов, которые использует узел
 */
Node.prototype.groups;


/**
 * Required constructor config elements
 *
 * @returns {[]}
 */
Node.prototype.requiredConfig = function () {
    return ['id'];
};

/**
 * Возвращает контент узла без html тегов.
 *
 * @returns {string}
 */
Node.prototype.contentStripped = function () {
    return this.content.replace(/&#?[a-z0-9]+;/g, "").replace(/<[^>]+>/gi, '');
};