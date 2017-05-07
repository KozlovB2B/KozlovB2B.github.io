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

    setEvent("click", "#integration___amo__api_credentials_create_button", function () {
        o.popCreateModal();

        return false;
    });

    setEvent("submit", "#integration___amo__api_credentials_create_form", function () {
        ajaxForm("integration___amo__api_credentials_create_form", window['app']['saving_message'], function (data) {
            showMessage("success", data.message);
            $("#integration___amo__api_credentials_create_modal").modal("hide");
            $.pjax.reload({container: '#integration___amo__api_credentials_index_grid', timeout: 50000});
        });

        return false;
    });

};


/**
 * ApiCredentials event handlers
 */
ApiCredentials.prototype.popCreateModal = function () {
    ajaxModal('integration___amo__api_credentials_create_modal', $('#integration___amo__api_credentials_create_button').attr('href'));
};