/**
 * Фабрика действий
 *
 * @constructor
 * @extends YiijBaseObject
 */
var Panel = function () {
    YiijBaseObject.apply(this, []);
};


/**
 * Extends
 * @type {YiijBaseObject}
 */
Panel.prototype = Object.create(YiijBaseObject.prototype);
Panel.prototype.constructor = Panel;

/**
 * @type {*|jQuery|HTMLElement}
 */
Panel.prototype.elem;

/**
 * @type {*|jQuery|HTMLElement}
 */
Panel.prototype.zoom_elem;

Panel.prototype.buttons = {};

/**
 * Panel div ID
 * @returns {string}
 */
Panel.prototype.getId = function () {
    return 'func-panel';
};

/**
 * Panel div ID
 * @returns {string}
 */
Panel.prototype.getZoomId = function () {
    return 'zoom-panel';
};

/**
 * @returns {*|jQuery|HTMLElement}
 */
Panel.prototype.getElem = function () {

    if (!this.elem) {
        this.elem = $('#' + this.getId());
    }

    return this.elem;
};


/**
 * @returns {*|jQuery|HTMLElement}
 */
Panel.prototype.getZoomElem = function () {

    if (!this.zoom_elem) {
        this.zoom_elem = $('#' + this.getZoomId());
    }

    return this.zoom_elem;
};

/**
 * Регистрирует кнопку на панели
 * @param {Button} button
 * @param {*|jQuery|HTMLElement} elem
 */
Panel.prototype.registerButton = function (button, elem) {

    this.buttons[button.getId()] = button;

    elem.append(button.getHtml());

    button.getElem().on('click', function () {
        if (!button.disabled) {
            button.perform();
        } else {
            Yiij.trace(button);
        }
    });

    var my = button.right ? 'top right' : 'top left';
    var at = button.right ? 'bottom left' : 'bottom right';

    if (button.zoom) {
        my = 'bottom left';
        at = 'top right';
    }

    button.getElem().qtip({
        style: {
            classes: 'qtip-dark qtip-shadow'
        },
        position: {
            my: my,
            at: at
        }
    });

    button.afterRegister();
};

/**
 * Выключает кнопку
 * @param id
 */
Panel.prototype.disableButton = function (id) {
    this.buttons[id].disabled = true;
    this.buttons[id].getElem().addClass('disabled');
};

/**
 * Выключает кнопку
 * @param id
 */
Panel.prototype.enableButton = function (id) {
    this.buttons[id].disabled = false;
    this.buttons[id].getElem().removeClass('disabled');
};

/**
 *
 */
Panel.prototype.init = function () {
    var buttons = [
        CreateNode,
        CreateGroup,
        Undo,
        Redo
    ];

    for (var i = 0; i < buttons.length; i++) {
        this.registerButton(new buttons[i](), this.getElem());
    }

    var search = new Search();
    this.getElem().append(search.getHtml());
    search.initSelect();


    var zoom = [
        ZoomIn,
        ZoomOut,
        ZoomFit
    ];

    for (var z = 0; z < zoom.length; z++) {
        this.registerButton(new zoom[z](), this.getZoomElem());
    }
};
