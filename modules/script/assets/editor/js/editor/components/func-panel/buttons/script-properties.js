/**
 * Кнопка показать настройки скрипта
 * @constructor
 */
var ScriptProperties = function () {
    Button.apply(this, []);

    this.disabled = false;
};

/**
 * Extends
 * @type {Button}
 */
ScriptProperties.prototype = Object.create(Button.prototype);
ScriptProperties.prototype.constructor = ScriptProperties;

ScriptProperties.prototype.title = 'Настройки скрипта';

/**
 * @inheritdoc
 */
ScriptProperties.prototype.getId = function () {
    return 'open_script_properties';
};

/**
 * @inheritdoc
 */
ScriptProperties.prototype.getIconClass = function () {
    return 'glyphicon-cog';
};

/**
 *
 */
ScriptProperties.prototype.perform = function () {


};
