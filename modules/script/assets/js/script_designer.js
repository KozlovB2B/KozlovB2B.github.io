/**
 * Common Cases list
 *
 * @constructor
 */
var CommonCasesRedactor = function (common_cases) {
    var d = this;
    d.common_cases = common_cases;
    console.log(d.common_cases);

    if(!d.common_cases){
        d.common_cases = [];
    }

    //d.common_cases = [
    //    {
    //        'id': guid(),
    //        'text': 'rot ebal 1',
    //        'stage': '',
    //        'target': ''
    //    },
    //    {
    //        'id': guid(),
    //        'text': 'rot ebal 2',
    //        'stage': '',
    //        'target': 1
    //    },
    //    {
    //        'id': guid(),
    //        'text': 'rot ebal 3',
    //        'stage': 3,
    //        'target': 2
    //    }
    //];

    if (document.readyState === "complete") {
        d.init();
    }
    else {
        window.addEventListener("load", function () {
            d.init();
        });
    }
};

/**
 * INIT
 */
CommonCasesRedactor.prototype.init = function () {
    var d = this;
    d.generateTabular();
    d.setEvents();
};

/**
 * Save data
 */
CommonCasesRedactor.prototype.save = function () {
    var d = this;

    $('#script___script___update_form_common_cases').val(JSON.stringify(d.common_cases));
};

/**
 * Delete elem by id
 */
CommonCasesRedactor.prototype.updateElemById = function (id, attribute, value) {
    var d = this;

    console.log(id);

    for (var i in d.common_cases) {
        if (d.common_cases[i].id == id) {
            console.log(d.common_cases[i]);
            d.common_cases[i][attribute] = value;

            break;
        }
    }

    console.log(d.common_cases);

    d.save();
};


/**
 * Delete elem by id
 */
CommonCasesRedactor.prototype.getById = function (id) {
    var d = this;

    for (var i in d.common_cases) {
        if (d.common_cases[i].id == id) {
            return d.common_cases[i];
        }
    }
};


/**
 * Delete elem by id
 */
CommonCasesRedactor.prototype.deleteById = function (id) {
    var d = this;

    for (var i in d.common_cases) {
        if (d.common_cases[i].id == id) {
            $("#" + id).remove();
            d.common_cases.splice(i, 1);
            break;
        }
    }

    d.save();
};


CommonCasesRedactor.prototype.setEvents = function () {
    var d = this;

    setEvent('click', '#script___script___common_cases_add', function () {
        d.common_cases.push({
            'id': guid(),
            'text': 'Текст ответа',
            'stage': '',
            'target': ''
        });

        console.log(d.common_cases);

        d.save();

        d.generateTabular();
    });

    setEvent('click', '.script___script___common_cases_case_close', function () {
        d.deleteById($(this).closest('.script___script___common_cases_case').attr('id'));
    });

    setEvent('click', '.script___script___common_cases_case_text .wrapper', function () {
        $('.script___script___common_cases_case_text_edit').trigger('blur');
        var parent = $(this).closest('td');
        var elem = d.getById(parent.closest('.script___script___common_cases_case').attr('id'));
        parent.html('<input type="text" class="form-control script___script___common_cases_case_text_edit" value="' + elem.text + '" />');
        parent.find('input').trigger('focus');
    });

    setEvent('blur change', '.script___script___common_cases_case_text_edit', function () {
        var value = $(this).val();

        var id = $(this).closest('.script___script___common_cases_case').attr('id');
        $(this).closest('td').html('<div class="wrapper">' + value + '</div>');
        d.updateElemById(id, 'text', value);
    });


    setEvent('click', '.script___script___common_cases_case_stage .wrapper', function () {
        var parent = $(this).closest('td');
        var elem = d.getById(parent.closest('.script___script___common_cases_case').attr('id'));
        parent.html($('#script___script___common_cases_call_stages_list').html());
        console.log(elem.stage);
        parent.find('select').val(elem.stage);
    });

    setEvent('blur change', '.script___script___common_cases_case_stage select', function () {
        var value = $(this).val();
        var label = $(this).find('option:selected').text();
        if(!value){
            label = '';
        }
        var id = $(this).closest('.script___script___common_cases_case').attr('id');
        $(this).closest('td').html('<div class="wrapper">' + label + '</div>');
        d.updateElemById(id, 'stage', value);
    });


    setEvent('click', '.script___script___common_cases_case_target .wrapper', function () {
        var parent = $(this).closest('td');
        var elem = d.getById(parent.closest('.script___script___common_cases_case').attr('id'));

        parent.html('<select id="script___script___common_cases_case_target_select"></select>');

        var selectize = window['designer'].initSelectNodeSelectize('script___script___common_cases_case_target_select');

        selectize.setValue(elem.target);

        $('#script___script___common_cases_case_target_select').addClass('active');
    });

    setEvent('blur change', '#script___script___common_cases_case_target_select.active', function () {
        var value = $(this).val();
        var label = '#' + value + ' ' + $(this).find('option:selected').text();
        if(!value){
            label = '';
        }
        label = label.replace('&nbsp;', ' ').substr(0, 20)
        var id = $(this).closest('.script___script___common_cases_case').attr('id');
        $(this).closest('td').html('<div class="wrapper">' + label + '</div>');
        d.updateElemById(id, 'target', value);
    });
};

CommonCasesRedactor.prototype.generateTabular = function () {
    var d = this;

    var tabular = $('#script___script___common_cases_tabular');

    tabular.html('<table class="table table-striped table-bordered"><tr><th>Текст</th><th>Этап звонка</th><th>Целевой узел</th><th></th></tr></table>');

    var nodes = window['designer'].toolkit.exportData().nodes;
    console.log(nodes);

    for (var i in d.common_cases) {
        var common_case = d.common_cases[i];


        if (typeof common_case === 'object') {
            var call_stage_text = '';
            var target_text = '';

            if (common_case.stage) {
                call_stage_text = window['designer'].call_stages[common_case.stage];
            }

            if (common_case.target) {
                for (var j in nodes) {
                    if (nodes[j].id == common_case.target) {
                        target_text = '#' + common_case.target + ' ' + nodes[j].content.replace('&nbsp;', ' ').substr(0, 20);
                    }
                }
            }

            var tr =
                '<tr class="script___script___common_cases_case" id="' + common_case.id + '">' +
                '<td class="script___script___common_cases_case_text"><div class="wrapper">' + common_case.text + '</div></td>' +
                '<td class="script___script___common_cases_case_stage"><div class="wrapper">' + call_stage_text + '</div></td>' +
                '<td class="script___script___common_cases_case_target"><div class="wrapper">' + target_text + '</span></td>' +
                '<td> <button type="button" class="close script___script___common_cases_case_close">×</button></td>' +
                '</tr>';

            tabular.find('table').append(tr);
        }

    }

};

