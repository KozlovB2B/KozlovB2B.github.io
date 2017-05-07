/**
/**
 * Call timer
 *
 * @constructor
 */
var CallPerformTimer = function () {
    this.elapsed_seconds = 0;
    this.interval = 0;
    this.end_reason_comment_title_replacement_default = 'Comment';
    this.end_reason_comment_title_replacements = []
};



CallPerformTimer.prototype.getElapsedTimerString = function () {
    var total_seconds = this.elapsed_seconds;

    function pretty_time_string(num) {
        return ( num < 10 ? "0" : "" ) + num;
    }

    var hours = Math.floor(total_seconds / 3600);
    total_seconds = total_seconds % 3600;

    var minutes = Math.floor(total_seconds / 60);
    total_seconds = total_seconds % 60;

    var seconds = Math.floor(total_seconds);

    // Pad the minutes and seconds with leading zeros, if required
    hours = pretty_time_string(hours);
    minutes = pretty_time_string(minutes);
    seconds = pretty_time_string(seconds);

    return hours + ":" + minutes + ":" + seconds;
};

/**
 * Render current time to container
 *
 * @param element
 */
CallPerformTimer.prototype.renderTo = function (element) {
    $(element).text(this.getElapsedTimerString());
};

/**
 * Starts the timer
 */
CallPerformTimer.prototype.go = function (element) {
    var c = this;

    c.elapsed_seconds = 0;

    c.interval = setInterval(function () {
        c.elapsed_seconds++;
        c.renderTo(element);
    }, 1000);
};

/**
 * Stops the timer
 */
CallPerformTimer.prototype.stop = function () {
    clearInterval(this.interval);
};

/**
 * Designer of scripts
 *
 * @constructor
 */
var Call = function (data) {
    var c = this;

    if(data){
        c.data = data;
    }else{
        c.data = {};
    }

    console.log(c.data);

    c.history = [];
    c.pathway = [];
    c.timer = new CallPerformTimer();
    c.current_node_data;

    if (document.readyState === "complete") {
        c.init();
    }
    else {
        window.addEventListener("load", function () {
            c.init();
        });
    }
};

/**
 * Designer init
 */
Call.prototype.init = function () {
    var c = this;
    c.initEvents();
};



/**
 * Preparing data for performing call
 */
Call.prototype.prepareNodes = function () {
    var c = this;
    var data = c.data.script;
    c.data.nodes = {};
    var targets = {};

    for (var key in data.edges) {
        var value = data.edges[key];
        if (value.source) {
            var sdata = value.source.split(".");
            if (sdata[1] !== "undefined" && value.target) {
                var tdata = value.target.split(".");
                targets[sdata[1]] = tdata[0];
            }
        }
    }

    for (var key in data.nodes) {
        var value = data.nodes[key];
        if (value.id && value.id !== "undefined") {
            c.data.nodes[value.id] = {
                "id": value.id,
                "content": value.content,
                "content_formatted": value.content_formatted,
                "normal_ending": value.normal_ending ? 1 : 0,
                "edges": {}
            };

            if (value.columns) {
                for (var k in value.columns) {
                    var v = value.columns[k];
                    if (v.id && v.id !== "undefined") {
                        var edge = {
                            "id": v.id,
                            "content": v.content
                        };
                        if (targets[edge.id] !== "undefined") {
                            edge.target = targets[edge.id];
                        }

                        c.data.nodes[value.id].edges[v.id] = edge;
                    }
                }
            }
        }
    }
};



/**
 * Loading DOM containers
 */
Call.prototype.loadDomContainers = function () {
    var c = this;
    c.current_node = $("#script___call__current_node");
    c.current_node_id = $("#script___call__perform_current_node");
    c.current_edges = $("#script___call__current_edges");
    c.history_chat = $("#script___call__history");
};


/**
 * Init working events, dialogs e.t.c.
 */
Call.prototype.initEvents = function () {
    var c = this;

    /**
     *
     */
    setEvent('submit', '#script___call__perform_form', function () {
        $("#script___call__perform_form_call_history").val(JSON.stringify(c.history));
        ajaxForm("script___call__perform_form", "Сохранение", function (r) {
            showMessage('success', r.message);
            $("#script___call__perform_form_modal").modal("hide");
        });

        return false;
    });

    /**
     *
     */
    setEvent('click', '.script___call__start_up', function () {
        c.loadScriptData($(this).data('script'));
    });


    /**
     *
     */
    setEvent('click', '#script___call__perform_form_start_call_button', function () {
        c.initCall();
    });



    /**
     *
     */
    setEvent('click', '#script___call__perform_form_back_button', function () {
        c.goToPreviousNode();
    });


    /**
     *
     */
    setEvent('click', '#script___call__perform_form_end_call', function () {
        c.end();
    });


    /**
     *
     */
    setEvent('change', '#script___call__perform_reason_id', function () {
        var variant = $(this).val();
        var replacement = c.end_reason_comment_title_replacements[variant];
        if(typeof replacement == 'undefined' || !replacement){
            replacement = c.end_reason_comment_title_replacement_default;
        }

        $("#script___call__perform_form_comment_label").text(replacement);
    });



    /**
     *
     */
    setEvent('click', '.script___call__edge_button', function () {

        if ($(this).hasClass("disabled")) {
            return false;
        }

        c.history_chat.append("<div class='chat_message answer' >" + $(this).html() + "</div>");

        var target = $(this).data('target');
        var edge = $(this).data('id');
        if (target && target !== "undefined") {
            c.goToNextNode(target, edge)
        } else {
            $(this).addClass("disabled");
            $(this).siblings(".btn").addClass("disabled");
        }
    });

};


