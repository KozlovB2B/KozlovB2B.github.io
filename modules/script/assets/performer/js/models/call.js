/**
 * Рабочая область прогонщика
 *
 * @param {{}} config
 * @constructor
 */
var Call = function (config) {
    YiijBaseModel.apply(this, [config]);
};


/**
 * Extends
 * @type {YiijBaseModel}
 */
Call.prototype = Object.create(YiijBaseModel.prototype);
Call.prototype.constructor = Call;

/**
 * @type {int} Id звонка
 */
Call.prototype.id;

/**
 * @type {int} Id скрипта
 */
Call.prototype.duration;

/**
 * @type {int} Id скрипта
 */
Call.prototype.script_id;


/**
 * @type {int} Id релиза
 */
Call.prototype.release_id;

/**
 * @type {string}
 */
Call.prototype.mode;

/**
 * @type {string} Узел завершения звонка
 */
Call.prototype.end_node_uuid;
Call.prototype.end_node_id;

/**
 * @type {string} Узел завершения звонка
 */
Call.prototype.last_word;

/**
 * @type {string} Содержание последнего узла
 */
Call.prototype.end_node_content;

/**
 * @type {int} Этап последнего узла
 */
Call.prototype.end_node_stage;

/**
 * @type {int} Сколько узлов было пройдено
 */
Call.prototype.nodes_passed;

/**
 * @type {boolean} Достигнута ли цель
 */
Call.prototype.is_goal_reached;

/**
 * @type {boolean} Штатное завершение
 */
Call.prototype.normal_ending;

/**
 * @type {string} История звонка
 */
Call.prototype.call_history;

/**
 * @type {string} Из какой страницы был вызван звонок (если использовался виджет)
 */
Call.prototype.perform_page;

/**
 * @type {{}} Из какой страницы был вызван звонок (если использовался виджет)
 */
Call.prototype.dataset;


/**
 * @type {{}} Данные по которым осуществляется звонок
 */
Call.prototype.data;

Call.prototype.back_stack = [];

Call.prototype.history = [];

Call.prototype.visited_nodes = {};

/**
 * Timer
 * @type {Timer}
 */
Call.prototype.timer;

/**
 *
 */
Call.prototype.ready = function () {
    if (!this.data) {
        throw new Error('Отсутсвуют данные!');
    }

    this.back_stack = [];

    this.history = [];

    this.visited_nodes = {};


    if (this.mode != 'test') {
        Yiij.app.getModule('performer').recorder.file = Yiij.app.getModule('performer').account + "/" + UUID.generate();
    }

    this.timer = new Timer({
        'output': $('#performer___timer')
    });

    $('#performer___form').trigger('reset');

    this.clearWorkspace();

    if (!this.start_node_uuid) {
        this.start_node_uuid = this.data['script']['start_node_uuid'];
    }

    if (!this.start_node_uuid) {
        this.destroy();
        this.id = null;

        Yiij.app.getModule('performer').workspaceController.showError('Не указан начальный узел!');

        return;
    }

    Yiij.app.getModule('performer').workspaceController.workspace.current_script_name = this.data['script']['name'];

    if (this.data['script']['performer_options']) {
        Yiij.app.getModule('performer').workspaceController.workspace.performer_options = JSON.parse(this.data['script']['performer_options']);
    }

    Yiij.app.getModule('performer').workspaceController.workspace.start_node_text = this.data['nodes'][this.start_node_uuid]['content'];

    $('#performer___start_button').attr('data-node', this.start_node_uuid).attr('data-variant', 0);

    //var hint = $('#performer___hint');

    Yiij.app.getModule('performer').workspaceController.workspace.call_id = null;

    //if (this.release_id == 'test') {
    //    hint.html("Внимание! Это тестовый звонок. Статистика не будет записана. Чтобы выполнять реальные звонки - опубликуйте скрипт.").show();
    //} else {
    //    hint.html("").hide();
    //}

    Yiij.app.getModule('performer').workspaceController.stateTo(Workspace.STATE_START);


    //Yiij.app.getModule('performer').workspaceController.workspace.setWorkspaceHeight();
};

