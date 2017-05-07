/**
 * Base controller
 *
 * @param config
 * @constructor
 * @extends YiijBaseObject
 */
var YiijBaseController = function (config) {
    YiijBaseObject.apply(this, [config]);
};

/**
 * Extend code
 * @type {YiijBaseObject}
 */
YiijBaseController.prototype = Object.create(YiijBaseObject.prototype);
YiijBaseController.prototype.constructor = YiijBaseController;
