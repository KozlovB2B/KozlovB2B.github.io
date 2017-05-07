/**
 * Web Application
 *
 * @param config
 * @constructor
 * @extends YiijBaseApplication
 */
var YiijWebApplication = function (config) {
    YiijBaseApplication.apply(this, [config]);
};

/**
 * Extend code
 * @type {YiijBaseApplication}
 */
YiijWebApplication.prototype = Object.create(YiijBaseApplication.prototype);
YiijWebApplication.prototype.constructor = YiijWebApplication;