/**
 * Designer of scripts
 *
 * @constructor
 */
var ScriptDesigner = function (id, data, call_stages, focus_node) {
    var d = this;
    d.id = id;
    d.call_stages = call_stages;
    d.init_big_numbers_on_zoom = true;
    d.focus_node = focus_node;
    d.current_node_data_has_changed = false;
    data.ports = [];

    d.edges_map = {};

    for (var i in data.edges) {
        var edge_id = data.edges[i].source + '-' + data.edges[i].target;
        if (typeof  data.edges[i] !== 'function') {
            d.edges_map[edge_id] = data.edges[i];
        }
    }

    d.data = data;

    d.data.edges = [];


    for (var e in d.edges_map) {
        d.data.edges.push(d.edges_map[e]);
    }

    d.nodes_forms_submit_callbacks = {};
    d.nodes_forms_update_callbacks = {};
    d.edges_forms_update_callbacks = {};
    d.edges_forms_edit_callbacks = [];


    d.history = [];

    if (document.readyState === "complete") {
        d.init();
    }
    else {
        window.addEventListener("load", function () {
            d.init();
        });
    }
};

/**
 * Designer init
 */
ScriptDesigner.prototype.init = function () {
    var d = this;
    this.initWysi();
    this.loadDomContainers();
    this.setDimensions();

    $(window).resize(function () {
        d.setDimensions();
    });

    this.initToolkit();
    this.initEvents();
    this.updateCallStages();
    this.initSearch();
    this.initSelectNodeSelectize('script___script___update_form_start_node_id');

    if (this.focus_node) {
        this.focusNode(this.focus_node);
    }
};

/**
 * Focus node in user's view
 */
ScriptDesigner.prototype.focusNode = function (id) {
    var d = this;
    var id = id.toString();

    d.renderer.setZoom(1.8);
    d.renderer.centerOn(id.toString());


    var box = this.findNodeDiv(id);

    if (box.find('.jp-table-column')) {
        box.addClass('editor___toolbar_tools_focus_node');

        setTimeout(function () {
            box.removeClass('editor___toolbar_tools_focus_node');
        }, 20000);
    }
};


/**
 * Loading DOM containers
 */
ScriptDesigner.prototype.initSelectNodeSelectize = function (id) {
    var d = this;
    var select = $('#' + id);
    var value = select.val();

    var selectize = select[0].selectize;

    if (selectize) {
        selectize.destroy();
    }

    select.selectize({
        valueField: 'id',
        labelField: 'content',
        searchField: ['id', 'content'],
        plugins: ['clear_button'],
        options: d.toolkit.exportData().nodes,
        render: {
            item: function (item, escape) {
                var node_select_name = item.content.replace('&nbsp;', ' ').replace('&lt;', '<').replace('&gt;', '>').substr(0, 20);

                return '<div>' +
                    '<span class="name selectize_note_id" data-id="' + escape(item.id) + '">#' + escape(item.id) + '</span> ' +
                    '<span class="email">' + escape(node_select_name) + '</span>' +
                    '</div>';
            },
            option: function (item, escape) {
                var node_select_name = item.content.replace('&nbsp;', ' ').replace('&lt;', '<').replace('&gt;', '>').substr(0, 70);

                return '<div>' +
                    '<span class="name selectize_note_id"  data-id="' + escape(item.id) + '">#' + escape(item.id) + '</span> ' +
                    '<span class="email">' + escape(node_select_name) + '</span>' +
                    '</div>';
            }
        }
    });

    var selectize = select[0].selectize;

    if (selectize) {
        selectize.setValue(value);
    }


    return select[0].selectize;
};


/**
 * Loading DOM containers
 */
ScriptDesigner.prototype.loadDomContainers = function () {
    this.mainElement = document.querySelector("#script___designer__main_container");
    this.canvasElement = this.mainElement.querySelector("#script___designer__canvas");
    this.miniviewElement = this.mainElement.querySelector("#script___designer__miniview");
    this.startNodeSelect = $("#script___script___update_form_start_node_id");
};

/**
 * Loading DOM containers
 */
ScriptDesigner.prototype.setDimensions = function () {
    var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
    var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    var mainElement = document.querySelector("#script___designer__main_container");
    var canvasElement = document.querySelector("#script___designer__canvas");
    canvasElement.style.height = (h - mainElement.offsetTop) + "px";

};

ScriptDesigner.prototype.initSearchDataProvider = function () {
    var d = this;
    d.search_data_provider = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('content', 'id'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: d.toolkit.exportData().nodes
    });

    d.search_data_provider.initialize();
};

/**
 * Highlight node while search results selected or hovered
 * @param id
 */
ScriptDesigner.prototype.highlightNode = function (id) {
    if (typeof id !== 'undefined') {
        var box = this.findNodeDiv(id);

        if (box.find('.jp-table-column')) {
            box.addClass('editor___toolbar_tools_search_highlight');
            box.removeClass('editor___toolbar_tools_search_highlight_small');
        } else {
            box.addClass('editor___toolbar_tools_search_highlight editor___toolbar_tools_search_highlight_small');
        }
    } else {
        $(".jtk-node").addClass('editor___toolbar_tools_search_highlight');
    }
};

/**
 * Remove search highlight from node
 * @param id
 */
ScriptDesigner.prototype.removeNodesSearchHighlight = function (id) {
    if (typeof id !== 'undefined') {
        this.findNodeDiv(id).removeClass('editor___toolbar_tools_search_highlight');
    } else {
        $(".jtk-node").removeClass('editor___toolbar_tools_search_highlight');
    }
};

/**
 * Finds node div by id
 *
 * @param id
 * @returns {*|jQuery|HTMLElement}
 */
ScriptDesigner.prototype.findNodeDiv = function (id) {
    return $("div[data-jtk-node-id='" + id + "']");
};
/**
 * Finds node div by id
 *
 * @param id
 * @returns {*|jQuery|HTMLElement}
 */
ScriptDesigner.prototype.findPortLi = function (id) {
    return $("li[data-port-id='" + id + "']");
};

/**
 * Init search
 */
