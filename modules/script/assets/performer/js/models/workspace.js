/**
 * Рабочая область прогонщика
 *
 * @param {{}} config
 * @constructor
 */
var Workspace = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Состояние рабочей области "Начало"
 * @type {number}
 */
Workspace.STATE_START = 1;

/**
 * Состояние рабочей области "В процессе звонка"
 * @type {number}
 */
Workspace.STATE_CALL = 2;

/**
 * Состояние рабочей области "В конце звонка"
 * @type {number}
 */
Workspace.STATE_END = 3;

/**
 * Состояние рабочей области "Ошибка"
 * @type {number}
 */
Workspace.STATE_ERROR = 4;


/**
 * Состояние рабочей области "Загрузка"
 * @type {number}
 */
Workspace.STATE_MESSAGE = 5;

/**
 * Состояние рабочей области "Скрыть"
 * @type {number}
 */
Workspace.STATE_HIDDEN = 6;


/**
 * Extends
 * @type {YiijBaseModel}
 */
Workspace.prototype = Object.create(YiijBaseModel.prototype);
Workspace.prototype.constructor = Workspace;

Workspace.prototype.state = Workspace.STATE_HIDDEN;

/**
 * Сообщение об ошибке
 * @type {string}
 */
Workspace.prototype.error;

/**
 *
 */
Workspace.prototype.message;

/**
 * Номер звонка
 * @type {string}
 */
Workspace.prototype.call_id = '';


/**
 * Название текущего скрипта
 * @type {string}
 */
Workspace.prototype.current_script_name = '';

/**
 * Название текущего скрипта
 * @type {string}
 */
Workspace.prototype.start_node_text = '';

/**
 * Содержание текущего узла
 * @type {string}
 */
Workspace.prototype.current_node_text = '';

/**
 * Содержание текущего узла
 * @type {string}
 */
Workspace.prototype.current_node_variants = '';
Workspace.prototype.current_group_variants = '';
Workspace.prototype.functions_buttons = '';
Workspace.prototype.performer_options = {
    "node_font_size": "medium",
    "variants_position": "bottom",
    "group_variants_position": "right",
    "variants_style": "buttons",
    "group_variants_style": "links",
    "variants_size": "medium",
    "group_variants_size": "medium"
};