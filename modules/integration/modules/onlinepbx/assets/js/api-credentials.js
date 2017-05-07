/**
 * ApiCredentials constructor
 * @constructor
 */
var ApiCredentials = function () {
    var o = this;
    window.addEventListener("load", function () {
        o.initEvents();
    });
};

/**
 * ApiCredentials event handlers
 */
ApiCredentials.prototype.initEvents = function () {
    var o = this;


    setEvent("submit", "#integration___onlinepbx__api_credentials_create_form", function () {
        ajaxForm("integration___onlinepbx__api_credentials_create_form", window['app']['saving_message'], function (data) {
            showMessage("success", data.message);
        });

        return false;
    });

    setEvent("change", ".integration___onlinepbx__user_settings_number", function () {
        ajax('/integration/onlinepbx/user-settings/save?id=' + $(this).data('id') + '&value=' + $(this).val());

        return true;
    });

};