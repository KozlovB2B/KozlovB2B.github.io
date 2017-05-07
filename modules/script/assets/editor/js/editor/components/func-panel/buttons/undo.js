/**
 * @constructor
 */
var Undo = function () {
    Button.apply(this, []);

    this.disabled = true;
};

/**
 * Extends
 * @type {Button}
 */
Undo.prototype = Object.create(Button.prototype);
Undo.prototype.constructor = Undo;



Undo.prototype.disabled = true;
Undo.prototype.title = 'Отменить';

/**
 * @inheritdoc
 */
Undo.prototype.getId = function () {
    return 'undo';
};

/**
 * @inheritdoc
 */
Undo.prototype.getIconClass = function () {
    return 'glyphicon-chevron-left';
};

/**
 * @inheritdoc
 */
Undo.prototype.perform = function () {

    Yiij.app.getModule('editor').undo();

    if (!Yiij.app.getModule('editor').canUndo()) {
        this.disable();
    }
};
