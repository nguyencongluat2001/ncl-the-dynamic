function JS_Questions(baseUrl, module, action) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;
}

// Load su kien tren man hinh index
JS_Questions.prototype.loadIndex = function () {
    console.log(222)
    $(document).ajaxSend(function () {
        EfyLib.showmainloadding();
    });
    $(document).ajaxStop(function () {
        EfyLib.successLoadImage();
    });
    var myClass = this;
    var oForm = 'form#frmQuestion_index';
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    myClass.loadList(oForm);
    // Them moi loai danh muc
    $(oForm).find('#Units').change(function () {
        myClass.loadList(oForm);
    });
    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
 
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
    $(oForm).find('#btn_search').click(function () {
        myClass.loadList(oForm);
    });
    $(oForm).find('#btn_export_cache').click(function () {
        myClass.exportCache(oForm);
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').click(function () {
        myClass.delete(oForm);
    });
    // Tim kiem loai danh muc
    $(oForm).find('#listtype').change(function () {
        myClass.loadList(oForm);
    });
    $(oForm).find('#search_text').keypress(function (e) {
        if (e.which == 13) {
            myClass.loadList(oForm);
        }
    });
    $(oForm).find('#trang_thai').change(function () {
        myClass.loadList(oForm);
    });
}

// Load su kien tren cac minh khac
JS_Questions.prototype.loadevent = function (oForm) {
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
}

// Lay du lieu cho man hinh danh sach
JS_Questions.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    var oForm = 'form#frmQuestion_index';
    var myClass = this;
    var loadding = EfyLib.loadding();
    loadding.go(20);
    var url = myClass.baseUrl+'/system/examinations/questions/loadList';
    var dirxml = myClass.baseUrl + '/xml/System/list/quan_tri_doi_tuong_danh_muc.xml';
    if (typeof (Tablelist) === 'undefined') {
        Tablelist = new TableXml(dirxml);
    }
    var data = $(oForm).serialize();
    data += '&currentPage=' + currentPage;
    data += '&perPage=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        // dataType: 'json',
        data: data,
        success: function (arrResult) {
            $("#table-container").html(arrResult);
            // phan trang
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPages = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPages);
            });
            $(oForm).find('#cbo_nuber_record_page').val(perPage);
            loadding.go(100);
        }
    });
}

// Them loai danh muc
JS_Questions.prototype.add = function (oForm) {
    var url = this.urlPath + '/add';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $('#addModal').html(arrResult);
            $('#addModal').modal('show');
            
            // $('#checkall_process_per_group').click();
            // $('#status').attr('checked', 'true');
            myClass.loadevent('form#frmAdd');

        }
    });
}
// Them loai danh muc
JS_Questions.prototype.back = function (oForm) {
    var myClass = this;
    window.location.replace(myClass.baseUrl+'/system/examinations');
}

/**
 * Hàm hiển thêm mới
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_Questions.prototype.store = function (oFormCreate) {
    var url = this.urlPath + '/update';
    var myClass = this;
    var data = $(oFormCreate).serialize();
    if (CKEDITOR.instances.ten_cau_hoi.getData() == '') {
        var nameMessage = 'Tên câu hỏi không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#dap_an_a").val() == '') {
        var nameMessage = 'Đáp án A không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#dap_an_b").val() == '') {
        var nameMessage = 'Đáp án B không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#dap_an_c").val() == '') {
        var nameMessage = 'Đáp án C không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#dap_an_d").val() == '') {
        var nameMessage = 'Đáp án D không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#thu_tu").val() == '') {
        var nameMessage = 'Thứ tự không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#trang_thai").val() == '') {
        var nameMessage = 'Trạng thái là bắt buộc';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    var formdata = new FormData();
    var status = ''
    $('input[name="trang_thai"]:checked').each(function() {
        status =  $(this).val();
    });
    formdata.append('_token', $("#_token").val());
    formdata.append('id', $("#id").val());
    formdata.append('ten_cau_hoi', $("#ten_cau_hoi").val());
    formdata.append('dap_an_a', $("#dap_an_a").val());
    formdata.append('dap_an_b', $("#dap_an_b").val());
    formdata.append('dap_an_c', $("#dap_an_c").val());
    formdata.append('dap_an_d', $("#dap_an_d").val());
    formdata.append('dap_an_dung', $("#dap_an_dung").val());
    formdata.append('ten_cau_hoi', CKEDITOR.instances.ten_cau_hoi.getData());
    formdata.append('trang_thai', status);
    formdata.append('thu_tu', $("#thu_tu").val());
    formdata.append('dot_thi_id', $("#dot_thi_id").val());

    $.ajax({
        url: url,
        type: "POST",
        data: formdata,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (arrResult) {
            console.log(arrResult);
            if (arrResult['success'] == true) {
                  var nameMessage = 'Cập nhật thành công!';
                  EfyLib.alertMessage('success', 'Thành công', nameMessage);
                  $('#addModal').modal('hide');
                  myClass.loadList(oFormCreate);

            } else {
                  EfyLib.alertMessage('danger', 'Thất bại', arrResult['message']);
                }
        }
    });
}
// sua
JS_Questions.prototype.edit = function (oForm) {
    var url = this.urlPath + '/edit';
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
        EfyLib.alertMessage('danger', 'Thông báo', "Bạn chưa chọn đối cần sửa");
        return false;
    }
    if (i > 1) {
        EfyLib.alertMessage('danger', 'Thông báo', "Bạn chỉ được chọn một đối để sửa");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $('#addModal').html(arrResult);
            $('#addModal').modal('show');
            var oForm = $('form#frmAdd');
            myClass.loadevent(oForm);
        }
    });
}
JS_Questions.prototype.show = function (id) {
    var url = this.urlPath + '/show';
    var myClass = this;
    var data = $('form#frmQuestion_index').serialize();
    data += '&itemId=' + id;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $('#addModal').html(arrResult);
            $('#addModal').modal('show');
            var oForm = $('form#frmAdd');
            myClass.loadevent(oForm);
        }
    });
}

// Xoa mot doi tuong
JS_Questions.prototype.delete = function (oForm) {
    var url = this.urlPath + '/delete';
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            console.log(5,$(this).val())
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == '') {
        EfyLib.alertMessage('danger', 'Thông báo', "Bạn chưa chọn danh mục cần xóa");
        return false;
    }
    var data = $(oForm).serialize();
    data += '&listitem=' + listitem;
    console.log(1,listitem)

    $.confirm({
        title: 'Thông báo!',
        content: 'Bạn có chắc chắn muốn xóa những đối tượng này không?',
        autoClose: 'Close|10000',
        buttons: {
            deleteUser: {
                btnClass: 'btn btn-primary',
                text: 'Xóa',
                action: function () {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: data,
                        success: function (arrResult) {
                            if (arrResult['success']) {
                                myClass.loadList(oForm);
                                EfyLib.alertMessage('success', 'Thành công', 'Xóa thành công');
                            } else {
                                EfyLib.alertMessage('danger', 'Thất bại', arrResult['message']);
                            }
                        }
                    });
                }
            },
            Close: {
                btnClass: 'btn btn-light',
                text: 'Đóng',
                action: function () {
                }
            }
        }
    });
}
// sua
JS_Questions.prototype.question = function (oForm) {
    // var url = this.urlPath + '/question';
    var myClass = this;
    window.location.replace(myClass.baseUrl+'/system/examinations/questions');
}