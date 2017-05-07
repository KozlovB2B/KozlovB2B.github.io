/**
 * Base Module
 *
 * @param {string} id
 * @param {YiijBaseModule} parent
 * @param config
 * @constructor
 * @extends YiijBaseLocator
 */
var YiijBaseModule = function (id, parent, config) {

    this.id = id;

    this.module = parent;

    YiijBaseLocator.apply(this, [config]);
};

/**
 * Extend code
 * @type {YiijBaseLocator}
 */
YiijBaseModule.prototype = Object.create(YiijBaseLocator.prototype);
YiijBaseModule.prototype.constructor = YiijBaseModule;


/**
 * @event ActionEvent an event raised before executing a controller action.
 * You may set [[ActionEvent.isValid]] to be `false` to cancel the action execution.
 */
YiijBaseModule.prototype.EVENT_BEFORE_ACTION = 'beforeAction';
/**
 * @event ActionEvent an event raised after executing a controller action.
 */
YiijBaseModule.prototype.EVENT_AFTER_ACTION = 'afterAction';

/**
 * @var array custom module parameters (name : value).
 */
YiijBaseModule.prototype.params = {};

/**
 * @var string an ID that uniquely identifies this module among other modules which have the same [[module|parent]].
 */
YiijBaseModule.prototype.id;

/**
 * @var Module the parent module of this module. `null` if this module does not have a parent.
 */
YiijBaseModule.prototype.module;

/**
 * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
 * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
 * will be taken. If this is `false`, layout will be disabled within this module.
 */
YiijBaseModule.prototype.layout;

/**
 * @var array mapping from controller ID to controller configurations.
 * Each name-value pair specifies the configuration of a single controller.
 * A controller configuration can be either a string or an array.
 * If the former, the string should be the fully qualified constructor name of the controller.
 * If the latter, the array must contain a `constructor` element which specifies
 * the controller's fully qualified constructor name, and the rest of the name-value pairs
 * in the array are used to initialize the corresponding controller properties. For example,
 *
 * ```js
 * {
 *   'account' : UserController,
 *   'article' : {
 *      'constructor' : PostController,
 *      'pageTitle' : 'something new',
 *   },
 * }
 * ```
 */
YiijBaseModule.prototype.controllers = {};

/**
 * @var string the default route of this module. Defaults to `default`.
 * The route may consist of child module ID, controller ID, and/or action ID.
 * For example, `help`, `post/create`, `admin/post/create`.
 * If action ID is not given, it will take the default value as specified in
 * [[Controller.defaultAction]].
 */
YiijBaseModule.prototype.defaultRoute = 'default';

/**
 * @var {string} the base url where to load templates files for this module
 */
YiijBaseModule.prototype.templatesUrl = '';

/**
 * @var string the url where to load layout template
 */
YiijBaseModule.prototype.layoutUrl = '';

/**
 * @var array child modules of this module
 */
YiijBaseModule.prototype.modules = {};


/**
 * Returns the currently requested instance of this module constructor.
 * If the module constructor is not currently requested, `null` will be returned.
 * This method is provided so that you access the module instance from anywhere within the module.
 * @return|null the currently requested instance of this module constructor, or `null` if the module constructor is not requested.
 */
YiijBaseModule.prototype.getInstance = function () {
    return typeof Yiij.app.loadedModules[this.shortName()] ? Yiij.app.loadedModules[this.shortName()] : null;
};

/**
 * Retrieves the child module of the specified ID.
 * This method supports retrieving both child modules and grand child modules.
 * @param {string} id module ID (case-sensitive). To retrieve grand child modules,
 * use ID path relative to this module (e.g. `admin/content`).
 * @return Module|null the module instance, `null` if the module does not exist.
 * @see hasModule()
 */
YiijBaseModule.prototype.getModule = function (id) {

    if (this.modules[id] instanceof YiijBaseModule) {
        return this.modules[id];
    }
    else {
        Yiij.trace("Loading module: " + id);

        if (this.modules[id] instanceof Function) {
            return this.modules[id] = new this.modules[id]();
        } else if (this.modules[id] instanceof Object) {
            var config = {};

            for (var i in this.modules[id]) {
                if (i !== 'constructor') {
                    config[i] = this.modules[id][i];
                }
            }

            this.modules[id] = new this.modules[id]['constructor'](id, this, config);

            Yiij.app.loadedModules[id] = this.modules[id];

            return this.modules[id];
        } else {
            throw new Error("Unknown module ID: " + id);
        }
    }
};

/**
 * Adds a sub-module to this module.
 * @param {string} id module ID.
 * @param {YiijBaseModule|{}|null} module the sub-module to be added to this module. This can
 * be one of the following:
 *
 * - a [[YiijBaseModule]] object
 * - a configuration object: when [[getModule()]] is called initially, the array
 *   will be used to instantiate the sub-module
 * - `null`: the named sub-module will be removed from this module
 */
YiijBaseModule.prototype.setModule = function (id, module) {
    if (module === null) {
        delete this.modules[id];
    } else {
        this.modules[id] = module;
    }
};

/**
 * Registers sub-modules in the current module.
 *
 * Each sub-module should be specified as a name-value pair, where
 * name refers to the ID of the module and value the module or a configuration
 * array that can be used to create the module. In the latter case, [[Yiij.createObject()]]
 * will be used to create the module.
 *
 * If a new sub-module has the same ID as an existing one, the existing one will be overwritten silently.
 *
 * The following is an example for registering two sub-modules:
 *
 * ```js
 * {
 *     'comment' : {
 *         'constructor' : CommentModule,
 *         'db' : 'db',
 *     },
 *     'booking' : {'constructor' : BookingModule},
 * }
 * ```
 *
 * @param {{}} modules modules (id : module configuration or instances).
 */
YiijBaseModule.prototype.setModules = function (modules) {
    for (var i in modules) {
        this.modules[i] = modules[i];
    }
};