ScriptDesigner.prototype.initSearch = function () {
    var d = this;

    d.initNodeSelectTypeAhead('#editor___toolbar_tools_search_input').on('typeahead:selected', function (event, data) {
        d.removeNodesSearchHighlight();

        if (typeof data.id !== 'undefined') {
            d.editNode(d.renderer.getObjectInfo(d.findNodeDiv(data.id)));
        }
    });
};

/**
 * Loading DOM containers
 */
ScriptDesigner.prototype.initNodeSelectTypeAhead = function (element) {
    var d = this,
        input = $(element);

    input.typeahead('destroy');

    d.initSearchDataProvider();

    var instance = input.typeahead(null, {
        name: 'data_source',
        displayKey: 'content',
        source: d.search_data_provider.ttAdapter(),
        limit: 10,
        templates: {
            suggestion: function (item) {
                return '<div class="editor___toolbar_tools_search_input_suggestion" data-id="' + item.id + '"><strong>#' + item.id + '</strong> ' + item.content + '</div>'
            }
        }
    }).on('typeahead:cursorchange', function (event, data) {
        d.removeNodesSearchHighlight();

        if (typeof data !== 'undefined' && typeof data.id !== 'undefined') {
            d.highlightNode(data.id);
        }
    }).on('typeahead:close', function (event, data) {
        d.removeNodesSearchHighlight();
    });

    setEvent('mouseover', '.editor___toolbar_tools_search_input_suggestion', function () {
        var id = $(this).data('id');
        if (typeof id !== 'undefined' && id) {
            d.highlightNode(id);
        }

        return true;
    });

    setEvent('mouseleave', '.editor___toolbar_tools_search_input_suggestion', function () {
        var id = $(this).data('id');
        if (typeof id !== 'undefined' && id) {
            d.removeNodesSearchHighlight(id);
        }

        return true;
    });

    return instance;
};

/**
 *
 * @param info
 * @returns {string}
 */
ScriptDesigner.prototype.getPortTarget = function (info) {
    var edge = this.getPortEdge(info);

    if (edge) {
        return edge.target.getFullId().replace('.head', '');
    } else {
        return '';
    }
};

/**
 * Form answers tabular
 */
ScriptDesigner.prototype.fillAnswersTabular = function () {
    var d = this;

    var edges_list = $('#script___node___update_form_edges');
    var info = d.renderer.getObjectInfo(d.findNodeDiv(edges_list.attr('data-id')));
    var columns = info.obj.data.columns;
    edges_list.html('');

    for (var i in columns) {
        var column = columns[i];
        if (typeof column == 'object') {

            //var column_info = d.renderer.getObjectInfo(d.findPortLi(column.id));

            //var target = d.getPortTarget(column_info);

            var target_html = '';
            var close_html = '<button type="button" class="close pull-right script___node___update_form_delete_edge">×</button>';

            //if (target) {
            //    var target_info = d.renderer.getObjectInfo(d.findNodeDiv(target));
            //    var target_name = target_info.obj.data.content.replace('&nbsp;', '').substr(0, 30);
            //    target_html = '<br/><small>#' + target + ' ' + target_name + '</small>';
            //}

            edges_list.append('<li class="list-group-item script___node___update_form_edit_edge" data-id="' + column.id + '">' + column.content + target_html + close_html + '</li>');
        }
    }
};

ScriptDesigner.prototype.editAnswersTabular = function (columns) {
    $('#script___node___update_form_edges').show();
    $('#script___edge___add_to_form').show();
    $('#script___node___update_form_edit_edges_button').hide();
};

ScriptDesigner.prototype.getPortEdge = function (info) {
    var edges = info.obj.getSourceEdges();

    if (edges[0] && edges[0].target && edges[0].target.getFullId()) {
        return edges[0];
    }

    return false;
};

/**
 * Edit node
 *
 * @param info
 */
ScriptDesigner.prototype.editNode = function (info) {
    var d = this;

    //var info = d.renderer.getObjectInfo(this);
    clearFormErrors("script___node___update_form");
    $("#script___node___update_form_id").val(info.obj.id);


    var field_content = info.obj.data.content;

    if (typeof info.obj.data.content_formatted !== 'undefined') {
        field_content = info.obj.data.content_formatted;
    }

    d.script___node___update_form_content_editor.setValue(field_content);

    if (typeof info.obj.data.call_stage_id !== 'undefined') {
        $("#script___node___update_form_call_stage_id").val(info.obj.data.call_stage_id);
    }

    if (typeof info.obj.data.is_goal !== 'undefined' && info.obj.data.is_goal) {
        $("#script___node___update_form_is_goal").prop('checked', 1);
    } else {
        $("#script___node___update_form_is_goal").prop('checked', 0);
    }
    if (typeof info.obj.data.normal_ending !== 'undefined' && info.obj.data.normal_ending) {
        $("#script___node___update_form_normal_ending").prop('checked', 1);
    } else {
        $("#script___node___update_form_normal_ending").prop('checked', 0);
    }

    if (typeof info.obj.data.width !== 'undefined' && info.obj.data.width > 60) {
        $("#script___edge___edit_form_width").val(info.obj.data.width);
        $('#script___node___update_form_modal .modal-dialog').width(info.obj.data.width + '%');
    } else {
        $('#script___node___update_form_modal .modal-dialog').width('60%');
    }


    d.nodes_forms_update_callbacks[info.obj.id] = function (content, call_stage_id) {

        if (typeof call_stage_id == "undefined" || !call_stage_id) {
            call_stage_id = '';
        }

        d.toolkit.updateNode(info.obj, {
            content: content.replace(/<[^>]+>/gi, ''),
            content_formatted: content,
            call_stage_id: call_stage_id,
            is_goal: $("#script___node___update_form_is_goal").prop('checked'),
            width: $("#script___edge___edit_form_width").val(),
            editor_width: $("#script___node___update_form_modal .wysihtml5-sandbox").width(),
            editor_height: $("#script___node___update_form_modal .wysihtml5-sandbox").height(),
            normal_ending: $("#script___node___update_form_normal_ending").prop('checked')
        });

        d.initSearch();
    };

    $('#script___node___update_form_modal_node_id_heading').text(info.obj.id);


    $('#script___node___update_form_edit_edges_button').show();
    $("#script___edge___edit_form").hide();
    $("#script___edge___add_to_form").hide();
    $("#script___node___update_form_edges").hide().attr('data-id', info.obj.data.id);

    d.fillAnswersTabular();

    $(".jtk-node").removeClass('editor___toolbar_tools_search_highlight_opened');
    d.findNodeDiv(info.obj.id).addClass('editor___toolbar_tools_search_highlight_opened');

    d.edges_forms_edit_callbacks = [];

    d.fillPreviousNodesHeader(info);

    $("#script___node___update_form_modal").modal("show");

    d.current_node_data_has_changed = false;

    setTimeout(function () {
        if (typeof info.obj.data.editor_width !== 'undefined') {
            $("#script___node___update_form_modal .wysihtml5-sandbox").width(info.obj.data.editor_width);
        } else {
            $("#script___node___update_form_modal .wysihtml5-sandbox").width($('#script___node___update_form_modal .field-script___node___update_form_content').width() - 26);
        }

        if (typeof info.obj.data.editor_height !== 'undefined') {
            $("#script___node___update_form_modal .wysihtml5-sandbox").height(info.obj.data.editor_height);
        } else {
            $('#script___node___update_form_modal .wysihtml5-sandbox').height('100%');
        }
    }, 300);
};

