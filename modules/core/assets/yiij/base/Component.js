/**
 * Base component
 *
 * @param config
 * @constructor
 * @extends YiijBaseObject
 */
var YiijBaseComponent = function (config) {
    YiijBaseObject.apply(this, [config]);
};

/**
 * Extend code
 * @type {YiijBaseObject}
 */
YiijBaseComponent.prototype = Object.create(YiijBaseObject.prototype);
YiijBaseComponent.prototype.constructor = YiijBaseComponent;

/**
 *
 * @type {Array}
 */
YiijBaseComponent.prototype.events = [];


/**
 * Attaches an event handler to an event.
 *
 * The event handler must be a valid callback. The following are
 * some examples:
 *
 * ```
 * function (event) { ... }  // anonymous function
 * ```
 *
 * The event handler must be defined with the following signature,
 *
 * ```
 * function (event)
 * ```
 *
 * where `event` is an [[Event]] object which includes parameters associated with the event.
 *
 * @param {string} name the event name
 * @param {string|function} [selector] handler selector (if you want to detach it using .off()) if you
 * give it as function - it will be used as handler.
 * @param {function} handler the event handler
 * @see off()
 */
YiijBaseComponent.prototype.on = function (name, selector, handler) {

    var selector_to_attach = null;
    var handler_to_attach = null;
    if (typeof name != 'string' || !name.length) {
        throw 'Param name must be a string with non zero length';
    }

    if (typeof selector == 'function') {
        selector_to_attach = null;
        handler_to_attach = selector;
    } else {
        selector_to_attach = selector;
        handler_to_attach = handler;
    }

    if (typeof handler_to_attach != 'function') {
        throw 'Handler must be a function';
    }


    if (!this.events[name]) {
        this.events[name] = [];
    }

    this.events[name].push({
        'handler': handler_to_attach,
        'selector': selector_to_attach
    });
};

/**
 * Detaches an existing event handler from this component.
 * This method is the opposite of [[on()]].
 * @param {string} name event name
 * @param {function} selector the event handler selector to be removed.
 * If no selector given - all handlers attached to the named event will be removed.
 *
 * @see on()
 */
YiijBaseComponent.prototype.off = function (name, selector) {
    if (typeof name != 'string' || !name.length) {
        throw 'Param name must be a string with non zero length';
    }

    if (!this.events[name] || !this.events[name].length) {
        return;
    }

    if (!selector) {
        delete this.events[name];
    } else {
        var i = this.events[name].length;

        while (i--)
            if (this.events[name][i].selector == selector)
                this.events[name].splice(i, 1);
    }
};


/**
 * Triggers an event.
 * This method represents the happening of an event. It invokes
 * all attached handlers for the event including class-level handlers.
 * @param {string} name the event name
 * @param {YiijBaseEvent} event the event parameter. If not set, a default [[YiijBaseEvent]] object will be created.
 */
YiijBaseComponent.prototype.trigger = function (name, event) {
    if (typeof name != 'string' || !name.length) {
        throw 'Param name must be a string with non zero length';
    }

    if (!this.events[name] || !this.events[name].length) {
        return;
    }


    if(!event){
        event = new YiijBaseEvent();
    }

    event.name = name;

    var events_count = this.events[name].length;

    for (var i = 0; i < events_count; i++) {
        this.events[name][i].handler.call(null, event);

        if (event.handled) {
            break;
        }
    }
};