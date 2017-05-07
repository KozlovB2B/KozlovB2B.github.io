/**
 * Base object
 *
 * @param {{}} config
 * @constructor
 */
var YiijBaseObject = function (config) {

    Yiij.configure(this, config);

    this.checkAbstracts();
    this.checkRequiredConfig();
    this.init();
};

/**
 * Init
 */
YiijBaseObject.prototype.checkRequiredConfig = function () {
    var properties = this.requiredConfig();

    for (var i in properties) {
        if (typeof this[properties[i]] === 'undefined' || this[properties[i]] === null || this[properties[i]] === '') {
            throw new Error('Для конструирования объекта ' + this.shortName() + ' в конфиге обязательно наличие ключа ' + properties[i]);
        }
    }
};

/**
 * Init
 */
YiijBaseObject.prototype.checkAbstracts = function () {
    var abstracts = this.abstracts();

    if (abstracts) {
        for (var i in abstracts) {
            if (typeof this[abstracts[i]] !== 'function') {
                throw new Error('Объект ' + this.shortName() + ' должен иметь реализацию метода ' + abstracts[i]);
            }
        }
    }
};

/**
 * Required constructor config elements
 *
 * @returns {[]}
 */
YiijBaseObject.prototype.requiredConfig = function () {
    return [];
};

/**
 * Abstract methods list of an object
 *
 * @returns {[]}
 */
YiijBaseObject.prototype.abstracts = function () {
    return [];
};

/**
 * Short name of a class
 *
 * @returns {*}
 */
YiijBaseObject.prototype.shortName = function () {
    return this.constructor.name;
};

/**
 * Init
 */
YiijBaseObject.prototype.init = function () {
};