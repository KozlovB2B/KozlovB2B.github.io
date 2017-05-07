/**
 * Base model
 *
 * @param config
 * @constructor
 * @extends YiijBaseObject
 */
var YiijBaseModel = function (config) {
    YiijBaseObject.apply(this, [config]);
};

/**
 * Extend code
 * @type {YiijBaseObject}
 */
YiijBaseModel.prototype = Object.create(YiijBaseObject.prototype);
YiijBaseModel.prototype.constructor = YiijBaseModel;
