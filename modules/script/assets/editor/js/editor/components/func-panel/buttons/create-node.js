/**
 * @constructor
 */
var CreateNode = function () {
    Button.apply(this, []);
};

/**
 * Extends
 * @type {Button}
 */
CreateNode.prototype = Object.create(Button.prototype);
CreateNode.prototype.constructor = CreateNode;


CreateNode.prototype.disabled = false;
CreateNode.prototype.text = 'Новый<br/>узел';
CreateNode.prototype.title = 'Вы можете перетащить эту кнопку в рабочую область - тогда узел появится там, куда вы перетащили кнопку.';

/**
 * @inheritdoc
 */
CreateNode.prototype.getId = function () {
    return 'create_node';
};

/**
 * @inheritdoc
 */
CreateNode.prototype.getIconClass = function () {
    return 'glyphicon-file';
};

CreateNode.prototype.afterRegister = function () {
    var button = this;

    this.getElem().draggable({
        helper: "clone",
        drag: function (event, ui) {
            var culmann = Yiij.app.getModule('editor').culmann;
            var coordinator_projection = culmann.snapToGrid(culmann.getCoordinatorProjection(ui.position));
            culmann.showNewObject(coordinator_projection, 'Новый узел');
            culmann.showRulers(coordinator_projection);
        },
        stop: function (event, ui) {
            var culmann = Yiij.app.getModule('editor').culmann;
            culmann.hideRulers();
            culmann.hideNewObject();
            var coordinator_projection = culmann.snapToGrid(culmann.getCoordinatorProjection(ui.position));
            button.createNode(coordinator_projection.top, coordinator_projection.left);
        }
    });
};

/**
 * @inheritdoc
 */
CreateNode.prototype.perform = function () {
    var culmann = Yiij.app.getModule('editor').culmann;

    var screen = culmann.screen();

    var coordinator_projection = culmann.snapToGrid(culmann.getCoordinatorProjection({
        top: (screen.h / 2),
        left: (screen.w / 2)
    }));

    this.createNode(coordinator_projection.top, coordinator_projection.left);
};


/**
 * @inheritdoc
 */
CreateNode.prototype.createNode = function (top, left) {

    var script = Yiij.app.getModule('editor').scriptController.get();
    var node_id = UUID.generate();

    var command = {
        'model_class': 'Node',
        'model_id': node_id,
        'p': {
            'id': node_id,
            'script_id': script.id,
            'deleted_at': '',
            'top': Math.round(top),
            'left': Math.round(left),
            'content': 'Новый узел'
        },
        'r': {}
    };

    Yiij.trace('Получение номера для нового узла...');

    Yiij.app.getModule('editor').messenger.overlay("Получение номера для нового узла");

    $.get({
        'url': '/script/editor/node-number?script_id=' + script.id,
        'success': function (res) {
            Yiij.app.getModule('editor').messenger.hideOverlay();

            Yiij.trace('Номер нового узла: ' + res);

            command.r = JSON.parse(JSON.stringify(command.p));
            command.r['deleted_at'] = timestamp();
            command.p['number'] = res;
            command.r['number'] = res;
            var node = new Node(command.p);
            Yiij.app.getModule('editor').nodeController.nodes[node_id] = node;
            Yiij.app.getModule('editor').nodeController.renderNode(node);
            Yiij.app.getModule('editor').create(command);
            Yiij.app.getModule('editor').nodeController.refreshNodeSelects();
            Yiij.app.getModule('editor').nodeController.node_form.load(node);
            Yiij.app.getModule('editor').nodeController.node_form.show();
        },
        error: function (jqXHR) {
            Yiij.trace('Произошла ошибка!');
            Yiij.app.getModule('editor').messenger.overlay("<span class='text-danger'>Произошла ошибка:</span><br/>" + jqXHR.responseJSON.message + "<br/><br/><br/> <span class='text-danger'>Пришлите текст ошибки на support@scriptdesigner.ru с указанием своего логина и номера скрипта</span>");
        }
    });
};