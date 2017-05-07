/**
 * Designer of scripts
 *
 * @constructor
 */
var ScriptDesigner = function (id, data, call_stages) {
    var d = this;
    d.id = id;
    d.call_stages = call_stages;
    data.ports = [];

    d.edges_map = {};

    for (var i in data.edges) {
        //console.log(data.edges[i]);
        var edge_id = data.edges[i].source + '-' + data.edges[i].target;
        if (typeof  data.edges[i] !== 'function') {
            d.edges_map[edge_id] = data.edges[i];
        }
    }

    d.data = data;

    d.data.edges = [];

    console.log(d.edges_map);

    for (var e in d.edges_map) {
        d.data.edges.push(d.edges_map[e]);
    }

    console.log(d.data);
    //return;
    d.nodes_forms_submit_callbacks = {};
    d.nodes_forms_update_callbacks = {};
    d.edges_forms_update_callbacks = {};


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
    var toolbarElement = document.querySelector("#editor___toolbar_functions");
    toolbarElement.style.width = (w - 300) + "px"; // 300 это лого + функции пользователя
    var canvasElement = document.querySelector("#script___designer__canvas");
    canvasElement.style.height = (h - canvasElement.offsetTop) + "px";

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
 * Loading DOM containers
 */
ScriptDesigner.prototype.initSearch = function () {
    var d = this,
        input = $('#editor___toolbar_tools_search_input');

    input.typeahead('destroy');

    d.initSearchDataProvider();

    input.typeahead(null, {
        name: 'data_source',
        displayKey: 'content',
        source: d.search_data_provider.ttAdapter(),
        limit: 10,
        templates: {
            suggestion: function (item) {
                return '<div class="editor___toolbar_tools_search_input_suggestion"><strong>#' + item.id + '</strong> ' + item.content + '</div>'
            }
        }
    }).on('typeahead:selected', function (event, data) {
        $(".jtk-node").removeClass('editor___toolbar_tools_search_highlight');
        if (typeof data.id !== 'undefined') {
            d.editNode(d.renderer.getObjectInfo($("div[data-jtk-node-id='" + data.id + "']")));
        }
    }).on('typeahead:cursorchange', function (event, data) {
        $(".jtk-node").removeClass('editor___toolbar_tools_search_highlight');

        if (typeof data.id !== 'undefined') {
            $("div[data-jtk-node-id='" + data.id + "']").addClass('editor___toolbar_tools_search_highlight');
        }
    }).on('typeahead:close', function (event, data) {
        $(".jtk-node").removeClass('editor___toolbar_tools_search_highlight');
    });
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

    d.nodes_forms_update_callbacks[info.obj.id] = function (content, call_stage_id) {

        if (typeof call_stage_id == "undefined" || !call_stage_id) {
            call_stage_id = '';
        }

        d.toolkit.updateNode(info.obj, {
            content: content.replace(/<[^>]+>/gi, ''),
            content_formatted: content,
            call_stage_id: call_stage_id,
            is_goal: $("#script___node___update_form_is_goal").prop('checked'),
            normal_ending: $("#script___node___update_form_normal_ending").prop('checked')
        });

        d.initSearch();
    };

    $("#script___node___update_form_modal").modal("show");

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
    setEvent('click', '#script___designer__undo', function () {
        d.undo();
        return false;
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
    setEvent('submit', '#script___node___create_form', function () {
        d.submitNodeCreatingForm();
        return false;
    });

    setEvent('click', '.jp-table-edit', function () {
        d.editNode(d.renderer.getObjectInfo(this));
        return false;
    });

    setEvent('click', '.jp-table-try-call', function () {
        d.performTest(d.renderer.getObjectInfo(this).id);
        return false;
    });

    setEvent('click', '.script___edge__add_button', function () {
        var info = d.renderer.getObjectInfo(this);
        clearFormErrors("script___edge___create_form");
        $("#script___edge___create_form_source").val(info.id);
        $("#script___edge___create_form_content").val(null);
        $("#script___edge___create_modal").modal("show");
    });

    setEvent('click', '.jp-table-column-edit', function () {
        var info = d.renderer.getObjectInfo(this);
        clearFormErrors("script___edge___update_form");
        $("#script___edge___update_form_id").val(info.obj.data.id);
        $("#script___edge___update_form_content").val(info.obj.data.content);

        d.edges_forms_update_callbacks[info.obj.id] = function (content) {
            d.toolkit.updatePort(info.obj, {
                content: content
            });
        };

        $("#script___edge___update_modal").modal("show");
    });

    setEvent('click', '.jp-table-column-delete', function () {
        var info = d.renderer.getObjectInfo(this);
        if (confirm("Вы действительно хотите удалить ответ?")) {
            d.toolkit.removePort(info.obj.getNode(), info.id);
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

    //for(var i in d.history){
    //    d.history[i].current = false;
    //}
    //
    //d.history.push(data);
    //
    //d.history[d.history.length - 1].current = true;
    //
    //console.log(d.history);


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
                //            console.log(arguments);
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
                // Check here that the edge was not added programmatically, ie. on load.
                if (params.addedByMouse) {
                    _editEdge(params.edge, true);
                }
            },
            canvasClick: function (e) {
                d.toolkit.clearSelection();
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
    d.renderer.registerDroppableNodes({
        droppables: [document.querySelector("#script___designer__add_node_by_drop")],
        dragOptions: {
            zIndex: 50000,
            cursor: "move",
            clone: true
        },
        typeExtractor: function (el, eventInfo, isNativeDrag, eventLocation) {
            return "node"
        },
        dataGenerator: function (type, draggedElement, eventInfo, eventLocation) {
            return {name: type};
        }
    });

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

    d.startNodeSelect.append("<option value='" + data.id + "'>#" + data.id + ' ' + data.content.substr(0, 30) + "</option>");

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

    $("#script___node___update_form_modal").modal("hide");

    d.startNodeSelect.find("option").each(function () {
        if ($(this).val() == id) {
            $(this).text('#' + id + ' ' + content.replace(/<[^>]+>/gi, '').substr(0, 30))
        }
    });

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

/**
 * Creating Edge
 *
 * @returns {boolean}
 */
ScriptDesigner.prototype.createEdge = function () {
    var d = this;

    var content_field = $("#script___edge___create_form_content");

    var content = content_field.val();

    if (!content.length) {
        content_field.parent().addClass("has-error");
        content_field.siblings(".help-block").text("Заполните содержание!");
        return false;
    }

    d.toolkit.addNewPort($("#script___edge___create_form_source").val(), "source", {
        id: "e" + d.incrementMaxEdge().toString(),
        content: content
    });

    $("#script___edge___create_modal").modal("hide");

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

    var content = content_field.val();

    if (!content.length) {
        content_field.parent().addClass("has-error");
        content_field.siblings(".help-block").text("Заполните содержание!");
        return false;
    }

    d.edges_forms_update_callbacks[$("#script___edge___update_form_id").val()](content);

    $("#script___edge___update_modal").modal("hide");

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
    this.script___node___content_editor = new wysihtml5.Editor("script___node___content", {
        toolbar: "script___node___content_toolbar",
        stylesheets: "/css/wysi.css",
        parserRules: wysihtml5ParserRules
    });
    this.script___node___update_form_content_editor = new wysihtml5.Editor("script___node___update_form_content", {
        toolbar: "script___node___update_form_content_toolbar",
        stylesheets: "/css/wysi.css",
        parserRules: wysihtml5ParserRules
    });
};