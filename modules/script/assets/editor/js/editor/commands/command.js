/**
 * Любое действие с любым объектом в редакторе
 *
 * @param config
 * @constructor
 * @extends YiijBaseModel
 */
var Command = function (config) {

    YiijBaseModel.apply(this, [config]);

    if (!this.id) {
        this.id = UUID.generate();
    }
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
Command.prototype = Object.create(YiijBaseModel.prototype);
Command.prototype.constructor = Command;

/**
 *
 * @type {string}
 */
Command.prototype.id;

/**
 *
 * @type {string}
 */
Command.prototype.model_class;

/**
 *
 * @type {string}
 */
Command.prototype.model_id;

/**
 *
 * @type {{}}
 */
Command.prototype.r = {};

/**
 *
 * @type {{}}
 */
Command.prototype.p = {};

/**
 *
 * @returns {boolean}
 */
Command.prototype.validate = function () {

    if (!this.r) {
        throw new Error('r missing')
    }
    if (!this.p) {
        throw new Error('p missing')
    }

    return true;
};



/**
 * Применить действие
 */
Command.prototype.perform = function () {
    if (!this.validate()) {
        return false;
    }

    var command = this;


    var controller_id = this.model_class.toLowerCase() + 'Controller';

    var model = Yiij.app.getModule('editor')[controller_id].getOrCreate(command.model_id, command.p);

    Yiij.trace('Внесение изменений в модель ' + this.model_class + ' #' + model.id + '...');

    var changes = 0;

    for (var i in command.p) {
        Yiij.trace('Меняю ' + i + ' с ' + model[i] + ' на ' + command.p[i]);
        model[i] = command.p[i];
        changes++;

    }

    Yiij.trace('Всего изменений внесено: ' + changes);

    Yiij.app.getModule('editor')[controller_id].view.applyChanges(model);

    return true;
};

/**
 * Откатить действие
 */
Command.prototype.rollback = function () {
    if (!this.validate()) {
        return false;
    }

    var command = this;
    var controller_id = this.model_class.toLowerCase() + 'Controller';

    var model = Yiij.app.getModule('editor')[controller_id].getOrCreate(command.model_id, command.p);

    Yiij.trace('Откат изменений в модель ' + this.model_class + ' #' + model.id + '...');

    var changes = 0;

    for (var i in command.r) {
        Yiij.trace('Откатываю ' + i + ' с ' + model[i] + ' на ' + command.r[i]);
        model[i] = command.r[i];
        changes++;

    }

    Yiij.trace('Всего изменений внесено: ' + changes);

    Yiij.app.getModule('editor')[controller_id].view.applyChanges(model);

    return true;
};


