var GroupSelects = {};

/**
 * Список выбора узлов
 *
 * @param {string} id
 * @constructor
 */
var GroupSelect = function (id) {

    YiijBaseComponent.apply(this, [{'id' : id}]);
};

/**
 * Extends
 * @type {YiijBaseComponent}
 */
GroupSelect.prototype = Object.create(YiijBaseComponent.prototype);
GroupSelect.prototype.constructor = GroupSelect;

/**
 * @type {string}
 */
GroupSelect.prototype.id = '';

/**
 * @type {*|jQuery|HTMLElement}
 */
GroupSelect.prototype.elem;
GroupSelect.prototype.selectize = {};

/**
 * @type {{}}
 */
GroupSelect.prototype.init = function(){


    this.elem = $('#' + this.id);
    var value = this.elem.val();

    this.selectize = this.elem[0].selectize;

    if (this.selectize) {
        this.selectize.destroy();
    }

    this.elem.selectize({
        delimiter: ',',
        //persist: true,
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        options: Yiij.app.getModule('editor').groupController.groups_list,
        render: {
            item: function (item, escape) {
                return '<div>' + item.name + '</div>';
            },
            option: function (item, escape) {
                return '<div>' + item.name + '</div>'

            }
        }
    });

    this.selectize = this.elem[0].selectize;

    if (this.selectize) {
        this.selectize.setValue(value);
    }

    GroupSelects[this.id] = this;
};