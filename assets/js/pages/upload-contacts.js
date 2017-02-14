$(function () {

    this.uploadForm = $('form#upload');
    this.submitButton = $('#drop a');
    this.dropZone = $('#drop');
    this.progress = $('.progress');
    this.progressBar = $('.progress-bar');
    this.alertElem = $('#alert');
    this.alertMessage = $('#alert-message');
    this.fileInput = this.uploadForm.find('input[type=file]');
    this.intervalId = null;
    var self = this;

    /**
     * Start monitoring
     * @param procId
     */
    this.startProcessMonitoring = function (procId) {
        this.showProgress();

        self.intervalId = setInterval(function () {
            $.ajax({
                url: '/contacts/processInfo',
                type: 'GET',
                dataType: 'json',
                data: {'process_id': procId},

                success: function (resp) {
                    if (resp.status) {
                        self.processProgress(resp.completed_count, resp.total_count);
                    } else {
                        self.alert(1, 'Contacts was successfully importend');
                        self.endProcessMonitoring();
                    }

                },
            });
        }, 3000);
    };

    /**
     * clear interval
     */
    this.endProcessMonitoring = function () {
        self.hideProgress();
        clearInterval(this.intervalId);
        this.intervalId = null;
    };

    /**
     * Send import csv request
     * @param data
     */
    this.sendCsvImportRequest = function (data) {
        $.ajax({
            url: '/contacts/importCsv',
            type: 'POST',
            dataType: 'json',
            data: {
                file: data.file
            },

            complete: function (resp) {
                var processId = resp.responseJSON.process_id;

                if (processId) {
                    self.startProcessMonitoring(processId);
                } else {
                    self.alert(0, 'Something went wrong');
                    self.hideProgress();
                }
            },
        })
    }

    /**
     * Show messages based on response status
     */
    this.alert = function (status, message, timeOut) {
        if (message) {
            self.alertElem.hide();
            self.alertElem.removeClass('alert-success').removeClass('alert-danger');
            self.alertMessage.html(message);
            var alertType = status == 0 ? 'alert-danger' : 'alert-success';
            self.alertElem.addClass(alertType);
            self.alertMessage.html(message);
            self.alertElem.fadeIn(200);

            if (typeof timeOut == 'undefined') {
                timeOut = 2000;
            }

            if (timeOut) {
                setTimeout(function () {
                    self.alertElem.fadeOut(400);
                }, timeOut)
            }
        }
    };

    /**
     *  Show progress loader
     */
    this.showProgress = function () {
        this.progressBar.css('width', 0);
        this.progress.removeClass('hidden');
    }

    /**
     *  Hide progress loader
     */
    this.hideProgress = function () {
        this.progress.addClass('hidden');
    }

    /**
     * Show progress completeness percentages
     * @param loaded
     * @param total
     */
    this.processProgress = function (loaded, total) {
        var progress = parseInt(loaded / total * 100, 10);
        self.progressBar.css('width', progress + '%');
    }

    /**
     * Reset upload form
     */
    this.resetForm = function () {
        self.uploadForm[0].reset();
    }

    /**
     * Trigger file input click on submit button click
     */
    this.submitButton.click(function (e) {
        self.fileInput.click();
    });

    /**
     * Init jQuery fileUpload plugin
     */
    this.uploadForm.fileupload({
        dropZone: self.dropZone,
        dataType: 'json',
        autoUpload: true,

        add: function (e, data) {
            self.showProgress();
            data.submit();
        },

        done: function (e, data) {
            self.hideProgress();
            var resp = JSON.parse(data.xhr().response);

            if (resp.status) {
                self.alert(resp.status, "Importing CSV data", 0);
                self.sendCsvImportRequest(resp);
            } else {
                self.alert(resp.status, resp.message);
            }

            self.resetForm();
        },

        error: function (e, data) {
            self.resetForm();
            self.alert(0, 'Something went wrong :(');
        },

        progressall: function (e, data) {
            self.processProgress(data.loaded, data.total);
        },
    });

    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });
});