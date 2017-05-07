/**
 * Модуль редактор
 *
 * http://mbostock.github.io/stack/
 *
 * @param id
 * @param parent
 * @param config
 * @constructor
 */
var Editor = function (id, parent, config) {

    /**
     * Объект с первоначальными данными скрипта, необходимыми для инициализации
     *
     * @type {{}}
     */
    this.data = null;

    /**
     * Объект содержащий списки вариантов, которые взаимодействуют с узлами (ссылаются на узлы или принадлежат узлам)
     * Содержит пары ключ - значение: ID узла - Список вариантов
     * {
     *  '1' : {'1' : Variant, '2' : Variant, '3' : Variant},
     *  '2' : ['1' : Variant, '2' : Variant, '4' : Variant],
     * }
     *
     * Служит для оптимизации рендеринга стрелок в момент перетаскивания узла:
     * Когда мы перетаскиваем узел - контроллер обращается к этому индексу и
     * дает команду на перерисовку только тем вариантам, которые взаимодействуют с узлом,
     * при этом не нужно проходить циклом всю карту узлов.
     *
     * @type {{}}
     */
    this.variant_node_relations_index = {};
    this.group_form = null;

    /**
     * @type {NodeController}
     */
    this.nodeController = null;
    this.node_form = null;

    /**
     * @type {GroupController}
     */
    this.groupController = null;

    /**
     * @type {VariantController}
     */
    this.variantController = null;
    this.variant_form = null;
    this.variant_form_embed = null;

    /**
     * @type {GroupVariantController}
     */
    this.groupvariantController = null;
    this.group_variant_form = null;
    this.group_variant_form_embed = null;

    /**
     * @type {StageController}
     */
    this.stageController = null;
    this.stages = null;

    /**
     * @type {ScriptController}
     */
    this.scriptController = null;

    /**
     * @type {NodeCloneController}
     */
    this.nodecloneController = null;

    /**
     * @type {WebSocket}
     */
    this.ws = null;

    /**
     *
     * @type {Culmann}
     */
    this.culmann = null;

    /**
     *
     * @type {Panel}
     */
    this.panel = null;

    /**
     *
     * @type {EditorSession}
     */
    this.session = null;

    /**
     *
     * @type {RelationManager}
     */
    this.relationsManager = null;

    /**
     *
     * @type {ImageExporter}
     */
    this.imageExporter = null;

    /**
     *
     * @type {Script}
     */
    this.script = null;

    /**
     *
     * @type {string}
     */
    this.options = null;

    /**
     * @type {boolean}
     */
    this.create_builds_manually = false;

    YiijBaseModule.apply(this, [id, parent, config]);
};

/**
 * Extends
 * @type {YiijBaseObject}
 */
Editor.prototype = Object.create(YiijBaseModule.prototype);
Editor.prototype.constructor = Editor;

/**
 * Стеки undo/redo проиндексированные по ID сессии, к которой они пренадлежат
 * @type {{}}
 */
Editor.prototype.sessions = {};

/**
 * @inheritdoc
 */
Editor.prototype.requiredConfig = function () {
    return ['culmann', 'data', 'ws', 'request', 'session', 'node_form'];
};

/**
 * Инициализация редактора
 */
