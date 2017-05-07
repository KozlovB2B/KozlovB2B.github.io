/**
 * Base Application
 *
 * @param config
 * @constructor
 * @extends YiijBaseModule
 */
var YiijBaseApplication = function (config) {

    YiijBaseModule.apply(this, ['app', null, config]);

    /**
     * @type {YiijBaseApplication}
     */
    Yiij.app = this;
};

/**
 * Extend code
 * @type {YiijBaseModule}
 */
YiijBaseApplication.prototype = Object.create(YiijBaseModule.prototype);
YiijBaseApplication.prototype.constructor = YiijBaseApplication;

/**
 * @var {{}} list of loaded modules indexed by their class names.
 */
YiijBaseApplication.prototype.loadedModules = {};

/**
 * Runs application
 */
YiijBaseApplication.prototype.run = function () {
    Yiij.trace('Run application...');
};