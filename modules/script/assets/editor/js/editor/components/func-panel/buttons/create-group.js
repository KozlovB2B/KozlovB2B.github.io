/**
 * @constructor
 */
var CreateGroup = function () {
    Button.apply(this, []);
};

/**
 * Extends
 * @type {Button}
 */
CreateGroup.prototype = Object.create(Button.prototype);
CreateGroup.prototype.constructor = CreateGroup;


CreateGroup.prototype.disabled = false;
CreateGroup.prototype.text = 'Новая<br/>группа';
CreateGroup.prototype.title = 'Создать группу ответов, которую можно использовать повторно в разных узлах';

/**
 * @inheritdoc
 */
CreateGroup.prototype.getId = function () {
    return 'create_group';
};

/**
 * @inheritdoc
 */
CreateGroup.prototype.getIconClass = function () {
    return 'glyphicon-list-alt';
};

CreateGroup.prototype.afterRegister = function () {
    var button = this;

    this.getElem().draggable({
        helper: "clone",
        drag: function (event, ui) {
            var culmann = Yiij.app.getModule('editor').culmann;
            var coordinator_projection = culmann.snapToGrid(culmann.getCoordinatorProjection(ui.position));
            culmann.showNewObject(coordinator_projection, 'Новая группа');
            culmann.showRulers(coordinator_projection);
        },
        stop: function (event, ui) {
            var culmann = Yiij.app.getModule('editor').culmann;
            culmann.hideRulers();
            culmann.hideNewObject();
            var coordinator_projection = culmann.snapToGrid(culmann.getCoordinatorProjection(ui.position));
            button.createGroup(coordinator_projection.top, coordinator_projection.left);
        }
    });
};

/**
 * @inheritdoc
 */
CreateGroup.prototype.perform = function () {

    var culmann = Yiij.app.getModule('editor').culmann;

    var screen = culmann.screen();

    var coordinator_projection = culmann.snapToGrid(culmann.getCoordinatorProjection({
        top: (screen.h / 2),
        left: (screen.w / 2)
    }));

    this.createGroup(coordinator_projection.top, coordinator_projection.left);
};


/**
 * @inheritdoc
 */
CreateGroup.prototype.createGroup = function (top, left) {
    var script = Yiij.app.getModule('editor').scriptController.get();

    var group_id = UUID.generate();

    var command = {
        'model_class': 'Group',
        'model_id': group_id,
        'p': {
            'id': group_id,
            'script_id': script.id,
            'deleted_at': '',
            'top': Math.round(top),
            'left': Math.round(left),
            'name': 'Универсальные ответы'
        },
        'r': {}
    };

    command.r = JSON.parse(JSON.stringify(command.p));
    command.r['deleted_at'] = timestamp();

    var group = new Group(command.p);

    Yiij.app.getModule('editor').groupController.groups[group_id] = group;
    Yiij.app.getModule('editor').groupController.renderGroup(group);

    Yiij.app.getModule('editor').create(command);

    Yiij.app.getModule('editor').groupController.refreshGroupSelects();
    Yiij.app.getModule('editor').groupController.form.load(group);
    Yiij.app.getModule('editor').groupController.form.show();
};