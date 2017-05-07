/**
 *
 * @param config
 * @constructor
 */
var NodeClone = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
NodeClone.prototype = Object.create(YiijBaseModel.prototype);
NodeClone.prototype.constructor = NodeClone;

/**
 * @type {string}
 */
NodeClone.prototype.id;

/**
 * @type {integer}
 */
NodeClone.prototype.script_id;

/**
 * @type {string}
 */
NodeClone.prototype.from;

/**
 * @type {string}
 */
NodeClone.prototype.to;

/**
 * @type {string}
 */
NodeClone.prototype.to_data;

/**
 * @type {integer}
 */
NodeClone.prototype.created_at;

/**
 * @type {integer}
 */
NodeClone.prototype.deleted_at;