var Employee = function () {
    this.grid = $('#contact-list');

    this.staticFields = [
        'id',
        'first_name',
        'last_name',
        'phone',
        'email'
    ];

    this.initDatatable = function () {
        this.grid.dataTable({

            bAutoWidth: false,
            bFilter: true,
            bInfo: true,
            bPaginate: true,
            bServerSide: true,
            sPaginationType: "bootstrap",
            sDom: 'l<"enabled">frti<"bottom"p><"clear">',
            aaSorting: [['0', "DESC"]],

            ajax: {
                url: 'listJson',
                type: 'POST'
            },

            aoColumns: [
                {data: "id"},
                {data: "first_name"},
                {data: "last_name"},
                {data: "phone"},
                {data: "email"}
            ],

        });
    };

    this.clearAllFormFields = function () {
        this.editForm.find('input').each(function () {
            $(this).val('');
        });
        this.addressesContainer.html('');
        this.phonesContainer.html('');
    };

    this.getContactListJson = function () {
        return [['wedde'], ['wedwed'], ['wedwd'], ['wedwed']];
    }

    // this.collectData = function () {
    //     var data = {};
    //     $.each(this.staticFields, function (index, value) {
    //         data[value] = $('input[name="' + value + '"]').val();
    //     });
    //     data.phones = [];
    //     $('input[name="phones[]"]').each(function () {
    //         if ($(this).val() != '') {
    //             data.phones.push($(this).val());
    //         }
    //     });
    //
    //     data.addresses = [];
    //     $('input[name="addresses[]"]').each(function () {
    //         if ($(this).val() != '') {
    //             data.addresses.push($(this).val());
    //         }
    //     });
    //
    //     return data;
    // };

    this.startListen = function () {
        var self = this;
    };
};

$(function () {
    var employee = new Employee();
    employee.initDatatable();
    employee.startListen();
});
