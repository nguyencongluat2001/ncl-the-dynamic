function JS_Documents(baseUrl, module, action) {
    // check side bar
//    $("#main_cms").attr("class", "active");
    $("#main_documents").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;//Biên public lưu tên module
    this.formdata = new FormData();
    this.formdataDocument = new FormData();
    this.countindex = 0;
    this.countindexDocuments = 0;
}
// Load su kien tren man hinh index
JS_Documents.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmDocumentsIndex';
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
JS_Documents.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}
// Lay du lieu cho man hinh danh sach
JS_Documents.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmDocumentsIndex';
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
JS_Documents.prototype.genTable = function (arrResult) {
    var shtml = '';
    var status = 'Không hoạt động';
    var oldPosGroup = '';
    for (var x in arrResult) {
        if (arrResult[x]['C_STATUS'] == 'HOAT_DONG') {
            status = 'Hoạt động'
        } else {
            status = 'Không hoạt động'
        }
        shtml += '<tr>';
        shtml += '<td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="' + arrResult[x]['PK_CMS_DOCUMENT'] + '"></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_SUBJECT'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_SYMBOL'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_DOCTYPE_NAME'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_SIGNER'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + status + '</td>';
        shtml += '</tr>';
    }
    $('#data-list-documents').html(shtml);
}
// Them loai danh muc
JS_Documents.prototype.add = function (oForm) {
    NclLib .showmainloadding();
    var url = this.urlPath + '/documents_add';
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
            $('#modalDocuments').html(arrResult);
            $('#frmDocumentsIndex').hide();
            $('#modalDocuments').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddDocuments'));
            $('.datepicker').datepicker();
            $('.chzn-select').chosen({
                height: '100%',
                width: '100%'
            });
        }
    });
}
JS_Documents.prototype.validateform = function (oForm) {
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
            C_SYMBOL: "required",
            C_DATE_PUBLIC: "required",
            C_SUBJECT: "required",
            C_SUBJECT: "required",
            C_DOCTYPE: "required",
        }
        ,
        messages: {
            C_SYMBOL: "Số ký hiệu được để trống",
            C_DATE_PUBLIC: "Ngày ban hành",
            C_SUBJECT: "Trích yếu không được để trống",
            C_DOCTYPE: "Loại văn bản không được để trống",
        }
    }
    );
}
JS_Documents.prototype.backtoindex = function () {
    $('#modalDocuments').html('');
    $('#frmDocumentsIndex').show();
    $('#modalDocuments').hide();
}
// sua
JS_Documents.prototype.edit = function (oForm) {
    var url = this.urlPath + '/documents_edit';
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
        NclLib .alertMessage('danger', "Bạn chưa chọn bài viết cần sửa");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một bài viết để sửa");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#modalDocuments').html(arrResult);
            $('#frmDocumentsIndex').hide();
            $('#modalDocuments').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddDocuments'));
            $('.datepicker').datepicker();
            $('.chzn-select').chosen({
                height: '100%',
                width: '100%'
            });
        }
    });
}
// Them loai danh muc
JS_Documents.prototype.delete = function (oForm) {
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
        NclLib .alertMessage('danger', "Bạn chưa chọn bài viết cần xóa");
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
JS_Documents.prototype.preview_images = function ()
{
    var myClass = this;
    var filedata = document.getElementById("images");
    var total_file = filedata.files.length;
    for (var i = 0; i < total_file; i++)
    {
        var file = filedata.files[i];
        var extensionFile = myClass.getextensionfile(file.name);
        if ((extensionFile == 'jpg' || extensionFile == 'png') && 1 == 2) {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindex) + "' class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[i]) + "'></div>");
        } else {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindex) + "' class='col-md-3'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='" + URL.createObjectURL(event.target.files[i]) + "'>" + file.name + " </a><i class='fa fa-trash-o' onclick=\"JS_Documents.deletefile(\'" + file.name + "\'," + (i + myClass.countindex) + ")\"></i></div>");
        }
        if (myClass.formdata) {
            myClass.formdata.append("file" + (i + myClass.countindex), file);
        }
        myClass.countindex++;

    }
}

JS_Documents.prototype.PreviewFeatureImage = function ()
{
    var myClass = this;
    var filedata = document.getElementById("C_FEATURE_IMG");
    $('#PreviewFeatureImage').html("<div class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[0]) + "'></div>");
    if (myClass.formdata) {
        myClass.formdata.append("C_FEATURE_IMG", filedata.files[0]);
    }
}

// Cap nhat loai danh muc
JS_Documents.prototype.update = function (oForm) {
    if ($(oForm).valid()) {
        var url = this.urlPath + '/update';
        var myClass = this;
        myClass.formdata.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        myClass.formdata.append('data', data);
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: myClass.formdata,
            processData: false,
            contentType: false,
            success: function (arrResult) {
                myClass.formdata = new FormData();
                if (arrResult['success']) {
                    $('#addListModal').modal('hide');
                    myClass.loadList(oForm);
                    $('#modalDocuments').html('');
                    $('#frmDocumentsIndex').show();
                    $('#modalDocuments').hide();
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
JS_Documents.prototype.updateAndNew = function (oForm) {
    if ($(oForm).valid()) {
        var url = this.urlPath + '/update';
        var myClass = this;
        myClass.formdata.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        myClass.formdata.append('data', data);
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: myClass.formdata,
            processData: false,
            contentType: false,
            success: function (arrResult) {
                myClass.formdata = new FormData();
                if (arrResult['success']) {
                    $('#addListModal').modal('hide');
                   
                    $('#modalDocuments').html('');
                    $('#frmDocumentsIndex').show();
                    $('#modalDocuments').hide();
                     myClass.add(oForm);
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
JS_Documents.prototype.getextensionfile = function (filename) {
    return(/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
}
JS_Documents.prototype.deletefile = function (obj, a) {
    $('#file' + a).remove();
    this.formdata.delete("file" + a);
    $('#images').val('');
}
JS_Documents.prototype.deletefile_documents = function (obj, a) {
    $('#file' + a).remove();
    this.formdataDocument.delete("file" + a);
    $('#files').val('');
}

JS_Documents.prototype.deletefileInSerVer = function (file_name, a) {
    var url = this.urlPath + '/deletefile';
    var myClass = this;
    var filename = file_name;
    var pkrecord = $('#file-preview-' + a).attr('data-id');
    $.confirm({
        title: 'Thông báo!',
        titleClass: 'tittleclass',
        content: 'Bạn có chắc chắn muốn xóa file này!',
        autoClose: 'Close|10000',
        buttons: {
            deleteFile: {
                btnClass: 'btn-blue',
                text: 'Xóa',
                action: function () {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'JSON',
                        data: {
                            _token: $('#_token').val(),
                            filename: filename,
                            pkrecord: pkrecord
                        },
                        success: function () {
                            $('#file-preview-' + a).remove();
                        }
                    });
                }
            },
            Close: {
                btnClass: 'btn-red',
                text: 'Đóng',
                action: function () {
                }
            }
        }
    });
}
