/**
 * Locator implements a {service locator}(http://en.wikipedia.org/wiki/Service_locator_pattern).
 *
 * To use Locator, you first need to register component IDs with the corresponding component
 * definitions with the locator by calling {{set()}} or {{setComponents()}}.
 * You can then call {{get()}} to retrieve a component with the specified ID. The locator will automatically
 * instantiate and configure the component according to the definition.
 *
 * For example,
 *
 * ```js
 * var locator = new YiijBaseLocator();
 * var locator.setComponents({
 *     'db' : {
 *         'constructor' : Connection,
 *         'dsn' : 'sqlite:path/to/file.db',
 *     },
 *     'cache' : {
 *         'constructor' : DbCache,
 *         'db' : 'db',
 *     },
 * });
 *
 * var db = var locator.get('db');  // or var locator.db
 * var cache = var locator.get('cache');  // or var locator.cache
 * ```
 *
 * Because {{YiijBaseModule}} extends from Locator, modules and the application are all service locators.
 *
 * @param {{}} config
 * @constructor
 * @extends YiijBaseComponent
 */
var YiijBaseLocator = function (config) {

    /**
     * @var {YiijBaseObject{}} shared component instances indexed by their IDs
     */
    this.components = {};

    /**
     * @var {{}{}}  The list of the component definitions or the loaded component instances (ID :  definition or instance
     */
    this.definitions = {};

    YiijBaseComponent.apply(this, [config]);
};

/**
 * Extend code
 * @type {YiijBaseComponent}
 */
YiijBaseLocator.prototype = Object.create(YiijBaseComponent.prototype);
YiijBaseLocator.prototype.constructor = YiijBaseComponent;

/**
 * Returns the component instance with the specified ID.
 *
 * @param {string} id component ID (e.g. `db`).
 * @return {{}} the component of the specified ID.
 * @throws Error if `id` refers to a nonexistent component ID
 * @see set()
 */
YiijBaseLocator.prototype.get = function (id) {
    if (typeof this.components[id] !== "undefined") {
        return this.components[id];
    }

    if (typeof this.definitions[id] !== "undefined") {
        var definition = this.definitions[id];

        if (this.definitions[id] instanceof Function) {
            return this.components[id] = new this.definitions[id]();
        } else {
            var config = {};

            for (var i in this.definitions[id]) {
                if (i !== 'constructor') {
                    config[i] = this.definitions[id][i];
                }
            }

            this.components[id] = Object.create(this.definitions[id]['constructor'], config);

            return this.components[id];
        }
    }
    else {
        throw new Error("Unknown component ID: " + id);
    }
};

/**
 * Registers a component definition with this locator.
 *
 * For example,
 *
 * ```js
 * // a constructor
 * locator.set('cache', FileCache);
 *
 * // a configuration object
 * locator.set('db', [
 *     'constructor' => Connection,
 *     'dsn' => 'mysql:host=127.0.0.1;dbname=demo',
 *     'username' => 'root',
 *     'password' => '',
 *     'charset' => 'utf8',
 * ]);
 * ```
 *
 * If a component definition with the same ID already exists, it will be overwritten.
 *
 * @param {string} id component ID (e.g. `db`).
 * @param {{}} definition the component definition to be registered with this locator.
 * It can be one of the following:
 *
 * - a constructor name
 * - a configuration object: the object contains name-value pairs that will be used to
 *   initialize the property values of the newly created object when [[get()]] is called.
 *   The `constructor` element is required and stands for the the constructor of the object to be created.
 *
 * @throws Error if the definition is an unknown type or invalid configuration object
 */
YiijBaseLocator.prototype.set = function (id, definition) {
    if (definition === null) {
        delete  this.components[id];
        delete this.definitions[id];
        return;
    }

    delete this.components[id];

    if (definition instanceof Function) {
        this.definitions[id] = definition;
    } else if (definition instanceof Object) {
        if (typeof definition['constructor'] !== "undefined") {
            this.definitions[id] = definition;
        } else {
            throw new Error('The configuration for the "id" component must contain a "constructor" element.');
        }
    } else {
        throw new Error('Unexpected configuration type for the "id" component: ' + typeof definition);
    }
};


/**
 * Registers a set of component definitions in this locator.
 *
 * If a component definition with the same ID already exists, it will be overwritten.
 *
 * The following is an example for registering two component definitions:
 *
 * ```js
 * {
 *     'db' : {
 *         'constructor' : Connection,
 *         'dsn' : 'sqlite:path/to/file.db',
 *     },
 *     'cache' : {
 *         'constructor' : DbCache,
 *         'db' : 'db',
 *     },
 * }
 * ```
 *
 * @param {{}} components component definitions or instances
 */
YiijBaseLocator.prototype.setComponents = function (components) {
    for (var i in components) {
        this.set(components[i]);
    }
};