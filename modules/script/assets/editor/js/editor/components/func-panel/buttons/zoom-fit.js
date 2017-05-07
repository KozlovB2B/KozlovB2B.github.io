/**
 * @constructor
 */
var ZoomFit = function () {
    Button.apply(this, []);
};

/**
 * Extends
 * @type {Button}
 */
ZoomFit.prototype = Object.create(Button.prototype);
ZoomFit.prototype.constructor = ZoomFit;

ZoomFit.prototype.title = 'Показать все узлы на экране';
ZoomFit.prototype.zoom = true;

/**
 * @fitheritdoc
 */
ZoomFit.prototype.getId = function () {
    return 'zoom_fit';
};

/**
 * @fitheritdoc
 */
ZoomFit.prototype.getIconClass = function () {
    return 'glyphicon-th';
};

/**
 * @fitheritdoc
 */
ZoomFit.prototype.perform = function () {
    Yiij.app.getModule('editor').culmann.fit();
};
