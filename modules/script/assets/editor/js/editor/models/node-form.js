/**
 *
 * @param config
 * @constructor
 * @extends {YiijBaseModel}
 */
var NodeForm = function (config) {
    YiijBaseModel.apply(this, [config]);


};

/**
 * Extends
 * @type {YiijBaseModel}
 */
NodeForm.prototype = Object.create(YiijBaseModel.prototype);
NodeForm.prototype.constructor = NodeForm;

/**
 * @inheritdoc
 */
NodeForm.prototype.requiredConfig = function () {
    return ['id'];
};

/**
 * @type {string} ID DOM элемента формы
 */
NodeForm.prototype.id;

/**
 * @type {Node}
 */
NodeForm.prototype.node;

/**
 * @type {Editor}
 */
NodeForm.prototype.wysi_editor;


NodeForm.prototype.hasChanged = 0;
NodeForm.prototype.attributes = [
    'id', 'top', 'left', 'is_goal', 'normal_ending', 'call_stage_id', 'content', 'groups', 'variants_sort_index'
];

/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
NodeForm.prototype.init = function () {
    var form = this;

    /**
     *
     */
    $('body').on('submit', '#' + this.id, function () {
        form.submit();
        return false;
    });

    /**
     *
     */
    $('body').on('click', '#' + this.id + '_submit', function () {

        form.submit();
        return false;
    });

    /**
     *
     */
    $('body').on('change', 'input, select', function () {
        form.hasChanged = 1;
        return true;
    });

    /**
     *
     */
    $('body').on('click', 'a.insert-field', function () {
        //console.log( Base64.decode($(this).attr('data-html')));
        form.wysi_editor.composer.commands.exec("insertHTML", Base64.decode($(this).attr('data-html')) + "&nbsp;");
    });

    new GroupSelect('node-groups');

    this.wysi_editor = new wysihtml5.Editor("node-content", {
        toolbar: this.id + "_content_toolbar",
        stylesheets: "/css/wysi.css",
        parserRules: wysihtml5ParserRules
    });
};
/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
NodeForm.prototype.getElem = function () {
    return $('#' + this.id);
};

/**
 * Загрузка формы из данных узла
 * @param {Node} node
 */
NodeForm.prototype.load = function (node) {


    var elem = this.getElem();

    this.node = node;

    $('#script___node___update_form_modal_node_id_heading').html(this.node.number);

    for (var i = 0; i < this.attributes.length; i++) {
        var attr = this.attributes[i];

        if (attr == 'content') {
            this.wysi_editor.setValue(this.node[attr]);
        } else if (attr == 'groups') {
            GroupSelects['node-groups'].selectize.setValue(this.node[attr] ? this.node[attr].split(',') : null);
        } else {
            var field = elem.find('#node-' + attr);
            if (field.length) {
                if (field.attr('type') == 'checkbox') {
                    var value = parseInt(this.node[attr]);
                    Yiij.trace(attr + ' ' + value);
                    field.prop('checked', value);
                } else {
                    field.val(this.node[attr]);
                }
            }
        }
    }

    Yiij.app.getModule('editor').variantController.variants_sortable_list.load(node.id);
    this.fillPreviousNodesHeader();
    $('#script___variant___create_embed').show();
    $('#script___variant___sortable_list').show();

    this.hasChanged = 0;
};

/**
 *
 * @returns {boolean}
 */
NodeForm.prototype.fillPreviousNodesHeader = function () {
    var map = {};

    var variants = Yiij.app.getModule('editor').variantController.variants;

    for (var i in variants) {

        if (!variants.hasOwnProperty(i)) {
            continue;
        }

        if (variants[i].target_id == this.node.id && variants[i].node_id) {

            var node = Yiij.app.getModule('editor').nodeController.get(variants[i].node_id);

            if (!node.deleted_at && !variants[i].deleted_at) {
                map[variants[i].node_id] = '<a href="#" class="node-edit-button-from-previous" data-id="' + variants[i].node_id + '">#' + node.number + '</a>';
            }
        }
    }

    var links = [];

    for (var l in map) {
        if (!map.hasOwnProperty(l)) {
            continue;
        }

        links.push(map[l]);
    }

    $("#script___node___update_form_modal_previous_nodes_heading").html(links.join(', '));

    return false;
};

/**
 *
 */
NodeForm.prototype.submit = function () {

    var command = {
        'model_class': 'Node',
        'model_id': this.node.id,
        'p': {},
        'r': {}
    };

    var changes = 0;

    var elem = this.getElem();

    for (var i = 0; i < this.attributes.length; i++) {
        var attr = this.attributes[i];
        var value = null;

        if (attr == 'content') {
            value = this.wysi_editor.getValue();

            if (!value) {
                alert('Заполните содержание');
                return;
            }

            if (value != this.node[attr]) {
                command.p[attr] = value;
                command.r[attr] = this.node[attr];
                changes++;
            }
        } else {
            var field = elem.find('#node-' + attr);

            if (field.length) {
                if (field.attr('type') == 'checkbox') {
                    value = +field.prop('checked');
                } else {
                    value = field.val();
                }

                if (value != this.node[attr]) {
                    command.p[attr] = value;
                    command.r[attr] = this.node[attr] ? this.node[attr] : null;
                    changes++;
                }
            }
        }
    }

    this.hasChanged = 0;

    if (changes > 0) {
        Yiij.app.getModule('editor').create(command);
        Yiij.app.getModule('editor').nodeController.refreshNodeSelects();
    }

    this.hide();
};

/**
 *
 */
NodeForm.prototype.show = function () {
    this.getElem().closest('.modal').modal('show');
};

/**
 *
 */
NodeForm.prototype.hide = function () {
    this.getElem().closest('.modal').modal('hide');
};