/**
 * Edit node
 *
 * @param info
 */
ScriptDesigner.prototype.fillPreviousNodesHeader = function (info) {
    var d = this;

    var map = {};
    var data = d.toolkit.exportData();

    // /script/script/update?id=' + d.id + '&focus_node=' + source_data[0] + '

    for (var i in data.edges) {
        if (data.edges[i].source) {
            var source_data = data.edges[i].source.split('.');
            if (source_data[0]) {
                if (data.edges[i].target == info.obj.id + '.head' && !map[source_data[0]]) {
                    map[source_data[0]] = '<a href="#" class="script___node___update_form_modal_heading_edit_node" data-id="' + source_data[0] + '">#' + source_data[0] + '</a>';
                }
            }
        }
    }

    var links = [];

    for (var i in map) {
        links.unshift(map[i]);
    }

    $("#script___node___update_form_modal_previous_nodes_heading").html(links.join(', '));

    return false;
};

/**
 * Init working events, dialogs e.t.c.
 */
ScriptDesigner.prototype.initEvents = function () {
    var d = this;

    /**
     *
     */
    setEvent('click', '#script___designer__try_script', function () {
        d.performTest();
        return false;
    });

    /**
     *
     */
    setEvent('click', '#script___designer__function_fit', function () {
        d.toolkit.clearSelection();
        d.renderer.zoomToFit();
    });

    /**
     *
     */
    setEvent('click', '#script___edge___add_to_list_button', function () {

        var input = $('#script___edge___add_to_list_field');

        if (!input.val()) {
            return false;
        }

        var port_id = "e" + d.incrementMaxEdge().toString();
        var source = $("#script___node___update_form_edges").attr('data-id');


        d.toolkit.addNewPort(source, "source", {
            id: port_id,
            content: input.val()
        });

        input.val('');

        d.fillAnswersTabular();
    });

    /**
     *
     */
    setEvent('click', '#script___node___update_form_edit_edges_button', function () {
        d.editAnswersTabular();
        return false;
    });

    setEvent('hidden.bs.modal', '#script___node___update_form_modal', function () {
        for (var i in d.edges_forms_edit_callbacks) {
            d.edges_forms_edit_callbacks[i]();
        }

        d.edges_forms_edit_callbacks = [];
    });


    /**
     *
     */
    setEvent('click', '#script___designer__undo', function () {
        d.undo();
        return false;
    });

    /**
     *
     */
    setEvent('click', '#script___designer__add_node_by_drop', function () {

        var center = d.renderer.getViewportCenter();

        d.createNode({name: "node", type: "node", left: center[0], top: center[1]}, function (b) {
            d.toolkit.addNode(b, {position: {left: center[0], top: center[1]}});
        });
    });

    $('#script___designer__add_node_by_drop').draggable({
        helper: "clone",
        cursor: "move",
        revert: "invalid",
        containment: "document"
    });

    $('#script___designer__canvas').droppable({
        accept: "#script___designer__add_node_by_drop",
        drop: function (event, ui) {
            var page_location = d.renderer.getOffset(ui.helper);
            var canvas_location = d.renderer.mapLocation(page_location.left, page_location.top);
            d.createNode({name: "node", type: "node", left: canvas_location.left, top: canvas_location.top}, function (b) {
                d.toolkit.addNode(b, {position: canvas_location});
            });
        }
    });


    /**
     *
     */
    setEvent('click', '#script___designer__redo', function () {
        d.redo();
        return false;
    });

    /**
     *
     */
    setEvent('submit', '#script___script___update_form', function () {
        d.updateScript();
        return false;
    });

    /**
     *
     */
    setEvent('submit', '#script___edge___create_form', function () {
        d.createEdge();
        return false;
    });

    /**
     *
     */
    setEvent('submit', '#script___edge___update_form', function () {
        d.updateEdge();
        return false;
    });

    /**
     *
     */
    setEvent('submit', '#script___node___update_form', function () {
        d.updateNode();
        return false;
    });


    /**
     *
     */
    setEvent('mouseover', '.editor___toolbar_tools_search_highlight', function () {
        $(this).removeClass('editor___toolbar_tools_search_highlight');
    });

    ///**
    // *
    // */
    //setEvent('click', '#script___edge___update_form_target_clear', function () {
    //
    //    var select = $('#script___edge___update_form_target_clear');
    //
    //    var selectize = select[0].selectize;
    //    selectize.clear();
    //    return false;
    //});

    /**
     *
     */
    setEvent('submit', '#script___node___create_form', function () {
        d.submitNodeCreatingForm();
        return false;
    });

    setEvent('click', '.jp-table-edit', function () {
        d.editNode(d.renderer.getObjectInfo(this));
        return false;
    });

    interact('#script___node___update_form_modal .modal-dialog')
        .resizable({
            edges: {left: true, right: true, bottom: true, top: true}
        })
        .on('resizemove', function (event) {
            var target = event.target;

            var w = parseFloat(event.rect.width / $('#script___node___update_form_modal').width()) * 100;

            if (w < 60) {
                w = 60;
            }

            $(target).width(w + '%');
            $('#script___edge___edit_form_width').val(w);
        });


    setEvent('change', '#script___node___update_form_modal input, #script___node___update_form_modal select, #script___node___update_form_modal textarea', function () {
        d.current_node_data_has_changed = true;
        return true;
    });

    setEvent('click', '.script___node___update_form_modal_heading_edit_node', function () {

        if (d.current_node_data_has_changed === true && !confirm($('#script___node___update_form_warning').text())) {
            return false;
        }

        d.editNode(d.renderer.getObjectInfo(d.findNodeDiv($(this).data('id'))));

        return false;
    });
    setEvent('hide.bs.modal.prevent', '#script___node___update_form_modal', function () {

        if (d.current_node_data_has_changed === true && !confirm($('#script___node___update_form_hide_warning').text())) {
            return false;
        }

        return true;
    });


    setEvent('mouseover', '.script___node___update_form_modal_heading_edit_node', function () {

        var info = d.renderer.getObjectInfo(d.findNodeDiv($(this).data('id')));

        $(this).tooltip({
            html: true,
            placement: 'bottom',
            title: info.obj.data.content_formatted
        }).tooltip('show');

        return false;
    });

    setEvent('mouseover', '#script___edge___edit_form_target_wrapper .selectize_note_id', function () {

        var info = d.renderer.getObjectInfo(d.findNodeDiv($(this).data('id')));

        $(this).tooltip({
            html: true,
            placement: 'left',
            container: 'body',
            title: info.obj.data.content_formatted
        }).tooltip('show');

        return false;
    });

    setEvent('click', '.jp-table-try-call', function () {
        d.performTest(d.renderer.getObjectInfo(this).id);
        return false;
    });

    setEvent('click', '.jp-table-copy', function () {
        d.copyNode(d.renderer.getObjectInfo(this));
        return false;
    });

    setEvent('click', '.script___edge__add_button', function () {
        var info = d.renderer.getObjectInfo(this);
        clearFormErrors("script___edge___create_form");
        $("#script___edge___create_form_source").val(info.id);
        $("#script___edge___create_form_content").val(null);
        $("#script___edge___create_form_target").val(null);
        d.initSelectNodeSelectize('script___edge___create_form_target');
        $("#script___edge___create_modal").modal("show");
    });

    setEvent('click', '.jp-table-column-edit', function () {
        var info = d.renderer.getObjectInfo(this);
        clearFormErrors("script___edge___update_form");
        $("#script___edge___update_form_id").val(info.obj.data.id);
        $("#script___edge___update_form_content").val(info.obj.data.content);

        var target = d.getPortTarget(info);

        $("#script___edge___update_form_target").val(target);

        var selectize = d.initSelectNodeSelectize('script___edge___update_form_target');

        selectize.setValue(target);

        d.edges_forms_update_callbacks[info.obj.id] = function (content) {
            d.toolkit.updatePort(info.obj, {
                content: content
            });
        };

        $("#script___edge___update_modal").modal("show");
    });

    setEvent('click', '.script___node___update_form_edit_edge', function () {
        var info = d.renderer.getObjectInfo(d.findPortLi($(this).data('id')));
        $("#script___edge___edit_form_id").val(info.obj.data.id);
        $("#script___edge___edit_form_content").val(info.obj.data.content);

        var target = d.getPortTarget(info);

        $("#script___edge___edit_form_target").val(target);

        var selectize = d.initSelectNodeSelectize('script___edge___edit_form_target');

        selectize.setValue(target);

        d.edges_forms_update_callbacks[info.obj.id] = function (content) {
            d.toolkit.updatePort(info.obj, {
                content: content
            });
        };

        $("#script___node___update_form_edges").hide();
        $("#script___edge___add_to_form").hide();
        $("#script___edge___edit_form").show();
    });

    setEvent('click', '#script___edge___edit_form_dismiss', function () {
        $("#script___node___update_form_edges").show();
        $("#script___edge___add_to_form").show();
        $("#script___edge___edit_form").hide();

    });

    setEvent('click', '#script___edge___edit_form_submit', function () {
        d.editEdge();
    });

    setEvent('click', '.jp-table-column-delete', function () {
        var info = d.renderer.getObjectInfo(this);
        if (confirm("Вы действительно хотите удалить ответ?")) {
            d.toolkit.removePort(info.obj.getNode(), info.id);
        }
        return false;
    });


    setEvent('click', '.script___node___update_form_delete_edge', function () {
        var parent = $(this).closest('li');
        var id = parent.attr('data-id');
        var info = d.renderer.getObjectInfo(d.findPortLi(id));

        if (confirm("Вы действительно хотите удалить ответ?")) {
            d.toolkit.removePort(info.obj.getNode(), info.id);
            parent.remove();
        }

        return false;
    });

    setEvent('click', '.delete', function () {
        var info = d.renderer.getObjectInfo(this);
        if (confirm("Вы действительно хотите удалить узел? Будут удалены все связанные ответы и переходы!")) {
            d.toolkit.removeNode(info);
            d.initSearch();
        }
        return false;
    });

};

