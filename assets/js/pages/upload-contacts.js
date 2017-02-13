$(function () {

    var ul = $('#upload ul');

    $('#drop a').click(function () {
        $(this).parent().find('input').click();
    });

    $('#upload').fileupload({
        dropZone: $('#drop'),
        dataType: 'json',

        add: function (e, data) {
            $('.progress').removeClass('hidden');
            data.submit();
        },

        done: function (e, data) {
            var resp = JSON.parse(data.xhr().response);

            if (resp.status) {
                $.ajax({
                    url: '/contacts/importCsv',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        file: resp.file
                    },

                    complete: function (resp) {
                        $('.progress').addClass('hidden');
                        window.location.href = '/contacts/list';
                    }
                })
            }
        },

        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('.progress-bar').css('width', progress + '%');
        },
    });

    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });


});