function JS_ImagesBackground(baseUrl, module, action) {
    // check side bar
//    $("#main_cms").attr("class", "active");
    $("#main_images-background").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;//Biên public lưu tên module
    this.formdata = new FormData();
    this.countindex = 0;
}
// Load su kien tren man hinh index
JS_ImagesBackground.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmImagesIndex';
    myClass.loadList(oForm);
    // Them moi loai danh muc
    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
    $(oForm).find('#btn_search').click(function () {
        myClass.loadList(oForm);
    });

    $(document).keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            myClass.loadList(oForm);
            return false;
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
JS_ImagesBackground.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}
// Lay du lieu cho man hinh danh sach
JS_ImagesBackground.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmImagesIndex';
    var myClass = this;
    var loadding = NclLib .loadding();
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
JS_ImagesBackground.prototype.genTable = function (arrResult) {
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
        shtml += '<td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="' + arrResult[x]['PK_CMS_IMAGES_BACKGROUND'] + '"></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}"> <img src="' + arrResult[x]['C_IMAGE_FILE_NAME'] + '" style="max-width:100%;max-height:150px"/></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + this.genTableMota(arrResult[x]) + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + status + '</td>';
        shtml += '</tr>';
    }
    $('#data-list-images').html(shtml);
}
JS_ImagesBackground.prototype.genTableMota = function (arrResult) {
    var shtml = '<table width="100%" id="table-mo-ta"  class="table table-bordered" > <colgroup> <col width="30%"><col width="70%"></colgroup>';
    shtml += '<tr >';
    shtml += '<td align="right">Tên hình ảnh</td>';
    shtml += '<td>' + arrResult['C_NAME'] + '</td>';
    shtml += '</tr>';
    shtml += '<tr>';
    shtml += '<td align="right">Độ rộng</td>';
    shtml += '<td>' + (arrResult['C_WIDTH'] == null ? '' : arrResult['C_WIDTH']) + '</td>';
    shtml += '</tr>';
    shtml += '<tr>';
    shtml += '<td align="right">Chiều cao</td>';
    shtml += '<td>' + (arrResult['C_HEIGHT'] == null ? '' : arrResult['C_HEIGHT']) + '</td>';
    shtml += '</tr>';
    shtml += '<tr>';
    shtml += '</tr>';
    shtml += '<tr>';
    shtml += '<td align="right">Vị trí hiển thị</td>';
    shtml += '<td>' + arrResult['C_POSITION'] + '</td>';
    shtml += '</tr>';
    shtml += '<tr >';
    shtml += '<td align="right">Trạng thái</td>';
    shtml += '<td>' + (arrResult['C_STATUS'] == 1 ? 'Hiển thị' : 'Không hiển thị') + '</td>';
    shtml += '</tr>';
    shtml += '</table>';
    return  shtml;
}
// Them loai danh muc
JS_ImagesBackground.prototype.add = function (oForm) {
    NclLib .showmainloadding();
    var url = this.urlPath + '/images_add';
    var myClass = this;
    myClass.countindex = 0;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            NclLib .successLoadImage();
            $('#modalImages').html(arrResult);
            $('#frmImagesIndex').hide();
            $('#modalImages').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddImages'));
            $('.datepicker').datepicker();
            $('.chzn-select').chosen();
        }
    });
}
JS_ImagesBackground.prototype.backtoindex = function () {
    $('#modalImages').html('');
    $('#frmImagesIndex').show();
    $('#modalImages').hide();
}
JS_ImagesBackground.prototype.validateform = function (oForm) {
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
            C_POSITION: "required",
            C_NAME: "required",
//            email: "email",
//            images: "required",
        },
        messages: {
            C_POSITION: "Vị trí không được để trống",
            C_NAME: "Tên ảnh không được để trống",
//            phone_number: {
//                digits: "Số điện thoại phải là các chữ số",
//                rangePhone: "Số điện thoại phải từ 9 đến 15 ký tự",
//            },
//            email: "Email phải đúng định dạng email",
//            images: "Ảnh đính kèm không được để trống",
        }
    });
}
// sua
JS_ImagesBackground.prototype.edit = function (oForm) {
    var url = this.urlPath + '/images_edit';
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
        NclLib .alertMessage('danger', "Bạn chưa chọn ảnh cần sửa");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một ảnh để sửa");
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
            $('#frmImagesIndex').hide();
            $('#modalImages').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddImages'));
            $('.datepicker').datepicker();
            $('.chzn-select').chosen();
        }
    });
}
// Them loai danh muc
JS_ImagesBackground.prototype.delete = function (oForm) {
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
        NclLib .alertMessage('danger', "Bạn chưa chọn ảnh cần xóa");
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
                NclLib .alertMessage('success', arrResult['message']);
            } else {
                NclLib .alertMessage('danger', arrResult['message']);
            }
        }
    });
}
JS_ImagesBackground.prototype.preview_images = function ()
{
    var myClass = this;
    var filedata = document.getElementById("images");
    var file = filedata.files[0];
    var extensionFile = myClass.getextensionfile(file.name);
    if (extensionFile == 'jpg' || extensionFile == 'png') {
        $('#image_preview').html("<div class=\"col-md-2\"></div><div class='col-md-10'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[0]) + "'></div>");
        if (myClass.formdata) {
            myClass.formdata.append("images", file);
        }
        myClass.countindex++;
    } else {
        NclLib .alertMessage('warning', 'Thông báo', 'Bạn cần phải chọn đúng file ảnh (.png,.jpg)');
    }
}

JS_ImagesBackground.prototype.PreviewFeatureImage = function ()
{
    var myClass = this;
    var filedata = document.getElementById("C_FEATURE_IMG");
    $('#PreviewFeatureImage').html("<div class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[0]) + "'></div>");
    if (myClass.formdata) {
        myClass.formdata.append("C_FEATURE_IMG", filedata.files[0]);
    }
}

// Cap nhat loai danh muc
JS_ImagesBackground.prototype.update = function (oForm) {
    if (oForm.valid()) {
        var url = this.urlPath + '/update';
        var myClass = this;
        myClass.formdata.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        myClass.formdata.append('data', data);
        if ($('#filename').val() == '' && $('#images').val() == '') {
            NclLib .alertMessage('danger', 'Cảnh báo', 'Ảnh album không được để trống', 6000);
            return false;
        }
        NclLib .showmainloadding();
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: myClass.formdata,
            processData: false,
            contentType: false,
            success: function (arrResult) {
                NclLib .successLoadImage();
                myClass.formdata = new FormData();
                if (arrResult['success']) {
                    $('#addListModal').modal('hide');
                    myClass.loadList(oForm);
                    $('#modalImages').html('');
                    $('#frmImagesIndex').show();
                    $('#modalImages').hide();
                    NclLib .alertMessage('success', arrResult['message']);
                } else {
                    NclLib .alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
                }
            },
            error: function (arrResult) {
                NclLib .alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
            }
        });
    }

}
JS_ImagesBackground.prototype.getextensionfile = function (filename) {
    return(/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
}
JS_ImagesBackground.prototype.deletefile = function (obj, a) {
    $('#file' + a).remove();
    this.formdata.delete("file" + a);
    $('#images').val('');
}
