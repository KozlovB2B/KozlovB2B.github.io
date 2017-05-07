/**
 * Контроллер группы
 *
 * @param config
 * @constructor
 */
var GroupController = function (config) {
    YiijBaseController.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
GroupController.prototype = Object.create(YiijBaseController.prototype);
GroupController.prototype.constructor = GroupController;

/**
 * Объект, отвечающий за представление групп
 *
 * @type {GroupView}
 */
GroupController.prototype.view;

/**
 * @type {Group[]}
 */
GroupController.prototype.groups;

/**
 * @type {Group[]}
 */
GroupController.prototype.groups_list;

/**
 * @type {GroupForm}
 */
GroupController.prototype.form;

/**
 * @inheritdoc
 */
GroupController.prototype.requiredConfig = function () {
    return ['view', 'groups'];
};


/**
 * Инициализация контроллера групп
 */
GroupController.prototype.init = function () {
    Yiij.trace('Инициализация контроллера групп');

    this.loadGroupsMap();

    this.setEvents();
};


/**
 * Загружает карту групп из данных скрипта
 */
GroupController.prototype.loadGroupsMap = function () {
    Yiij.trace('Загружаю карту групп...');

    var groups = {};

    for (var i in this.groups) {
        groups[this.groups[i].id] = new Group(this.groups[i]);
    }

    this.groups = groups;

    this.reloadGroupsList();
};

/**
 *
 */
GroupController.prototype.reloadGroupsList = function () {
    Yiij.trace('Обновляю список групп...');

    this.groups_list = [];

    for (var i in this.groups) {
        if (!this.groups[i].deleted_at) {
            this.groups_list.push({
                'id' : this.groups[i].id,
                'name' : this.groups[i].name
            });
        }
    }
};


/**
 *
 */
GroupController.prototype.refreshGroupSelects = function () {

    this.reloadGroupsList();

    for (var select in GroupSelects) {

        //var value = GroupSelects['node-groups'].selectize.getValue();
        //var value = GroupSelects[select].selectize.getValue().split(',');

        for (var i in GroupSelects[select].selectize.options) {

            var option = GroupSelects[select].selectize.options[i];

            var option_exist = false;

            for (var j = 0; j < this.groups_list.length; j++) {
                if (this.groups_list[j].id == option.id) {
                    option_exist = true;
                    break;
                }
            }

            if (!option_exist) {
                GroupSelects[select].selectize.removeOption(option.id);
            }

        }

        for (var j = 0; j < this.groups_list.length; j++) {
            GroupSelects[select].selectize.addOption(this.groups_list[j]);
            GroupSelects[select].selectize.updateOption(this.groups_list[j].id, this.groups_list[j]);
        }

        GroupSelects[select].selectize.refreshOptions(false);
    }
};

/**
 * Найти или создать группу
 *
 * @param id
 * @param {{}} data
 * @returns {Group}
 */
GroupController.prototype.getOrCreate = function (id, data) {
    Yiij.trace('Найти или создать группа ' + id + '.');

    if (typeof this.groups[id] === 'undefined') {
        this.groups[id] = new Group(data);
        this.renderGroup(this.groups[id]);
    }

    return this.groups[id];
};

/**
 * Получение объекта группы
 *
 * @param id
 * @returns {Group}
 */
GroupController.prototype.get = function (id) {
    Yiij.trace('Получаю данные по группе ' + id + '.');

    if (typeof this.groups[id] === 'undefined') {
        throw new Error('Группа с ID' + id + ' не найдена!');
    }

    return this.groups[id];
};

/**
 * Перерисовка связей для узла
 * @param id
 */
GroupController.prototype.repaintGroupRelations = function (id) {
    Yiij.trace('Обновляю представления связей для группы ' + id);

    var node = this.get(id);
    var elem = this.view.get(id);

    Yiij.app.getModule('editor').relationsManager.repaint(elem, {
        top: parseInt(node.top),
        left: parseInt(node.left)
    });
};


/**
 * Отображение всех групп
 */
GroupController.prototype.renderVariants = function () {
    Yiij.trace('Рисую все варианты группы...');

    for (var i in this.groups) {
        this.view.renderVariants(this.groups[i]);
    }
};
/**
 * Отображение всех групп
 */
GroupController.prototype.renderGroups = function (withoutVariants) {
    Yiij.trace('Рисую все группы...');

    for (var i in this.groups) {
        this.renderGroup(this.groups[i], withoutVariants);
    }
};

/**
 * Отображение группы
 *
 * @param {Group} n
 */
GroupController.prototype.renderGroup = function (n, withoutVariants) {
    Yiij.trace('Рисую группа ' + n.id);

    if (!this.view.get(n.id)) {
        this.view.create(n);
    }

    this.view.applyChanges(n, withoutVariants);
};


/**
 * Отображение группы
 *
 * @param {Group} n
 */
GroupController.prototype.setEvents = function (n) {
    var nc = this;

    $("body").on('dragstop', '.' + this.view.css_class, function (e) {
        var group = nc.get($(this).data('id'));
        var current_top = parseInt($(this).css('top'));
        var current_left = parseInt($(this).css('left'));

        if (group.top != current_top || group.left != current_left) {
            Yiij.app.getModule('editor').create({
                'model_class': 'Group',
                'model_id': group.id,
                'r': {
                    'top': group.top,
                    'left': group.left
                },
                'p': {
                    'top': current_top,
                    'left': current_left
                }
            });
        }
    });

    $("body").on('click', '.group-edit-button', function () {
        var group = nc.get($(this).closest('.group').attr('id'));
        nc.form.load(group);
        nc.form.show();
    });

    $("body").on('click', '.group-delete-button', function (e) {
        var group = nc.get($(this).closest('.group').data('id'));

        Yiij.app.getModule('editor').create({
            'model_class': 'Group',
            'model_id': group.id,
            'r': {
                'deleted_at': null
            },
            'p': {
                'deleted_at': Math.round((new Date()).getTime() / 1000)
            }
        });
    });
};