/**
 * Starts call
 */
Call.prototype.end = function () {
    var c = this;
    c.timer.stop();

    if(typeof c.current_node_data.normal_ending !== 'undefined'){
        $("#script___call__perform_form_normal_ending").val(c.current_node_data.normal_ending);
    }

    $("#script___call__perform_reason_id").trigger('change');
    $("#script___call__perform_start_screen").hide();
    $("#script___call__perform_working_area").hide();
    $("#script___call__perform_end_screen").show();
    $("#script___call__perform_form_ended_at").val(Math.round(new Date().getTime() / 1000));

};


/**
 * Starts call
 */
Call.prototype.initCall = function () {
    var c = this;


    $("#script___call__perform_start_screen").hide();
    $("#script___call__perform_end_screen").hide();
    $("#script___call__perform_working_area").show();
    $("#script___call__perform_form_script_id").val(c.script_id);
    $("#script___call__perform_form_script_version").val(c.data.version);
    $("#script___call__perform_form_start_node_id").val(c.data.start_node);
    $("#script___call__perform_form_started_at").val(Math.round(new Date().getTime() / 1000));

    c.loadDomContainers();
    c.history_chat.html("");
    c.current_node.html("");
    c.current_node_id.html("");
    c.current_edges.html("");

    c.renderNodeContent(c.data.start_node);
    c.timer.go("#script___call__perform_form_timer");
};

/**
 * Rendering node in working view
 * If node rendered - it's possible end node
 *
 * @param node_id integer Node ID
 */
Call.prototype.renderNodeContent = function (node_id) {
    var c = this;

    c.writeEndNode(node_id);

    var data = c.data.nodes[node_id];

    var content = data.content;
    if (typeof data.content_formatted !== 'undefined') {
        content = data.content_formatted;
    }

    c.current_node.html(content);
    c.current_node_id.html(node_id);
    c.current_node_data = data;
    c.current_edges.html("");

    c.history_chat.append("<div class='chat_message question' >" + content + "</div>");

    for (var key in data.edges) {
        var value = data.edges[key];
        if(typeof value.content !== "undefined"){
            c.current_edges.append("<div class='btn btn-default btn-small script___call__edge_button' data-target='" + value.target + "' data-id='" + key + "'>" + value.content + "</div>");
        }
    }
};

/**
 * Goes to the next node. Writes history
 */
Call.prototype.goToPreviousNode = function () {
    var c = this;

    if(!c.pathway.length){
        return false;
    }
    c.pathway.pop();
    if(c.pathway.length > 0){
        var node_id = c.pathway[c.pathway.length - 1];
    }else{
        var node_id = c.data.start_node;
    }


    if(!c.pathway.length){
        $('#script___call__perform_form_back_button').hide();
    }

    c.writeHistory(node_id, 0);

    c.history_chat.append("<div class='chat_message answer' >Возврат назад</div>");

    c.renderNodeContent(node_id);
};

/**
 * Goes to the next node. Writes history
 *
 * @param node_id integer Node ID
 * @param edge_id integer Edge ID (for history only)
 */
Call.prototype.goToNextNode = function (node_id, edge_id) {
    var c = this;
    c.pathway.push(node_id);
    c.writeHistory(node_id, edge_id);
    c.renderNodeContent(node_id);
    $('#script___call__perform_form_back_button').show();
};

/**
 * Writes history
 *
 * @param node_id integer Node ID
 * @param edge_id integer Edge ID (for history only)
 */
Call.prototype.writeHistory = function (node_id, edge_id) {
    var c = this;

    var rec = {
        n: node_id,
        e: edge_id,
        t: c.timer.elapsed_seconds
    };

    c.history.push(rec);
};

/**
 * Write node as end node
 *
 * @param node_id
 */
Call.prototype.writeEndNode = function (node_id) {
    $("#script___call__perform_form_end_node_id").val(node_id);
};

/**
 * Loading Script data and then start call
 *
 * @param script_id
 */
Call.prototype.loadScriptData = function (script_id) {
    var c = this;
    c.script_id = script_id;

    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: "/script/script/load-call-data?id=" + script_id,
        data: {},
        success: function (r) {
            if(r.message){
                alert(r.message);
            }else{
                $("#script___call__perform_form").trigger("reset");
                c.data = r;
                c.openModal();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {

            console.log(xhr.responseText || thrownError);
        }
    });
};

/**
 * Loading Script data and then start call
 */
Call.prototype.openModal = function () {
    var c = this;
    c.prepareNodes();

    if(!c.data.start_node){
        console.log(c.data.nodes);

        for(var i in c.data.nodes){
            c.data.start_node = c.data.nodes[i].id;
            break;
        }

        if(!c.data.start_node){
            alert("Добавьте хотя бы один узел!");
            return false;
        }
    }

    $("#script___call__perform_script_id").html(c.script_id);
    $("#script___call__perform_script_name").html(c.data.script_name);
    var data = c.data.nodes[c.data.start_node];

    var content = data.content;
    if (typeof data.content_formatted !== 'undefined') {
        content = data.content_formatted;
    }
    $("#script___call__first_node").html(content);
    $("#script___call__perform_start_screen").show();
    $("#script___call__perform_working_area").hide();
    $("#script___call__perform_end_screen").hide();
    $('#script___call__perform_form_back_button').hide();
    $("#script___call__perform_form_modal").modal("show");
};