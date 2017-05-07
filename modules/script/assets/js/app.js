;
(function () {
    jsPlumbToolkit.ready(function () {

// ------------------------ toolkit setup ------------------------------------

        // This function is what the toolkit will use to get an ID from a node.
        var idFunction = function (n) {
            return n.id;
        };

        // This function is what the toolkit will use to get the associated type from a node.
        var typeFunction = function (n) {
            return n.type;
        };

        // get the various dom elements
        var mainElement = document.querySelector("#jtk-demo-dbase"),
            canvasElement = mainElement.querySelector(".jtk-demo-canvas"),
            miniviewElement = mainElement.querySelector(".miniview"),
            nodePalette = mainElement.querySelector(".node-palette"),
            controls = mainElement.querySelector(".controls");

        // Declare an instance of the Toolkit, and supply the functions we will use to get ids and types from nodes.
        var toolkit = jsPlumbToolkit.newInstance({
            idFunction: idFunction,
            typeFunction: typeFunction,
            nodeFactory: function (type, data, callback) {
                data.columns = [];
                jsPlumbToolkit.Dialogs.show({
                    id: "dlgName",
                    title: "Enter " + type + " name:",
                    onOK: function (d) {
                        data.name = d.name;
                        // if the user entered a name...
                        if (data.name) {
                            if (data.name.length >= 2) {
                                // generate an id: replace spaces with underscores, and make lower case
                                data.id = data.name.replace(" ", "_").toLowerCase();
                                callback(data);
                            }
                            else
                                alert(type + " names must be at least 2 characters!");
                        }
                        // else...do not proceed.
                    }
                });

            },
            edgeFactory: function (params, data, callback) {
                // you must hit the callback if you provide the edgeFactory.
                callback(data);
                // unless you want to return false, to abandon the edge
                //return false;
            },
            portFactory: function (params, data, callback) {
                // add to node. we have to do this manually. the Toolkit does not know our internal
                // data structure.
                if(data.id !== "head"){
                    params.node.data.columns.push(data);
                }

                // handoff the new column.
                callback(data);
            },
            //
            // For a given Node, return the parts of its dataset that we want to configure as Ports.
            // This is called when the data is being loaded. It is an optional argument to the newInstance
            // method.
            //
            portExtractor: function (data, node) {
                toolkit.addNewPort(data.id, "target", {id: "head"});
                return  data.columns || [];
            }
        });

        //
        // any operation that caused a data update (and would have caused an autosave), fires a dataUpdated event.
        //
        toolkit.bind("dataUpdated", function () {
            _updateDataset();
        });

// ------------------------ / toolkit setup ------------------------------------

// ------------------------- dialogs -------------------------------------

        jsPlumbToolkit.Dialogs.initialize({
            selector: ".dlg"
        });

// ------------------------- / dialogs ----------------------------------

// ------------------------- behaviour ----------------------------------

        // delete column button
        jsPlumb.on(canvasElement, "tap", ".jp-table-column-delete, .jp-table-column-delete i", function () {
            var info = renderer.getObjectInfo(this);
            jsPlumbToolkit.Dialogs.show({
                id: "dlgConfirm",
                data: {
                    msg: "Delete column '" + info.id + "'"
                },
                onOK: function (data) {
                    toolkit.removePort(info.obj.getNode(), info.id);
                }
            });
        });

        // add new column to table
        jsPlumb.on(canvasElement, "tap", ".new-column, .new-column i", function () {
            var // getObjectInfo is a helper method that retrieves the node or port associated with some
            // element in the DOM.
                info = renderer.getObjectInfo(this);

            jsPlumbToolkit.Dialogs.show({
                id: "dlgColumnEdit",
                title: "Column Details",
                onOK: function (data) {
                    // if the user supplied a column name, tell the toolkit to add a new port, providing it the
                    // id and name of the new column.  This will result in a callback to the portFactory defined above.
                    toolkit.addNewPort(info.id, "column", {
                        id: data.id
                    });
                }
            });
        });

        // delete a table or view
        jsPlumb.on(canvasElement, "tap", ".delete i, .view-delete i", function () {
            var info = renderer.getObjectInfo(this);

            jsPlumbToolkit.Dialogs.show({
                id: "dlgConfirm",
                data: {
                    msg: "Delete '" + info.id
                },
                onOK: function (data) {
                    toolkit.removeNode(info.id);
                }
            });

        });

        // edit a view's query
        jsPlumb.on(canvasElement, "tap", ".view .edit i", function () {
            var info = renderer.getObjectInfo(this);
            jsPlumbToolkit.Dialogs.show({
                id: "dlgViewQuery",
                data: info.obj.data,
                onOK: function (data) {
                    // update data, and UI (which works only if you use the Toolkit's default template engine, Rotors.
                    toolkit.updateNode(info.obj, data);
                }
            });
        });

        // change a view or table's name
        jsPlumb.on(canvasElement, "tap", ".view .name span, .jp-table .name span", function () {
            // getObjectInfo is a method that takes some DOM element (this function's `this` is
            // set to the element that fired the event) and returns the toolkit data object that
            // relates to the element.
            var info = renderer.getObjectInfo(this);
            jsPlumbToolkit.Dialogs.show({
                id: "dlgName",
                data: info.obj.data,
                title: "Edit " + info.obj.data.type + " name",
                onOK: function (data) {
                    if (data.name && data.name.length > 2) {
                        // if name is at least 2 chars long, update the underlying data and
                        // update the UI.
                        toolkit.updateNode(info.obj, data);
                    }
                }
            });
        });

        // edit a column's details
        jsPlumb.on(canvasElement, "tap", ".jp-table-column-edit i", function () {
            var info = renderer.getObjectInfo(this);
            jsPlumbToolkit.Dialogs.show({
                id: "dlgColumnEdit",
                title: "Column Details",
                data: info.obj.data,
                onOK: function (data) {
                    // if the user supplied a column name, tell the toolkit to add a new port, providing it the
                    // id and name of the new column.  This will result in a callback to the portFactory defined above.
                    if (data.id) {
                        toolkit.updatePort(info.obj, {
                            id: data.id
                        });
                    }
                }
            });
        });

// ------------------------- / behaviour ----------------------------------

// ------------------------ rendering ------------------------------------

        // Instruct the toolkit to render to the 'canvas' element. We pass in a model of nodes, edges and ports, which
        // together define the look and feel and behaviour of this renderer.  Note that we can have 0 - N renderers
        // assigned to one instance of the Toolkit..
        var renderer = toolkit.render({
            container: canvasElement,
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
                        overlays: [
                            ["Label", {
                                cssClass: "delete-relationship",
                                label: "<i class='fa fa-times'></i>",
                                events: {
                                    "tap": function (params) {
                                        toolkit.removeEdge(params.edge);
                                    }
                                }
                            }],
                            ["Arrow", {location: 1, width: 10, length: 10}],
                            ["Arrow", {location: 0.3, width: 10, length: 10}]
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
                    "source": {
                        endpoint: "Blank",
                        paintStyle: {fillStyle: "#84acb3"},
                        anchor: ["Left", "Right"],
                        edgeType: "common",
                        maxConnections: -1
                    },
                    "target": {
                        maxConnections: -1,
                        endpoint: "Blank",
                        anchor: ["Left", "Right", "Top"],
                        paintStyle: {fillStyle: "#84acb3"},
                        edgeType: "common",
                        isTarget: true
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
                    //            console.log(arguments);
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
                container: miniviewElement
            },
            // Register for certain events from the renderer. Here we have subscribed to the 'nodeRendered' event,
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
                portAdded: function (params) {
                    params.nodeEl.querySelectorAll("ul")[0].appendChild(params.portEl);
                },
                edgeAdded: function (params) {
                    // Check here that the edge was not added programmatically, ie. on load.
                    if (params.addedByMouse) {
                        _editEdge(params.edge, true);
                    }
                },
                canvasClick: function (e) {
                    toolkit.clearSelection();
                }
            },
            dragOptions: {
                filter: "i, .view .name span, .jp-table .name span, .jp-table-column *"
            },
            consumeRightClick: false,
            zoomToFit: true
        });

        // listener for mode change on renderer.
        renderer.bind("modeChanged", function (mode) {
            jsPlumb.removeClass(controls.querySelectorAll("[mode]"), "selected-mode");
            jsPlumb.addClass(controls.querySelectorAll("[mode='" + mode + "']"), "selected-mode");
        });

        // pan mode/select mode
        jsPlumb.on(controls, "tap", "[mode]", function () {
            renderer.setMode(this.getAttribute("mode"));
        });

        // on home button click, zoom content to fit.
        jsPlumb.on(controls, "tap", "[reset]", function () {
            toolkit.clearSelection();
            renderer.zoomToFit();
        });

// ------------------------ / rendering ------------------------------------

        var _syntaxHighlight = function (json) {
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return "<pre>" + json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                var cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            }) + "</pre>";
        };


        var datasetContainer = document.querySelector(".jtk-demo-dataset");
        var _updateDataset = function () {
            datasetContainer.innerHTML = _syntaxHighlight(JSON.stringify(toolkit.exportData(), null, 4));
        };

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
        renderer.registerDroppableNodes({
            droppables: nodePalette.querySelectorAll("li"),
            dragOptions: {
                zIndex: 50000,
                cursor: "move",
                clone: true
            },
            typeExtractor: function (el, eventInfo, isNativeDrag, eventLocation) {
                return el.getAttribute("jtk-node-type");
            },
            dataGenerator: function (type, draggedElement, eventInfo, eventLocation) {
                return {name: type};
            }
        });

// ------------------------ / drag and drop new tables/views -----------------

// ------------------------ loading  ------------------------------------
// Load the data.
        toolkit.load({
            url: "/schema-1.json",
            onload: _updateDataset
        });


// ------------------------ /loading  ------------------------------------

    });
})();