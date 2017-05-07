/**
 *
 * @param config
 * @constructor
 * @extends {YiijBaseModel}
 */
var ScriptForm = function (config) {
    YiijBaseModel.apply(this, [config]);


};

/**
 * Extends
 * @type {YiijBaseModel}
 */
ScriptForm.prototype = Object.create(YiijBaseModel.prototype);
ScriptForm.prototype.constructor = ScriptForm;

/**
 * @inheritdoc
 */
ScriptForm.prototype.requiredConfig = function () {
    return ['id'];
};

/**
 * @type {string} ID DOM элемента формы
 */
ScriptForm.prototype.id;
/**
 * @type {Script}
 */
ScriptForm.prototype.script;

/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
ScriptForm.prototype.init = function () {
    var form = this;

    /**
     *
     */
    $('body').on('submit', '#' + this.id, function () {
        form.submit();
        return false;
    });

    /**
     *
     */
    $('body').on('click', '#' + this.id + '_submit', function () {
        form.submit();
        return false;
    });

};
/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
ScriptForm.prototype.getElem = function () {
    return $('#' + this.id);
};

/**
 * Загрузка формы из данных узла
 * @param {Script} script
 */
ScriptForm.prototype.load = function (script) {

    var elem = this.getElem();

    this.script = script;

    for (var i in this.script) {
        var field = elem.find('#script-' + i);
        field.val(this.script[i]);
    }
};

/**
 *
 */
ScriptForm.prototype.submit = function () {

    var command = {
        'model_class': 'Script',
        'model_id':this.script.id,
        'p': {},
        'r': {}
    };

    var changes = 0;

    var elem = this.getElem();

    for (var i in this.script) {

        var field = elem.find('#script-' + i);

        if (field.length) {

            var value = field.val();

            if (value != this.script[i]) {
                command.p[i] = value;
                command.r[i] = this.script[i];
                changes++;
            }
        }
    }

    if (changes > 0) {
        Yiij.app.getModule('editor').create(command);
    }

    this.hide();
};

/**
 *
 */
ScriptForm.prototype.show = function () {
    this.getElem().closest('.modal').modal('show');
};

/**
 *
 */
ScriptForm.prototype.hide = function () {
    this.getElem().closest('.modal').modal('hide');
};