Editor.prototype.start = function () {


    this.session = new EditorSession(this.session);
    this.ws = new CentrifugoSocket(this.ws);

    RequestQueue._csrf = this.request._csrf;

    this.stageController = new StageController({
        'stages': this.stages
    });


    this.scriptController = new ScriptController({
        'script': new Script(this.data.script),
        'script_form': new ScriptForm(this.script_form),
        'view': new ScriptView()
    });

    this.groupController = new GroupController({
        'groups': this.data.groups,
        'form': new GroupForm(this.group_form),
        'view': new GroupView()
    });

    this.nodeController = new NodeController({
        'nodes': this.data.nodes,
        'node_form': new NodeForm(this.node_form),
        'view': new NodeView()
    });

    this.variantController = new VariantController({
        'variants': this.data.variants,
        'variant_form': new VariantForm(this.variant_form),
        'variant_form_embed': new VariantFormEmbed(this.variant_form_embed),
        'variants_sortable_list': new VariantsSortableList(),
        'view': new VariantView()
    });

    this.groupvariantController = new GroupVariantController({
        'variants': this.data.group_variants,
        'form': new GroupVariantForm(this.group_variant_form),
        'variant_form_embed': new GroupVariantFormEmbed(this.group_variant_form_embed),
        'variants_sortable_list': new GroupVariantsSortableList(),
        'view': new GroupVariantView()
    });

    this.nodecloneController = new NodeCloneController({
        'view': new NodeCloneView()
    });

    this.imageExporter = new ImageExporter();

    this.messenger = new Messenger();

    this.panel = new Panel();

    this.culmann = new Culmann(this.culmann);

    this.relationsManager = new RelationManager();

    this.loadOptions();

    this.nodeController.renderNodes(true);
    this.groupController.renderGroups(true);

    this.nodeController.renderVariants();
    this.groupController.renderVariants();

    this.scriptController.renderScript();

    this.culmann.fit();

    this.applyOptions();
};

/**
 * Создает команду и выполняет ее
 *
 * @param {[]} data Данные для конструирования команды
 * @throws Error
 */
Editor.prototype.create = function (data) {
    CommandInvoker.create(this.session, CommandFactory.getInstance(data), true);

    this.panel.enableButton('undo');
    this.panel.disableButton('redo');
};

/**
 * Отмена последнего действия
 */
Editor.prototype.undo = function () {
    if (!this.canUndo()) {
        return false;
    }

    var id = this.session.undo_stack[this.session.undo_stack.length - 1].id;

    CommandInvoker.undo(this.session, id, true);

    this.panel.enableButton('redo');
};

/**
 * Повторное выполнение действия
 */
Editor.prototype.redo = function () {
    if (!this.canRedo()) {
        return false;
    }

    var id = this.session.redo_stack[this.session.redo_stack.length - 1].id;

    CommandInvoker.redo(this.session, id, true);

    this.panel.enableButton('undo');
};

/**
 * Можно ли отменить последнее действие
 * @returns {boolean}
 */
Editor.prototype.canUndo = function () {
    return CommandInvoker.canUndo(this.session);
};

/**
 * Можно ли повторить последнее действие
 * @returns {boolean}
 */
Editor.prototype.canRedo = function () {
    return CommandInvoker.canRedo(this.session);
};


/**
 * Загружает натсройки редактора
 *
 * @param {string} options
 */
Editor.prototype.loadOptions = function (options) {
    if (options) {
        this.options = options;
    } else if (this.scriptController.script.editor_options) {
        this.options = JSON.parse(this.scriptController.script.editor_options);
    } else if (!this.options) {
        this.options = {
            'arrow_style': 'Flowchart',
            'node_content_max_height': 100
        }
    }
};


/**
 * Применяет настройки редактора
 */
Editor.prototype.applyOptions = function () {
    if (!this.options) {
        return true;
    }

    this.applyNodeContentMaxHeight(this.options.node_content_max_height);
    this.applyArrowStyle(this.options.arrow_style);

    this.nodeController.renderNodes();
    this.groupController.renderGroups();
};

/**
 * Применяет настройки редактора
 */
Editor.prototype.applyNodeContentMaxHeight = function (option) {
    var style_elem = $('#node_content_max_height_style_elem');

    if (option == 0) {
        style_elem.html('<style>.node .node-content { max-height: none; }</style>')
    } else {
        style_elem.html('<style>.node .node-content { max-height: ' + option + 'px; }</style>')
    }
};

/**
 * Применяет настройки редактора
 */
Editor.prototype.applyArrowStyle = function (option) {
    switch (option) {
        case "Bezier":
            this.relationsManager.renderer.Defaults.Connector = ["Bezier", {curviness: 150}];
            break;
        case "Straight":
            this.relationsManager.renderer.Defaults.Connector = ["Straight", {gap: 0, stub: 25}];
            break;
        case "Flowchart":
            this.relationsManager.renderer.Defaults.Connector = ["Flowchart", {gap: 0, cornerRadius: 2, stub: 25}];
            break;
        default:
            throw new Error('Неизвестный стиль стрелок');
            break;
    }
};