/**
 * Updating form data after toolkit init and on every change
 */
ScriptDesigner.prototype.updateData = function () {
    var d = this;
    var data = d.toolkit.exportData();
    data.ports = [];


    $("#script___script___update_form_script_data").val(JSON.stringify(data));
};


/**
 * Init toolkit
 */
ScriptDesigner.prototype.initToolkit = function () {
    var d = this;

    // This function is what the toolkit will use to get an ID from a node.
    var idFunction = function (n) {
        return n.id;
    };

    // This function is what the toolkit will use to get the associated type from a node.
    var typeFunction = function (n) {
        return n.type;
    };

    // Declare an instance of the Toolkit, and supply the functions we will use to get ids and types from nodes.
    d.toolkit = jsPlumbToolkit.newInstance({
        idFunction: idFunction,
        typeFunction: typeFunction,
        nodeFactory: function (type, data, callback) {
            d.createNode(data, callback);
        },
        edgeFactory: function (params, data, callback) {
            callback(data);
        },
        portFactory: function (params, data, callback) {
            if (data.id !== "head") {
                params.node.data.columns.push(data);
            }
            callback(data);
        },
        //
        // For a given Node, return the parts of its dataset that we want to configure as Ports.
        // This is called when the data is being loaded. It is an optional argument to the newInstance
        // method.
        //
        portExtractor: function (data, node) {
            d.toolkit.addNewPort(data.id, "target", {id: "head"});
            return data.columns || [];
        },
        beforeStartConnect: function (node, edgeType) {
            // limit edges from start node to 1. if any other type of node, return
            return node.getEdges().length > 0 ? false : true;
        }
    });


// ------------------------- / behaviour ----------------------------------

// ------------------------ rendering ------------------------------------

    // Instruct the toolkit to render to the 'canvas' element. We pass in a model of nodes, edges and ports, which
    // together define the look and feel and behaviour of this d.renderer.  Note that we can have 0 - N renderers
    // assigned to one instance of the Toolkit..
    d.renderer = d.toolkit.render({
        container: d.canvasElement,
        view: {
            // Two node types - 'table' and 'view'
            nodes: {
                "node": {
                    template: "tplNode"
                }
            },
            // Three edge types  - '1:1', '1:N' and 'N:M',
            // sharing  a common parent, in which the connector type
            // and appearance is defined.
            edges: {
                "common": {
                    connector: ["Flowchart", {cornerRadius: 5}],
                    //connector: "StateMachine",  //	use StateMachine connector type
                    paintStyle: {lineWidth: 3, strokeStyle: "#f76258"},   // paint style for this edge type.
                    hoverPaintStyle: {lineWidth: 3, strokeStyle: "#434343"}, // hover paint style for this edge type.
                    maxConnections: -1,
                    overlays: [
                        ["Label", {
                            cssClass: "delete-relationship",
                            label: "<i class='fa fa-times'></i>",
                            events: {
                                "tap": function (params) {
                                    d.toolkit.removeEdge(params.edge);
                                }
                            }
                        }],
                        ["Arrow", {location: 0.1, width: 10, length: 10}],
                        ["Arrow", {location: 0.9, width: 10, length: 10}]
                    ]
                }
            },
            // There is only one type of Port - a column - so we use the key 'default' for the port type
            // Here we define the appearance of this port,
            // and we instruct the Toolkit what sort of Edge to create when the user drags a new connection
            // from an instance of this port. Note that we here we tell the Toolkit to create an Edge of type
            // 'common' because we don't know the cardinality of a relationship when the user is dragging. Once
            // a new relationship has been established we can ask the user for the cardinality and update the
            // model accordingly.
            ports: {
                "default": {
                    template: "tplEdge",
                    endpoint: "Blank",
                    paintStyle: {fillStyle: "#f76258"},
                    anchor: ["Left", "Right"],
                    edgeType: "common",
                    maxConnections: 1,


                    //hoverPaintStyle: {fillStyle: "#434343"}, // appearance when mouse hovering on endpoint or connection
                    isSource: true, // indicates new connections can be dragged from this port type
                    isTarget: false,// indicates new connections can be dragged to this port type
                    dropOptions: {  //drop options for the port. here we attach a css class.
                        hoverClass: "drop-hover"
                    },
                    allowLoopback: false,   // do not allow loopback connections from a port to itself.
                    allowNodeLoopback: false, // do not allow connections from this port to any other port on the same node.
                    events: {
                        "dblclick": function () {
                        }
                    }
                },
                "source": {
                    template: "tplEdge",
                    endpoint: "Blank",
                    paintStyle: {fillStyle: "#f76258"},
                    anchor: ["Left", "Right"],
                    edgeType: "common",
                    maxConnections: 1,


                    //hoverPaintStyle: {fillStyle: "#434343"}, // appearance when mouse hovering on endpoint or connection
                    isSource: true, // indicates new connections can be dragged from this port type
                    isTarget: false,// indicates new connections can be dragged to this port type
                    dropOptions: {  //drop options for the port. here we attach a css class.
                        hoverClass: "drop-hover"
                    },
                    allowLoopback: false,   // do not allow loopback connections from a port to itself.
                    allowNodeLoopback: false, // do not allow connections from this port to any other port on the same node.
                    events: {
                        "dblclick": function () {
                        }
                    }
                },
                "target": {
                    maxConnections: -1,
                    endpoint: "Blank",
                    anchor: ["Left", "Right", "Top"],
                    paintStyle: {fillStyle: "#84acb3"},
                    edgeType: "common",
                    isTarget: true,
                    isSource: false
                }
                //"default": {
                //    template: "tplHeadPort",
                //    endpoint: "Blank",		// the type of the endpoint
                //    paintStyle: {fillStyle: "#f76258"},		// the endpoint's appearance
                //    hoverPaintStyle: {fillStyle: "#434343"}, // appearance when mouse hovering on endpoint or connection
                //    anchor: ["Left", "Right"], // anchors for the endpoint
                //    edgeType: "common", // the type of edge for connections from this port type
                //    maxConnections: -1, // no limit on connections
                //    isSource: false, // indicates new connections can be dragged from this port type
                //    isTarget: true,// indicates new connections can be dragged to this port type
                //    dropOptions: {  //drop options for the port. here we attach a css class.
                //        hoverClass: "drop-hover"
                //    },
                //    allowLoopback: false,   // do not allow loopback connections from a port to itself.
                //    allowNodeLoopback: false, // do not allow connections from this port to any other port on the same node.
                //    events: {
                //        "dblclick": function () {
                //        }
                //    }
                //},
                //"column": {
                //    template: "tmplColumn",
                //    endpoint: "Blank",		// the type of the endpoint
                //    paintStyle: {fillStyle: "#f76258"},		// the endpoint's appearance
                //    hoverPaintStyle: {fillStyle: "#434343"}, // appearance when mouse hovering on endpoint or connection
                //    anchor: ["Left", "Right"], // anchors for the endpoint
                //    edgeType: "common", // the type of edge for connections from this port type
                //    maxConnections: -1, // no limit on connections
                //    isSource: true, // indicates new connections can be dragged from this port type
                //    isTarget: true,// indicates new connections can be dragged to this port type
                //    dropOptions: {  //drop options for the port. here we attach a css class.
                //        hoverClass: "drop-hover"
                //    },
                //    allowLoopback: false,   // do not allow loopback connections from a port to itself.
                //    allowNodeLoopback: false, // do not allow connections from this port to any other port on the same node.
                //    events: {
                //        "dblclick": function () {
                //        }
                //    }
                //}
            }
        },
        // Layout the nodes using a 'Spring' (force directed) layout. This is the best layout in the jsPlumbToolkit
        // for an application such as this.
        layout: {
            type: "Absolute"
            //type: "Spring",
            //parameters: {
            //    padding: [150, 150]
            //}
        },
        miniview: {
            container: d.miniviewElement
        },
        // Register for certain events from the d.renderer. Here we have subscribed to the 'nodeRendered' event,
        // which is fired each time a new node is rendered.  We attach listeners to the 'new column' button
        // in each table node.  'data' has 'node' and 'el' as properties: node is the underlying node data,
        // and el is the DOM element. We also attach listeners to all of the columns.
        // At this point we can use our underlying library to attach event listeners etc.
        events: {
            // This is called by the Toolkit when a new Port is added to a Node. In this application, that occurs
            // when the user adds a new column to a table. It is instigated by the application code preparing the
            // JS data for a new column, and then calling toolkit.portAdded(node, portData); Note that the
            // application also adds the data to the backing model itself.
            // In this application, the portElement was rendered by the 'tmplColumn' template, and it is an LI.
            // the nodeElement was rendered by 'tplNode', and it has a UL inside of it to which we want to attach
            // the column's LI.
            portAdded: function (params, port) {
                if (params.nodeEl) {
                    params.nodeEl.querySelectorAll("ul")[0].appendChild(params.portEl);
                }
            },
            portRemoved: function (params, port) {
                for (var i in params.node.data.columns) {
                    if (params.node.data.columns[i].id == params.port.id) {
                        params.node.data.columns.splice(i, 1);
                    }
                }
            },
            edgeAdded: function (params) {
            },
            canvasClick: function (e) {
                d.toolkit.clearSelection();
            },
            zoom: function (value) {
                if (typeof d.init_big_numbers_on_zoom != 'undefined' && d.init_big_numbers_on_zoom) {
                    if (value.zoom && value.zoom < 0.5) {
                        d.highlightNode();
                    } else {
                        d.removeNodesSearchHighlight();
                    }
                }
            }
        },
        dragOptions: {
            filter: "i, .jp-table-column *"
        },
        consumeRightClick: false,
        zoomToFit: true
    });


    //d.renderer.setViewportCenter(d.viewport_center);
    //
    //d.renderer.setZoom(d.zoom);

// ------------------------ drag and drop new tables/views -----------------

    //
    // Here, we are registering elements that we will want to drop onto the workspace and have
    // the toolkit recognise them as new nodes.
    //
    //  typeExtractor: this function takes an element and returns to jsPlumb the type of node represented by
    //                 that element. In this application, that information is stored in the 'jtk-node-type' attribute.
    //
    //  dataGenerator: this function takes a node type and returns some default data for that node type.
    //
    //d.renderer.registerDroppableNodes({
    //    droppables: document.querySelectorAll("#script___designer__add_node_by_drop"),
    //    dragOptions: {
    //        zIndex: 50000,
    //        cursor: "move",
    //        clone: true
    //    },
    //    typeExtractor: function (el, eventInfo, isNativeDrag, eventLocation) {
    //        return "node"
    //    },
    //    dataGenerator: function (type, draggedElement, eventInfo, eventLocation) {
    //        return {name: type};
    //    }
    //});

// ------------------------ loading  ------------------------------------
// Load the data.
    d.toolkit.load({
        data: d.data,
        onload: d.updateData()
    });


    // any operation that caused a data update (and would have caused an autosave), fires a dataUpdated event.
    d.toolkit.bind("dataUpdated", function () {
        d.updateData();
    });
};

