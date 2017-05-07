var NodeSelects = {};

/**
 * Список выбора узлов
 *
 * @param {string} id
 * @constructor
 */
var NodeSelect = function (id) {
    YiijBaseComponent.apply(this, [{'id' : id}]);
};

/**
 * Extends
 * @type {YiijBaseComponent}
 */
NodeSelect.prototype = Object.create(YiijBaseComponent.prototype);
NodeSelect.prototype.constructor = NodeSelect;

/**
 * @type {string}
 */
NodeSelect.prototype.id = '';
NodeSelect.prototype.elem = {};
NodeSelect.prototype.selectize = {};

/**
 * @type {{}}
 */
NodeSelect.prototype.init = function(){

    this.elem = $('#' + this.id);
    var value = this.elem.val();

    this.selectize = this.elem[0].selectize;

    if (this.selectize) {
        this.selectize.destroy();
    }

    this.elem.selectize({
        valueField: 'id',
        labelField: 'content',
        searchField: ['number', 'content'],
        plugins: ['clear_button'],
        options: Yiij.app.getModule('editor').nodeController.nodes_list,
        render: {
            item: function (item, escape) {
                var node_select_name = item.content.replace('&nbsp;', ' ').replace('&lt;', '<').replace('&gt;', '>').substr(0, 20);

                return '<div>' +
                    '<span class="name selectize_note_id" data-number="' + escape(item.number) + '">#' + escape(item.number) + '</span> ' +
                    '<span class="email">' + escape(node_select_name) + '</span>' +
                    '</div>';
            },
            option: function (item, escape) {
                var node_select_name = item.content.replace('&nbsp;', ' ').replace('&lt;', '<').replace('&gt;', '>').substr(0, 70);

                return '<div>' +
                    '<span class="name selectize_note_id"  data-number="' + escape(item.number) + '">#' + escape(item.number) + '</span> ' +
                    '<span class="email">' + escape(node_select_name) + '</span>' +
                    '</div>';
            }
        }
    });

    this.selectize = this.elem[0].selectize;

    if (this.selectize) {
        this.selectize.setValue(value);
    }

    NodeSelects[this.id] = this;
};