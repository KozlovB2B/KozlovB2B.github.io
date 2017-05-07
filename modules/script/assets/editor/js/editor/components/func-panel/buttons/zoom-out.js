/**
 * @constructor
 */
var ZoomOut = function () {
    Button.apply(this, []);
};

/**
 * Extends
 * @type {Button}
 */
ZoomOut.prototype = Object.create(Button.prototype);
ZoomOut.prototype.constructor = ZoomOut;


ZoomOut.prototype.title = 'Отдалить';
ZoomOut.prototype.zoom = true;

/**
 * @outheritdoc
 */
ZoomOut.prototype.getId = function () {
    return 'zoom_out';
};

/**
 * @outheritdoc
 */
ZoomOut.prototype.getIconClass = function () {
    return 'glyphicon-minus';
};

/**
 * @outheritdoc
 */
ZoomOut.prototype.perform = function () {
    Yiij.app.getModule('editor').culmann.zoom(false);
};