/**
 *
 */
Call.prototype.to = function (node_id, from) {

    if (!this.timer.interval) {
        this.timer.start();
    }

    this.back_stack.push(node_id);
    this.writeHistory(node_id, from);
    this.loadNode(node_id);

    if (this.data['variants'][from]) {
        this.last_word = this.data['variants'][from]['content'];
    } else if (this.data['group_variants'][from]) {
        this.last_word = this.data['group_variants'][from]['content'];
    } else {
        this.last_word = null;
    }
};

/**
 *
 */
Call.prototype.back = function () {
    var node_id = this.back_stack.pop();

    this.last_word = null;

    if (this.back_stack.length) {
        this.writeHistory(this.back_stack[this.back_stack.length - 1], -1);
        this.loadNode(this.back_stack[this.back_stack.length - 1]);
    }
};

/**
 * Writes history
 *
 * @param node_id integer Node ID
 * @param variant_id integer Edge ID (for history only)
 */
Call.prototype.writeHistory = function (node_id, variant_id) {
    this.history.push({
        n: node_id,
        e: variant_id,
        t: this.timer.elapsed_seconds
    });

    this.visited_nodes[node_id] = true;
};

Call.prototype.clearWorkspace = function () {
    $('#performer___timer').html('');
    Yiij.app.getModule('performer').workspaceController.workspace.start_node_text = '';
    Yiij.app.getModule('performer').workspaceController.workspace.functions_buttons = '';
    Yiij.app.getModule('performer').workspaceController.workspace.current_node_variants = '';
    Yiij.app.getModule('performer').workspaceController.workspace.current_group_variants = '';
};

Call.prototype.renderNodeVariants = function (id) {

};


/**
 *
 */
Call.prototype.loadNode = function (id) {

    var node = this.data['nodes'][id];
    var workspace = Yiij.app.getModule('performer').workspaceController.workspace;

    this.end_node_uuid = id;
    var node_number = node['number'];
    this.end_node_id = node_number;
    this.end_node_content = node['content'];
    this.end_node_stage = node['call_stage_id'] ? node['call_stage_id'] : null;
    this.is_goal_reached = !!node['is_goal'];
    this.normal_ending = !!node['normal_ending'];

    var heading = '';

    heading += '<strong>#' + node_number + '</strong>';

    if (this.is_goal_reached) {
        heading += ' <span class="label label-success">Этот узел является целью.</span>'
    } else if (this.normal_ending) {
        heading += ' <span class="label label-primary">Возможно штатное завершение.</span>'
    }

    Yiij.app.getModule('performer').workspaceController.workspace.current_node_text = "<p class='current-node-heading'>" + heading + "</p>" + node['content'];

    var current_node_variants = '';

    var variants = this.nodeVariants(id);

    for (var r = 0; r < variants.length; r++) {

        var variant = variants[r];

        if (variant['target_id'] && this.data['nodes'][variant['target_id']]) {
            current_node_variants += '<div data-node="' + variant['target_id'] + '" data-variant="' + variant['id'] + '" class="' + workspace.performer_options.variants_size + ' variant-button btn btn-primary ' + (!!this.visited_nodes[variant['target_id']] ? "visited" : "") + '">' + variant['content'] + '</div>'
        }
    }

    var current_group_variants = '';

    if (this.data['group_variants'] && node['groups']) {

        var groups_array = node['groups'].split(",");

        for (var g = 0; g < groups_array.length; g++) {

            var group_id = groups_array[g];

            var group_variants = this.groupVariants(group_id);

            for (var j = 0; j < group_variants.length; j++) {
                var group_variant = group_variants[j];

                if (group_variant['group_id'] == group_id && group_variant['target_id'] && this.data['nodes'][group_variant['target_id']]) {
                    current_group_variants += '<div data-node="' + group_variant['target_id'] + '" data-variant="' + group_variant['id'] + '" class="' + workspace.performer_options.group_variants_size + ' variant-button btn btn-primary  ' + (!!this.visited_nodes[group_variant['target_id']] ? "visited" : "") + '">' + group_variant['content'] + '</div>'
                }
            }
        }
    }

    var functions_buttons = '';

    if (this.back_stack.length > 1) {
        functions_buttons += '<div class="btn btn-warning btn-xs" id="performer___functions_back">Назад</div>'
    }

    functions_buttons += '<div class="btn btn-success btn-sm" id="performer___functions_end_call">Завершить</div>'


    Yiij.app.getModule('performer').workspaceController.workspace.functions_buttons = functions_buttons;
    Yiij.app.getModule('performer').workspaceController.workspace.current_node_variants = current_node_variants;
    Yiij.app.getModule('performer').workspaceController.workspace.current_group_variants = current_group_variants;
};