/**
 * Node creating
 */
ScriptDesigner.prototype.createNode = function (data, callback) {
    var d = this;
    data.columns = [];
    data.id = d.incrementMaxNode().toString();
    d.loadNodeForm(data, callback);
    $("#script___node___form_modal").modal("show");
};

/**
 * Loading node from
 */
ScriptDesigner.prototype.loadNodeForm = function (data, callback) {
    var d = this;
    d.nodes_forms_submit_callbacks[data.id] = callback;
    $("#script___node___data").val(JSON.stringify(data));
    d.script___node___content_editor.setValue(data.content);
    $("#script___node___call_stage_id").val('');
    $("#script___node___is_goal").prop('checked', 0);
    $("#script___node___normal_ending").prop('checked', 0);
};

/**
 * Submiting node from
 */
ScriptDesigner.prototype.submitNodeCreatingForm = function () {
    var d = this;
    var data = JSON.parse($("#script___node___data").val());
    data.content_formatted = d.script___node___content_editor.getValue();
    data.content = data.content_formatted.replace(/<[^>]+>/gi, '');
    data.call_stage_id = $("#script___node___call_stage_id").val();
    data.is_goal = $("#script___node___is_goal").prop('checked');
    data.normal_ending = $("#script___node___normal_ending").prop('checked');
    d.nodes_forms_submit_callbacks[data.id](data);
    $("#script___node___form_modal").modal("hide");

    d.initSelectNodeSelectize('script___script___update_form_start_node_id');

    d.updateCallStages();

    d.initSearch();

    return false;
};

