Dropzone.autoDiscover = false;

/**
 * Приложение
 * @constructor
 */
var ImportForm = function (attributes) {

    var f = this;

    f.attributes = {
        'modalId': null,
        'url': null,
        'saveButton': null,
        'id': null,
        'inputName': 'file',
        'maxFilesize': 1,
        'parallelUploads': 1,
        autoProcessQueue: false,
        maxFiles: 2,
        thumbnailWidth: 200,
        thumbnailHeight: 200,
        acceptedFiles: '.scrd',
        dictDefaultMessage: 'Перетащите файл сюда или нажмите, чтобы выбрать. Максимально допустимый размер файла - 1 MB',
        dictInvalidFileType: 'Можно загружать только .scrd файлы',
        dictFileTooBig: 'Слишком большой файл!',
        dictRemoveFile: 'убрать',
        uploadMultiple: false,
        addRemoveLinks: true
    };

    jQuery.extend(f.attributes, attributes);

    if (!f.attributes.modalId) {
        throw "ID модала обязательно!";
    }
    if (!f.attributes.saveButton) {
        throw "saveButto обязательно!";
    }
    if (!f.attributes.id) {
        throw "id обязательно!";
    }

    f.init();
};

/**
 * Обработчик событий приложения
 */
ImportForm.prototype.init = function () {
    var f = this;

    // jQuery

    var myDropzone = new Dropzone("#" + f.attributes.id, {
        url: f.attributes.url,
        paramName: f.attributes.id + "[" + f.attributes.inputName + "]",
        maxFilesize: f.attributes.maxFilesize, // MB
        parallelUploads: f.attributes.parallelUploads,
        autoProcessQueue: f.attributes.autoProcessQueue,
        maxFiles: f.attributes.maxFiles,
        thumbnailWidth: f.attributes.thumbnailWidth,
        thumbnailHeight: f.attributes.thumbnailHeight,
        acceptedFiles: f.attributes.acceptedFiles,
        dictDefaultMessage: f.attributes.dictDefaultMessage,
        dictInvalidFileType: f.attributes.dictInvalidFileType,
        dictFileTooBig: f.attributes.dictFileTooBig,
        dictRemoveFile: f.attributes.dictRemoveFile,
        uploadMultiple: f.attributes.uploadMultiple,
        addRemoveLinks: f.attributes.addRemoveLinks,
        init: function () {
            var dz = this;

            dz.on("addedfile", function (file) {
                setTimeout(function () {
                    if (!file.accepted) {
                        dz.removeFile(file);
                    } else {
                        $('#' +  f.attributes.saveButton).attr('disabled', false);
                    }
                }, 1);
            });

            dz.on("reset", function () {
                $('#' + f.attributes.saveButton).attr('disabled', true);
            });

            $('body').on("click", '#' + f.attributes.saveButton, function () {
                if ($(this).attr('disabled')) {
                    return false;
                }
                
                dz.processQueue();

                $(this).attr('disabled', true);
                return false;
            });
        },
        maxfilesreached: function (file) {
            this.removeFile(file[0]);
        },
        maxfilesexceeded: function (file) {
        },
        success: function (file, res) {
            if(res.status == 'ok'){
                this.removeFile(file);
                window.location.href = res.url;
                $('#' + f.attributes.modalId).modal('hide');
                $('#' + f.attributes.saveButton).attr('disabled', true);
            }else{
                alert(res.message);
            }
        },
        error: function (file, res) {
            alert(res.message);
        }
    });
};



