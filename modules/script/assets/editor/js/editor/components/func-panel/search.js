/**
 * Фабрика действий
 *
 * @constructor
 * @extends YiijBaseObject
 */
var Search = function () {
    YiijBaseObject.apply(this, []);
};


/**
 * Extends
 * @type {YiijBaseObject}
 */
Search.prototype = Object.create(YiijBaseObject.prototype);
Search.prototype.constructor = Search;

/**
 *
 * @type {boolean}
 */
Search.prototype.disabled = false;
Search.prototype.id = 'editor___function__search';

Search.prototype.afterRegister = function(){};


/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
Search.prototype.initSelect = function () {
   new NodeSelect(this.id);

    NodeSelects[this.id].selectize.on('change', function(value){
        if(!value){
            return;
        }

        var node = Yiij.app.getModule('editor').nodeController.get(value);

        Yiij.app.getModule('editor').culmann.focus(node);
    })
};


/**
 * @returns {*|jQuery|HTMLElement}
 */
Search.prototype.getElem = function () {

    if (!this.elem) {
        this.elem = $('#' + this.id);
    }

    return this.elem;
};


/**
 * @returns {*|jQuery|HTMLElement}
 */
Search.prototype.disable = function () {
    this.disabled = true;
    this.getElem().addClass('disabled')
};

/**
 * @returns {*|jQuery|HTMLElement}
 */
Search.prototype.enable = function () {
    this.disabled = false;
    this.getElem().removeClass('disabled')
};

/**
 * Html button
 * @returns {string}
 */
Search.prototype.getHtml = function () {
    return '<div id="' + this.id + '_wrapper"><select placeholder="Поиск по узлам..." class="' + (this.disabled ? 'disabled' : '') + '" id="' + this.id + '" ></select></div>';
};