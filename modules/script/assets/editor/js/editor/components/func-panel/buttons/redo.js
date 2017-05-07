/**
 * @constructor
 */
var Redo = function () {
    Button.apply(this, []);
};

/**
 * Extends
 * @type {Button}
 */
Redo.prototype = Object.create(Button.prototype);
Redo.prototype.constructor = Redo;


Redo.prototype.disabled = true;
Redo.prototype.title = 'Вернуть назад';

/**
 * @inheritdoc
 */
Redo.prototype.getId = function () {
    return 'redo';
};

/**
 * @inheritdoc
 */
Redo.prototype.getIconClass = function () {
    return 'glyphicon-chevron-right';
};

/**
 * @inheritdoc
 */
Redo.prototype.perform = function () {
    Yiij.app.getModule('editor').redo();

    if(!Yiij.app.getModule('editor').canRedo()){
        this.disable();
    }
};
