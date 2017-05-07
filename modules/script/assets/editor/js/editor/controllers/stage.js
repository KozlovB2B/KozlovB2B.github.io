/**
 * Контроллер группы
 *
 * @param config
 * @constructor
 */
var StageController = function (config) {
    YiijBaseController.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
StageController.prototype = Object.create(YiijBaseController.prototype);
StageController.prototype.constructor = StageController;

/**
 * @type {{}}
 */
StageController.prototype.stages;

/**
 * @inheritdoc
 */
StageController.prototype.requiredConfig = function () {
    return ['stages'];
};