/**
 * Получение объекта узла
 *
 * @param {string} node_id
 * @returns {[]}
 */
Call.prototype.nodeVariants = function (node_id) {
    Yiij.trace('Получаю список вариантов для узла ' + node_id + '.');

    var variants_raw = {};

    if (!this.data['nodes'][node_id]) {
        return [];
    }

    var node = this.data['nodes'][node_id];

    for (var i in this.data['variants']) {
        if (this.data['variants'].hasOwnProperty(i) && this.data['variants'][i].node_id == node_id) {
            variants_raw[i] = this.data['variants'][i];
        }
    }

    var nodes_result = [];

    var pushed = {};

    if (node.variants_sort_index) {
        var order = node.variants_sort_index.split(',');

        for (var o = 0; o < order.length; o++) {
            var variant_id = order[o];

            pushed[variant_id] = true;

            if (!variants_raw.hasOwnProperty(variant_id)) {
                continue;
            }

            nodes_result.push(variants_raw[variant_id]);
        }
    }

    var not_ordered = [];

    for (var v in variants_raw) {

        if (!variants_raw.hasOwnProperty(v) || pushed[v]) {
            continue;
        }

        not_ordered.push(variants_raw[v]);
    }

    not_ordered.sort(function (a, b) {
        var a_created = 0;

        var b_created = 0;

        if (a.created_at) {
            a_created = a.created_at;
        }

        if (b.created_at) {
            b_created = b.created_at;
        }

        if (a_created > b_created) {
            return 1;
        }

        if (a_created < b_created) {
            return -1;
        }

        return 0;
    });

    for (var n = 0; n < not_ordered.length; n++) {
        nodes_result.push(not_ordered[n]);
    }

    return nodes_result;
};

/**
 * Получение объекта узла
 *
 * @param {string} group_id
 * @returns {[]}
 */
Call.prototype.groupVariants = function (group_id) {
    Yiij.trace('Получаю список вариантов для группы ' + group_id + '.');

    if (!this.data['groups'][group_id]) {
        return [];
    }

    var variants_raw = {};

    var group = this.data['groups'][group_id];

    for (var i in this.data['group_variants']) {
        if (this.data['group_variants'].hasOwnProperty(i) && this.data['group_variants'][i].group_id == group_id) {
            variants_raw[i] = this.data['group_variants'][i];
        }
    }

    var nodes_result = [];

    var pushed = {};

    if (group.variants_sort_index) {
        var order = group.variants_sort_index.split(',');

        for (var o = 0; o < order.length; o++) {
            var variant_id = order[o];

            pushed[variant_id] = true;

            if (!variants_raw.hasOwnProperty(variant_id)) {
                continue;
            }

            nodes_result.push(variants_raw[variant_id]);
        }
    }

    var not_ordered = [];

    for (var v in variants_raw) {

        if (!variants_raw.hasOwnProperty(v) || pushed[v]) {
            continue;
        }

        not_ordered.push(variants_raw[v]);
    }

    not_ordered.sort(function (a, b) {
        var a_created = 0;

        var b_created = 0;

        if (a.created_at) {
            a_created = a.created_at;
        }

        if (b.created_at) {
            b_created = b.created_at;
        }

        if (a_created > b_created) {
            return 1;
        }

        if (a_created < b_created) {
            return -1;
        }

        return 0;
    });

    for (var n = 0; n < not_ordered.length; n++) {
        nodes_result.push(not_ordered[n]);
    }

    return nodes_result;
};