/**
 * Updating Edge
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.updateNode = function () {
    var d = this;

    var content_field = $("#script___node___update_form_content");
    var call_stage_id_field = $("#script___node___update_form_call_stage_id");

    var content = d.script___node___update_form_content_editor.getValue();
    var call_stage_id = call_stage_id_field.val();
    var id = $("#script___node___update_form_id").val();

    if (!content.length) {
        content_field.parent().addClass("has-error");
        content_field.siblings(".help-block").text("Заполните содержание!");
        return false;
    }

    d.nodes_forms_update_callbacks[id](content, call_stage_id);
    d.current_node_data_has_changed = false;
    $("#script___node___update_form_modal").modal("hide");

    d.initSelectNodeSelectize('script___script___update_form_start_node_id');

    d.updateCallStages();

    return false;
};


/**
 * Loading node from
 */
ScriptDesigner.prototype.loadEdgeForm = function (data, callback) {
    var d = this;
    $("#script___edge___create_form_source").val(data.id);
};

ScriptDesigner.prototype.simulateConnect = function (source, target) {
    var d = this;

    console.log('simulateConnect', source, target);


    var source_obj = $("li[data-port-id='" + source + "']");
    var target_wrapper = d.findNodeDiv(target);

    if (target_wrapper.hasClass('editor___toolbar_tools_search_highlight')) {
        target_wrapper.removeClass('editor___toolbar_tools_search_highlight');
    }

    var target_obj = target_wrapper.find('.node-content');

    var source_offset = source_obj.offset();
    var target_offset = target_obj.offset();

    var dy = target_offset.top - source_offset.top;
    var dx = target_offset.left - source_offset.left - 50;


    //console.log(dy, dx);

    //source_obj.simulate('drag-n-drop', {dx: dx, dy:dy, interpolation: {stepWidth: 10, stepDelay: 100}});

    source_obj.simulate('drag-n-drop', {dx: dx, dy: dy});
};

