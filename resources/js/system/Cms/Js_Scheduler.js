function JS_Scheduler(baseUrl, module, action) {
    // check side bar
//    $("#main_cms").attr("class", "active");
    $("#main_scheduler").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;//Biên public lưu tên module
    this.formdata = new FormData();
    this.countindex = 0;
}
// Load su kien tren man hinh index
JS_Scheduler.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmSchedulerIndex';
//    myClass.loadList(oForm);
    // Them moi loai danh muc
    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
    $(oForm).find('#btn_search').click(function () {
        $(oForm).submit();
    });

    $(document).keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $(oForm).submit();
//            myClass.loadList(oForm);
//            return false;
        }
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function () {
            myClass.delete(oForm);
        }
    });
    // Tim kiem loai danh muc
    $(oForm).find('#listtype').change(function () {
        myClass.loadList(oForm);
    });
}
// Load su kien tren cac minh khac
JS_Scheduler.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}
// Lay du lieu cho man hinh danh sach
JS_Scheduler.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmSchedulerIndex';
    $(oForm).submit();return false;
    var myClass = this;
    var loadding = EFYLib.loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadlist';
    var data = $(oForm).serialize();
    data += '&currentPage=' + currentPage;
    data += '&perPage=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            loadding.go(100);
            var shtml = myClass.genTable(arrResult['Dataloadlist']['data']);
            // phan trang
            $('#pagination').html(arrResult.pagination);
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').val(arrResult.perPage);
            loadding.go(100);
        }
    });
}
JS_Scheduler.prototype.genTable = function (arrResult) {
    var shtml = '';
    var status = 'Không hoạt động';
    var oldPosGroup = '';
    for (var x in arrResult) {
        if (arrResult[x]['C_STATUS'] == '1') {
            status = 'Hoạt động'
        } else {
            status = 'Không hoạt động'
        }
        shtml += '<tr>';
        shtml += '<td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="' + arrResult[x]['PK_CMS_VIDEOS'] + '"></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}"> ' + arrResult[x]['C_NAME'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_VIDEO_FILE_NAME'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + status + '</td>';
        shtml += '</tr>';
    }
    $('#data-list-scheduler').html(shtml);
}
// Them loai danh muc
JS_Scheduler.prototype.add = function (oForm) {
    EFYLib.showmainloadding();
    var url = this.urlPath + '/scheduler_add';
    var myClass = this;
    myClass.countindex = 0;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            EFYLib.successLoadImage();
            $('#modalImages').html(arrResult);
            $('#frmSchedulerIndex').hide();
            $('#modalImages').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddImages'));
            $('.chzn-select').chosen();
            $('.datepicker').datepicker();
        }
    });
}
JS_Scheduler.prototype.backtoindex = function () {
    $('#modalImages').html('');
    $('#frmSchedulerIndex').show();
    $('#modalImages').hide();
}
JS_Scheduler.prototype.validateform = function (oForm) {
    jQuery.validator.addMethod("rangePhone", function (value, element, params) {
        if (value.length >= params[0] && value.length <= params[1]) {
            return true;
        } else {
            return false;
        }
    });
    oForm.validate({
        onfocusout: false,
        invalidHandler: function (form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                validator.errorList[0].element.focus();
            }
        },
        rules: {
            C_WORK_NAME: "required",
            C_DAY: "required",
//            email: "email",
//            scheduler: "required",
        },
        messages: {
            C_WORK_NAME: "Nội dung công việc không được để trống",
            C_DAY: "Ngày trong tuần không được để trống",
//            phone_number: {
//                digits: "Số điện thoại phải là các chữ số",
//                rangePhone: "Số điện thoại phải từ 9 đến 15 ký tự",
//            },
//            email: "Email phải đúng định dạng email",
//            scheduler: "Ảnh đính kèm không được để trống",
        }
    });
}
// sua
JS_Scheduler.prototype.edit = function (oForm) {
    var url = this.urlPath + '/scheduler_edit';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
            i++;
        }
    });
    if (listitem == '') {
        EFYLib.alertMessage('danger', "Bạn chưa chọn bài viết cần sửa");
        return false;
    }
    if (i > 1) {
        EFYLib.alertMessage('danger', "Bạn chỉ được chọn một bài viết để sửa");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#modalImages').html(arrResult);
            $('#frmSchedulerIndex').hide();
            $('#modalImages').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddImages'));
            $('.datepicker').datepicker();
        }
    });
}
// Them loai danh muc
JS_Scheduler.prototype.delete = function (oForm) {
    var url = this.urlPath + '/delete';
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == '') {
        EFYLib.alertMessage('danger', "Bạn chưa chọn bài viết cần xóa");
        return false;
    }
    var data = $(oForm).serialize();
    data += '&listitem=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                myClass.loadList(oForm);
                EFYLib.alertMessage('success', arrResult['message']);
            } else {
                EFYLib.alertMessage('danger', arrResult['message']);
            }
        }
    });
}
JS_Scheduler.prototype.preview_scheduler = function ()
{
    var myClass = this;
    var filedata = document.getElementById("scheduler");
    var file = filedata.files[0];
    var extensionFile = myClass.getextensionfile(file.name);
    if (extensionFile == 'mp4' || extensionFile == 'avi') {
        $('#video_preview').html("<div class=\"col-md-2\"></div><div class='col-md-3' id='file-video'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='" + URL.createObjectURL(event.target.files[0]) + "'>" + file.name + " </a><i class='fa fa-trash-o' onclick=\"JS_Scheduler.deletefile(\'" + file.name + "\')\"></i></div>");
        if (myClass.formdata) {
            myClass.formdata.append("scheduler", file);
        }
        myClass.countindex++;
    } else {
        EFYLib.alertMessage('warning', 'Thông báo', 'Bạn cần phải chọn đúng file ảnh (.mp4,.avi,...)');
    }
}

JS_Scheduler.prototype.PreviewFeatureImage = function ()
{
    var myClass = this;
    var filedata = document.getElementById("C_FEATURE_IMG");
    $('#PreviewFeatureImage').html("<div class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[0]) + "'></div>");
    if (myClass.formdata) {
        myClass.formdata.append("C_FEATURE_IMG", filedata.files[0]);
    }
}

// Cap nhat loai danh muc
JS_Scheduler.prototype.update = function (oForm) {
    if (oForm.valid()) {
        var url = this.urlPath + '/update';
        var myClass = this;
        myClass.formdata.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        myClass.formdata.append('data', data);
        EFYLib.showmainloadding();
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: myClass.formdata,
            processData: false,
            contentType: false,
            success: function (arrResult) {
                EFYLib.successLoadImage();
                myClass.formdata = new FormData();
                if (arrResult['success']) {
                    $('#addListModal').modal('hide');
                    myClass.loadList(oForm);
                    $('#modalImages').html('');
                    $('#frmSchedulerIndex').show();
                    $('#modalImages').hide();
                    EFYLib.alertMessage('success', arrResult['message']);
                } else {
                    EFYLib.alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
                }
            },
            error: function (arrResult) {
                EFYLib.successLoadImage();
                EFYLib.alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
            }
        });
    }

}
JS_Scheduler.prototype.getextensionfile = function (filename) {
    return(/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
}
JS_Scheduler.prototype.deletefile = function (obj) {
    $('#file-video').remove();
    this.formdata.delete("scheduler");
    $('#scheduler').val('');
}
