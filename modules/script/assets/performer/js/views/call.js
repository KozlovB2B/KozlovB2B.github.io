/**
 * Представление
 *
 * @param {{}} config
 * @constructor
 */
var CallView = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
CallView.prototype = Object.create(YiijBaseModel.prototype);
CallView.prototype.constructor = CallView;
