/**
 *
 * @param config
 * @constructor
 * @extends {YiijBaseModel}
 */
var Script = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
Script.prototype = Object.create(YiijBaseModel.prototype);
Script.prototype.constructor = Script;

/**
 * @type {integer}
 */
Script.prototype.id;

/**
 * @type {string}
 */
Script.prototype.name;

/**
 * @type {string}
 */
Script.prototype.start_node_uuid;

/**
 * @type {string}
 */
Script.prototype.editor_options;