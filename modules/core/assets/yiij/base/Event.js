/**
 * Base component
 *
 * @param config
 * @constructor
 * @extends YiijBaseObject
 */
var YiijBaseEvent = function (config) {
    YiijBaseObject.apply(this, [config]);
};

/**
 * Extend code
 * @type {YiijBaseObject}
 */
YiijBaseEvent.prototype = Object.create(YiijBaseObject.prototype);
YiijBaseEvent.prototype.constructor = YiijBaseEvent;

/**
 * @type {string}
 */
YiijBaseEvent.prototype.name;

/**
 * @type {bool}
 */
YiijBaseEvent.prototype.handled = false;

/**
 * @type {*}
 */
YiijBaseEvent.prototype.data;
