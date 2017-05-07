/**
 * Трекер, который следит за статусами сотрудников и тем, чем они занимаются
 *
 * @param config
 * @constructor
 */
var EmployeeTracker = function (config) {
    YiijBaseComponent.apply(this, [config]);
};
/**
 * Extends
 * @type {YiijBaseObject}
 */
EmployeeTracker.prototype = Object.create(YiijBaseComponent.prototype);

EmployeeTracker.prototype.constructor = EmployeeTracker;

/**
 * Инициализация трекера
 */
EmployeeTracker.prototype.start = function () {


    Yiij.app.getModule('coordinator').on('users_list', function (e) {
        for(var i =0;i< e.data.users.length; i++){
            $("table tr[data-key='" + e.data.users[i].id + "']").attr('data-status', e.data.users[i].status);
        }

        console.log(e);

    });

    Yiij.app.getModule('coordinator').on('status_changed', function (e) {
        console.log(e);
        $("table tr[data-key='" + e.data.user + "']").attr('data-status', e.data.status);
    });

    Yiij.app.getModule('coordinator').on('connected', function () {
        Yiij.app.getModule('coordinator').requestUsersList();
    });
};