/**
 * PromoLink constructor
 * @constructor
 */
var PromoLink = function () {
    var o = this;
    window.addEventListener("load", function () {
        o.initEvents();
    });
};

/**
 * PromoLink event handlers
 */
PromoLink.prototype.initEvents = function () {
    var o = this;

    setEvent("click", "#aff___promo_link__create_button", function () {
        ajaxModal('aff___promo_link__create_modal', $(this).attr("href"));

        return false;
    });


    setEvent("change", "#aff___promo_link__create_form_utm_medium", function () {
        if ($(this).val() == 'other') {
            $('#aff___promo_link__create_form_utm_medium_other').show();
        } else {
            $('#aff___promo_link__create_form_utm_medium_other').hide();
        }

        o.generateLink();

        return false;
    });

    setEvent("change", ".aff___promo_link__create_form_utm", function () {
        o.generateLink();
    });

    setEvent("change", "#aff___promo_link__create_form_utm_medium_other", function () {
        o.generateLink();
    });

    setEvent("submit", "#aff___promo_link__create_form", function () {
        ajaxForm("aff___promo_link__create_form", window['app']['saving_message'], function (data) {
            showMessage("success", data.message);
            $("#aff___promo_link__create_modal").modal("hide");
            $.pjax.reload({container: '#aff___promo_link__index_grid', timeout: 50000});
        });

        return false;
    });

};

/**
 *
 */
PromoLink.prototype.generateLink = function () {

    var utm_medium = $('#aff___promo_link__create_form_utm_medium').val();

    if (utm_medium == 'other') {
        utm_medium = $('#aff___promo_link__create_form_utm_medium_other').val()
    }

    var host = $('#aff___promo_link__create_form_host').val();
    var query_string = 'p=' + $('#aff___promo_link__create_form_promo_code').val();

    if (utm_medium) {
        query_string += '&utm_medium=' + utm_medium;
    }

    $('.aff___promo_link__create_form_utm').each(function () {
        var value = $(this).val();
        if (value) {
            query_string += '&' + $(this).data('utm') + '=' + value;
        }
    });
    $('#aff___promo_link__create_form_url').val(host + '?' + query_string);
    $('#aff___promo_link__create_form_query_string').val(query_string);
};
