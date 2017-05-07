/**
 * Представление
 *
 * @param {{}} config
 * @constructor
 */
var WorkspaceView = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
WorkspaceView.prototype = Object.create(YiijBaseModel.prototype);
WorkspaceView.prototype.constructor = WorkspaceView;

/**
 * @type {string}
 */
WorkspaceView.prototype.template;

/**
 * @type {string}
 */
WorkspaceView.prototype.id = 'performer___workspace';

/**
 * Получить DOM элемент группы по ID
 *
 * @returns {*|jQuery|HTMLElement}
 */
WorkspaceView.prototype.elem = function () {
    var workspace = $("#" + this.id);

    return workspace.length ? workspace : false;
};


/**
 * Создания DOM элемента на основе данных группы
 *
 * @returns {*|jQuery|HTMLElement}
 */
WorkspaceView.prototype.create = function (workspace, callback) {
    var wv = this;
    Yiij.trace('Создание DOM элемента рабочей области. Загружаю шаблон...');

    $.ajax({
        'type': 'get',
        'async': true,
        'url': '/performer/views/workspace/template.html?v=2'
    }).done(function (data) {
        wv.template = data;

        $('body').append($(wv.template).attr('id', wv.id).hide());

        callback(wv.elem(), workspace);
    });
};


/**
 * @param {Workspace} workspace
 */
WorkspaceView.prototype.applyChanges = function (workspace) {

    var elem = this.elem();

    var callback = function (elem, workspace) {

        elem.find('.performer-workspace-state').hide();

        switch (workspace.state) {
            case Workspace.STATE_HIDDEN:
                Yiij.trace('Скрываю рабочую область прогонщика');
                elem.hide();
                return;
            case Workspace.STATE_START:
                Yiij.trace('Отображаю состояние: Начало');
                elem.is(':hidden') ? elem.show() : null;
                elem.find('.performer-start-screen').show();
                break;
            case Workspace.STATE_CALL:
                Yiij.trace('Отображаю состояние: В процессе звонка');
                elem.is(':hidden') ? elem.show() : null;
                elem.find('.performer-call-screen').show();
                break;
            case Workspace.STATE_END:
                Yiij.trace('Отображаю состояние: Конец');
                elem.is(':hidden') ? elem.show() : null;
                elem.find('.performer-end-screen').show();
                break;
            case Workspace.STATE_ERROR:
                Yiij.trace('Отображаю состояние: Ошибка');
                elem.is(':hidden') ? elem.show() : null;
                elem.find('.performer-state-error').show().html(workspace.error);
                break;
            case Workspace.STATE_MESSAGE:
                Yiij.trace('Отображаю состояние: Загрузка');
                elem.is(':hidden') ? elem.show() : null;
                elem.find('.performer-state-loading').show();
                break;
            default :
                throw new Error('Неизвестное состояние рабочей области!');
                break;
        }

        if(typeof workspace.performer_options.group_variants_position == 'undefined'){
            workspace.performer_options.group_variants_position = 'right';
        }

        elem.attr('data-variants-position', workspace.performer_options.variants_position);
        elem.attr('data-group-variants-position', workspace.performer_options.group_variants_position);
        elem.attr('data-variants-style', workspace.performer_options.variants_style);
        elem.attr('data-group-variants-style', workspace.performer_options.group_variants_style);


        var script_name = $('#performer___current_script_name');

        var script_name_html = '';

        if (workspace.call_id) {
            script_name_html += '#' + workspace.call_id + ': ';
        }

        if (workspace.current_script_name) {
            script_name_html += workspace.current_script_name;
        }

        if (script_name.html() != script_name_html) {
            Yiij.trace('Отображаю название скрипта: ' + script_name_html);
            script_name.html(script_name_html);
        }


        var start_screen = $('#performer___start_node_text');

        if (start_screen.html() != workspace.start_node_text) {
            start_screen.html(workspace.start_node_text);
        }


        var current_node_text = $('#performer___current_node_text');

        if (!workspace.performer_options.node_font_size) {
            workspace.performer_options.node_font_size = 'medium';
        }

        current_node_text.attr("data-font-size", workspace.performer_options.node_font_size);

        if (current_node_text.html() != workspace.current_node_text) {
            current_node_text.html(workspace.current_node_text);
        }


        $('.performer-variants-position').hide();
        var current_node_variants_container = $('#performer___variants_' + workspace.performer_options.variants_position);
        var current_group_variants_container = $('#performer___variants_' + workspace.performer_options.group_variants_position);
        current_node_variants_container.html('').show();
        current_group_variants_container.html('').show();

        current_node_variants_container.append('<div class="node-variants-wrapper">' + workspace.current_node_variants + '</div>');
        current_group_variants_container.append('<div class="group-variants-wrapper">' + workspace.current_group_variants + '</div>');

        var total_width = 100;
        if($('#performer___variants_left').is(':visible')){
            total_width -=32;
            $('#performer___variants_left').css('width', '32%');
        }
        if($('#performer___variants_right').is(':visible')){
            total_width -=32;
            $('#performer___variants_right').css('width', '32%');
        }

        $('#performer___current_node_text').css('width', total_width+'%');

        var functions_buttons = $('#performer___functions');

        if (functions_buttons.html() != workspace.functions_buttons) {
            functions_buttons.html(workspace.functions_buttons);
        }

        var message = $('#performer___message');

        if (message.html() != workspace.message) {
            message.html(workspace.message);
        }
    };

    if (elem) {
        callback(elem, workspace);
    } else {
        this.create(workspace, callback);
    }

    Yiij.app.getModule("context").renderFields();
};