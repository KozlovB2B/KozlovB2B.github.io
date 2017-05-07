/**
 * Модуль координатор работы команды

 * @param config
 * @constructor
 */
var CoordinatorEvent = function (config) {
    YiijBaseEvent.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseEvent}
 */
CoordinatorEvent.prototype = Object.create(YiijBaseEvent.prototype);
CoordinatorEvent.prototype.constructor = CoordinatorEvent;