/**
 * Creating Edge
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.createEdge = function () {
    var d = this;

    var content_field = $("#script___edge___create_form_content");
    var target_field = $("#script___edge___create_form_target");

    var content = content_field.val();

    if (!content.length) {
        content_field.parent().addClass("has-error");
        content_field.siblings(".help-block").text("Заполните содержание!");
        return false;
    }

    var port_id = "e" + d.incrementMaxEdge().toString();
    var source = $("#script___edge___create_form_source").val();


    d.toolkit.addNewPort(source, "source", {
        id: port_id,
        content: content
    });


    $("#script___edge___create_modal").modal("hide");

    if (target_field.val()) {
        setTimeout(function () {
            d.simulateConnect(port_id, target_field.val());
        }, 10);

        //d.toolkit.connect({
        //    source: source + '.e4',
        //    target: target_field.val() + '.head',
        //});
    }

    return false;
};

/**
 * Incrementing max node
 *
 * @returns integer Current max node
 */
ScriptDesigner.prototype.incrementMaxNode = function () {
    var f = $("#script___script___update_form_script_max_node");
    var max = f.val();
    max++;
    f.val(max);

    return max;
};


/**
 * Incrementing max edge
 *
 * @returns integer Current max edge
 */
ScriptDesigner.prototype.incrementMaxEdge = function () {
    var f = $("#script___script___update_form_script_max_edge");
    var max = f.val();
    max++;
    f.val(max);
    return max;
};


/**
 * Updating Edge
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.updateEdge = function () {
    var d = this;

    var content_field = $("#script___edge___update_form_content");
    var target_field = $("#script___edge___update_form_target");

    var content = content_field.val();

    if (!content.length) {
        content_field.parent().addClass("has-error");
        content_field.siblings(".help-block").text("Заполните содержание!");
        return false;
    }

    var port_id = $("#script___edge___update_form_id").val();

    d.edges_forms_update_callbacks[port_id](content);

    $("#script___edge___update_modal").modal("hide");

    var info = d.renderer.getObjectInfo(d.findPortLi(port_id));

    var target = d.getPortTarget(info);
    var edge = d.getPortEdge(info);

    var new_target = target_field.val();

    console.log('target', target);
    console.log('new_target', new_target);

    if (new_target && !target) {
        setTimeout(function () {
            console.log('simulateConnect', port_id, new_target);

            d.simulateConnect(port_id, new_target);
        }, 10);
    } else if (!new_target && target) {
        d.toolkit.removeEdge(edge);
    } else if (new_target != target) {

        d.toolkit.removeEdge(edge);
        setTimeout(function () {
            d.simulateConnect(port_id, new_target);
        }, 10);
    }


    return false;
};

/**
 * Updating Edge
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.editEdge = function () {
    var d = this;

    var content_field = $("#script___edge___edit_form_content");
    var target_field = $("#script___edge___edit_form_target");

    var content = content_field.val();

    if (!content.length) {
        content_field.parent().addClass("has-error");
        content_field.siblings(".help-block").text("Заполните содержание!");
        return false;
    }

    var port_id = $("#script___edge___edit_form_id").val();

    var new_target = target_field.val();

    d.edges_forms_update_callbacks[port_id](content);


    d.edges_forms_edit_callbacks.push(function () {

        var info = d.renderer.getObjectInfo(d.findPortLi(port_id));

        var target = d.getPortTarget(info);

        var edge = d.getPortEdge(info);

        if (new_target && !target) {
            setTimeout(function () {
                d.simulateConnect(port_id, new_target);
            }, 10);
        } else if (!new_target && target) {
            d.toolkit.removeEdge(edge);
        } else if (new_target != target) {
            d.toolkit.removeEdge(edge);
            setTimeout(function () {
                d.simulateConnect(port_id, new_target);
            }, 10);
        }
    });

    $("#script___node___update_form_edges").show();
    $("#script___edge___add_to_form").show();
    $("#script___edge___edit_form").hide();

    d.fillAnswersTabular();

    return false;
};


/**
 * Updating script
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.updateScript = function () {
    var d = this;
    $('#script___script___update_form_is_test').val(0);
    d.updateData();

    ajaxForm("script___script___update_form", "Сохранение", function (r) {
        showMessage('success', r.message);
        if (r.creating) {
            window.location.href = "/script/script/update?id=" + r.id;
        }
    });

    return false;
};

/**
 * Updating script
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.performTest = function (start_id) {
    var d = this;
    if (!start_id || typeof start_id == 'undefined') {
        start_id = '';
    }
    $('#script___script___update_form_is_test').val(1);
    d.updateData();

    ajaxForm("script___script___update_form", "Test", function (r) {
        $("#script___designer__try_script_link").attr("href", "/script/call/test?key=" + r.key + '&start_id=' + start_id);
        $('#script___script___try_call_modal').modal('show');
        setTimeout(function () {
            $('#script___script___try_call_modal').modal('hide');
        }, 20000)
    });

    return false;
};

/**
 * Updating script
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.copyNode = function (node) {

    var d = this;

    //var data = $.extend({}, node.obj.data);

    var data = (JSON.parse(JSON.stringify(node.obj.data)));

    console.log(node.obj.data.columns);

    data.left = data.left + 50;
    data.top = data.top + 50;

    if (data.left == 0) {
        data.left = 1;
    }

    if (data.top == 0) {
        data.top = 1;
    }

    data.id = d.incrementMaxNode().toString();


    for (var i in data.columns) {
        if (typeof data.columns[i] == 'object') {
            data.columns[i].id = "e" + d.incrementMaxEdge().toString();
        }
    }


    console.log(data.columns);

    d.toolkit.addNode(data);

    return false;
};

/**
 * Undo
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.undo = function () {
    var d = this;
    return false;
};

/**
 * Updating script
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.redo = function () {
    return false;
};


/**
 * Updating script
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.updateCallStages = function () {
    var d = this;

    $(".node-call-stage").each(function () {
        var id = $(this).attr('data-id');
        if (!id) {
            $(this).html('');
        } else {
            $(this).html(d.call_stages[id]);
        }
    });
    d.renderer.refresh();
};


/**
 * Updating script
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.initWysi = function () {
    var d = this;
    this.script___node___content_editor = new wysihtml5.Editor("script___node___content", {
        toolbar: "script___node___content_toolbar",
        stylesheets: "/css/wysi.css",
        parserRules: wysihtml5ParserRules,
    });

    this.script___node___update_form_content_editor = new wysihtml5.Editor("script___node___update_form_content", {
        toolbar: "script___node___update_form_content_toolbar",
        stylesheets: "/css/wysi.css",
        parserRules: wysihtml5ParserRules
    }).on("change", function () {
            d.current_node_data_has_changed = true
        });
};