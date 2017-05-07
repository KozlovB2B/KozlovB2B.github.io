/**
 * Yii is a helper class serving common framework features.
 *
 * @constructor
 */
var Yiij = {};

/**
 * @type {{}}
 */
Yiij.classmap = {};

/**
 * YiijBaseApplication
 * @type {YiijBaseApplication}
 */
Yiij.app = {};

/**
 * @returns {string} Current version
 */
Yiij.getVersion = function () {
    return '0.0.1';
};

/**
 * Configures an object with the initial property values.
 * @param {{}} object the object to be configured
 * @param {[]} config the property initial values given in terms of name-value pairs.
 * @return {{}} the object itself
 */
Yiij.configure = function (object, config) {
    if (config instanceof Object) {
        for (var i in config) {
            object[i] = config[i];
        }
    }
};

/**
 * Trace
 */
//Yiij.trace = function(txt){console.log(txt)};
Yiij.trace = console.log;
Yiij.trace = function(){};