/**
 *
 */
Call.prototype.end = function () {

    Yiij.app.getModule('performer').workspaceController.workspace.functions_buttons = '';
    Yiij.app.getModule('performer').workspaceController.workspace.current_node_variants = '';
    Yiij.app.getModule('performer').workspaceController.workspace.current_group_variants = '';

    $('#performer___form__is_goal_reached').val(+this.is_goal_reached);
    $('#performer___form__normal_ending').val(+this.normal_ending);

    if (this.timer.interval) {
        this.timer.stop();
    }

    if (this.release_id == 'test') {
        Yiij.app.getModule('performer').workspaceController.workspace.message = "Тестовый звонок завершен!";
        Yiij.app.getModule('performer').workspaceController.stateTo(Workspace.STATE_MESSAGE);
    } else {
        Yiij.app.getModule('performer').workspaceController.stateTo(Workspace.STATE_END);
    }

    this.sendEnd();
};

/**
 * Loading DOM containers
 */
Call.prototype.setUnloadTrigger = function () {
    var c = this;

    $(window).off("unload beforeunload").on("unload beforeunload", function () {
        if (c.id) {
            $.ajax({
                'type': 'get',
                'method': 'POST',
                'async': false,
                'dataType': "JSON",
                'data': {'_csrf': window['_csrf'], 'Call': c.getPostData()},
                'url': '/script/call/end?id=' + c.id
            });
        }
    });
};

/**
 * Loading DOM containers
 */
Call.prototype.clearUnloadTrigger = function () {
    $(window).off("unload beforeunload");
};

/**
 *
 */
Call.prototype.getPostData = function () {
    return {
        'is_goal_reached': $('#performer___form__is_goal_reached').val(),
        'normal_ending': $('#performer___form__normal_ending').val(),
        'comment': $('#performer___form__comment').val(),
        'duration': this.timer.elapsed_seconds,
        'call_history': JSON.stringify(this.history),
        'last_word': this.last_word,
        'start_node_id': this.start_node_id,
        'start_node_uuid': this.start_node_uuid,
        'end_node_uuid': this.end_node_uuid,
        'end_node_content': this.end_node_content,
        'end_node_stage': this.end_node_stage,
        'nodes_passed': this.history.length,
        'end_node_id': this.end_node_id,
        'record_file': Yiij.app.getModule('performer').recorder.file,
        'fields': JSON.stringify(Yiij.app.getModule("context").exportData()),
        'perform_page': Yiij.app.getModule('performer').perform_page
    };

};

/**
 *
 */
Call.prototype.sendReport = function () {
    var c = this;

    $('#performer___form').addClass('disabled');

    $.ajax({
        'type': 'get',
        'method': 'POST',
        'async': true,
        'dataType': "JSON",
        'data': {'_csrf': window['_csrf'], 'Call': this.getPostData()},
        'url': '/script/call/report?id=' + this.id,
        'success': function (data) {
            $('#performer___form').removeClass('disabled');

            Yiij.app.getModule('performer').callController.prepare(c.script_id, c.release_id);
        },
        error: function (jqXHR) {
            $('#performer___form__errors').html(jqXHR.responseJSON.message);
            $('#performer___form').removeClass('disabled');
        }
    });
};

/**
 *
 */
Call.prototype.sendEnd = function () {
    if (this.id !== 'test') {
        Yiij.app.getModule('performer').recorder.stop();

        $.ajax({
            'type': 'get',
            'method': 'POST',
            'async': true,
            'dataType': "JSON",
            'data': {'_csrf': window['_csrf'], 'Call': this.getPostData()},
            'url': '/script/call/end?id=' + this.id
        });
    }

    this.clearUnloadTrigger();
};

/**
 *
 */
Call.prototype.destroy = function () {
    this.clearWorkspace();
    if (this.timer.interval) {
        this.timer.stop();
    }
};