Dropzone.options.ChangeAvatarForm = {
    paramName: "ChangeAvatarForm[file]",
    maxFilesize: 1, // MB
    parallelUploads: 1,
    autoProcessQueue: false,
    maxFiles: 2,
    thumbnailWidth: 200,
    thumbnailHeight: 200,
    acceptedFiles: 'image/jpeg, image/jpg, image/png',
    dictDefaultMessage: 'Перетащите файл сюда или нажмите, чтобы выбрать изображение. Максимально допустимый размер файла - 1 MB',
    dictInvalidFileType: 'Можно загружать только .jpg или .png изображение',
    dictFileTooBig: 'Слишком большая картинка.',
    dictRemoveFile: 'убрать',
    uploadMultiple: false,
    addRemoveLinks: true,
    init: function () {
        var dz = this;

        dz.on("addedfile", function (file) {
            setTimeout(function () {
                if (!file.accepted) {
                    dz.removeFile(file);
                } else {
                    $('#user___user__upload_avatar_button').attr('disabled', false);
                }
            }, 1);
        });

        dz.on("reset", function () {
            $('#user___user__upload_avatar_button').attr('disabled', true);
        });

        on("click", "#user___user__upload_avatar_button", function () {
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
        this.removeFile(file);
        $('#user___user__avatar_img').attr('src', res.data.url);
        $('#user___user__avatar_mini_img').attr('src', res.data.url);
        $('#user___user__change_avatar_form_modal').modal('hide');
        $('#user___user__upload_avatar_button').attr('disabled', true);
    }
};


