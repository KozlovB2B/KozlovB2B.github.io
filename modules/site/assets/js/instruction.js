/**
 * Instruction constructor
 * @constructor
 */

var Instruction = function () {
    var o = this;
    window.addEventListener("load", function () {
        o.initEvents();
    });
};

/**
 * Instruction event handlers
 */
Instruction.prototype.initEvents = function () {

    setEvent("click", ".site___instruction__delete", function () {

        if(!confirm("Вы уверены, что хотите удалить инструкцию?")){
            return false;
        }

        var url = $(this).attr("href");
        ajax(url, null, function (r) {
            showMessage("success", r.message);
            $.pjax.reload({container: '#site___instruction__manage_grid'});
        });

        return false;
    });


    setEvent("click", ".site___instruction__update", function () {
        ajaxModal('site___instruction__update_modal', $(this).attr("href"));

        return false;
    });

    setEvent("click", "#site___instruction__create", function () {
        ajaxModal('site___instruction__create_modal', "/site/instruction/create");
    });

    setEvent("submit", "#site___instruction__update_form", function () {
        ajaxForm("site___instruction__update_form", "Сохранение", function (data) {
            showMessage("success", data.message);
            $("#site___instruction__update_modal").modal("hide");
            $.pjax.reload({container: '#site___instruction__manage_grid', timeout : 50000});
        });

        return false;
    });

    setEvent("submit", "#site___instruction__create_form", function () {
        ajaxForm("site___instruction__create_form", "Сохранение", function (data) {
            showMessage("success", data.message);
            $("#site___instruction__create_modal").modal("hide");
            $.pjax.reload({container: '#site___instruction__manage_grid', timeout : 50000});
        });

        return false;
    });

};
