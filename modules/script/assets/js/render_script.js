/**
 * Designer of scripts
 *
 * @constructor
 */
var ScriptRenderer = function (id, data, call_stages) {
    var d = this;
    d.id = id;
    d.call_stages = call_stages;
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
ScriptRenderer.prototype.init = function () {
    this.loadDomContainers();
    this.initToolkit();
    this.updateCallStages();
    $('.jtk-surface-pan').remove();
    $('.delete-relationship').remove();
};

/**
 * Loading DOM containers
 */
ScriptRenderer.prototype.loadDomContainers = function () {
    this.mainElement = document.querySelector("#script___designer__main_container");
    this.canvasElement = this.mainElement.querySelector("#script___designer__canvas");
    this.miniviewElement = this.mainElement.querySelector("#script___designer__miniview");
    this.startNodeSelect = $("#script___script___update_form_start_node_id");
};


/**
 * Init toolkit
 */
ScriptRenderer.prototype.initToolkit = function () {
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
            }
        },
        layout: {
            type: "Absolute"
        },
        events: {
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

    d.toolkit.load({
        data: d.data
    });
};


/**
 * Updating script
 *
 * @returns {boolean}
 */
ScriptRenderer.prototype.updateCallStages = function () {
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