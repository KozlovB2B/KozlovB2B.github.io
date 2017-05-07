/**
 * @constructor
 */
var ZoomIn = function () {
    Button.apply(this, []);
};

/**
 * Extends
 * @type {Button}
 */
ZoomIn.prototype = Object.create(Button.prototype);
ZoomIn.prototype.constructor = ZoomIn;


ZoomIn.prototype.title = 'Приблизить';
ZoomIn.prototype.zoom = true;

/**
 * @inheritdoc
 */
ZoomIn.prototype.getId = function () {
    return 'zoom_in';
};

/**
 * @inheritdoc
 */
ZoomIn.prototype.getIconClass = function () {
    return 'glyphicon-plus';
};

/**
 * @inheritdoc
 */
ZoomIn.prototype.perform = function () {
    Yiij.app.getModule('editor').culmann.zoom(true);
};
