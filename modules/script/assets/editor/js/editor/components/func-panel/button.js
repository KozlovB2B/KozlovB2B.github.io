/**
 * Фабрика действий
 *
 * @constructor
 * @extends YiijBaseObject
 */
var Button = function () {
    YiijBaseObject.apply(this, []);
};


/**
 * Extends
 * @type {YiijBaseObject}
 */
Button.prototype = Object.create(YiijBaseObject.prototype);
Button.prototype.constructor = Button;

/**
 *
 * @type {boolean}
 */
Button.prototype.disabled = false;
Button.prototype.text = '';
Button.prototype.title = '';
Button.prototype.right = false;

Button.prototype.idPrefix = 'editor___function__';
Button.prototype.afterRegister = function(){};

/**
 * @inheritdoc
 */
Button.prototype.abstracts = function () {
    return ['getId', 'getIconClass', 'perform'];
};

/**
 * @returns {*|jQuery|HTMLElement}
 */
Button.prototype.getElem = function () {

    if (!this.elem) {
        this.elem = $('#' + this.idPrefix + this.getId());
    }

    return this.elem;
};


/**
 * @returns {*|jQuery|HTMLElement}
 */
Button.prototype.disable = function () {
    this.disabled = true;
    this.getElem().addClass('disabled')
};

/**
 * @returns {*|jQuery|HTMLElement}
 */
Button.prototype.enable = function () {
    this.disabled = false;
    this.getElem().removeClass('disabled')
};

/**
 * Html button
 * @returns {string}
 */
Button.prototype.getHtml = function () {
    return ' <div title="'+this.title+'" class="func-button '  + (this.text ? 'with-text' : '') + (this.disabled ? 'disabled' : '') + (this.right ? 'pull-right' : '') +'" id="' + this.idPrefix + this.getId() + '" ><i class="glyphicon ' + this.getIconClass() + '"></i><span class="func-button-text">'+this.text+'</span></div>';
};