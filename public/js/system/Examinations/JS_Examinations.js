function JS_Examinations(baseUrl, module, action) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;
}

// Load su kien tren man hinh index
JS_Examinations.prototype.loadIndex = function () {
    console.log(222)
    $(document).ajaxSend(function () {
        NclLib .showmainloadding();
    });
    $(document).ajaxStop(function () {
        NclLib .successLoadImage();
    });
    var myClass = this;
    var oForm = 'form#frmlist_index';
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
    $(oForm).find('#nam').change(function () {
        myClass.loadList(oForm);
    });
}

// Load su kien tren cac minh khac
JS_Examinations.prototype.loadevent = function (oForm) {
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
JS_Examinations.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    var oForm = 'form#frmlist_index';
    var myClass = this;
    var loadding = NclLib .loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadList';
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
JS_Examinations.prototype.add = function (oForm) {
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
/**
 * Hàm hiển thêm mới
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_Examinations.prototype.store = function (oFormCreate) {
    var url = this.urlPath + '/update';
    var myClass = this;
    var data = $(oFormCreate).serialize();
    if ($("#ten").val() == '') {
        var nameMessage = 'Tên danh mục không được để trống!';
        NclLib .alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#nam").val() == '') {
        var nameMessage = 'Hội thi năm không được để trống!';
        NclLib .alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#ngay_bat_dau").val() == '') {
        var nameMessage = 'Ngày bắt đầu không được để trống!';
        NclLib .alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#ngay_ket_thuc").val() == '') {
        var nameMessage = 'Ngày kết thúc không được để trống!';
        NclLib .alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    let d1 = $("#ngay_bat_dau").val().split('/');
    let d2 = $("#ngay_ket_thuc").val().split('/');
    let objD1 = new Date(`${d1[2]}-${d1[1]}-${d1[0]}`);
    let objD2 = new Date(`${d2[2]}-${d2[1]}-${d2[0]}`);
    if (objD1 > objD2) {
        var nameMessage = 'Ngày bắt đầu không được lớn hơn ngày kết thúc!';
        NclLib .alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#thoi_gian_lam_bai").val() == '') {
        var nameMessage = 'Thười gian làm bài không được để trống!';
        NclLib .alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#trang_thai").val() == '') {
        var nameMessage = 'Trạng thái là bắt buộc';
        NclLib .alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            console.log(arrResult);
            if (arrResult['success'] == true) {
                  NclLib .alertMessage('success', 'Thành công', 'Cập nhật thành công!');
                  $('#addModal').modal('hide');
                  myClass.loadList(oFormCreate);
            } else {
                  NclLib .alertMessage('danger', 'Thất bại', arrResult['message']);
                }
        }
    });
}
// sua
JS_Examinations.prototype.edit = function (oForm) {
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
        NclLib .alertMessage('danger', 'Thông báo', "Bạn chưa chọn đối cần sửa");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', 'Thông báo', "Bạn chỉ được chọn một đối để sửa");
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

// Xoa mot doi tuong
JS_Examinations.prototype.delete = function (oForm) {
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
        NclLib .alertMessage('danger', 'Thông báo', "Bạn chưa chọn danh mục cần xóa");
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
                                NclLib .alertMessage('success', 'Thành công', 'Xóa thành công');
                            } else {
                                NclLib .alertMessage('danger', 'Thất bại', arrResult['message']);
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
JS_Examinations.prototype.question = function (id) {
    // var url = this.urlPath + '/question';
    var myClass = this;
    var url = myClass.baseUrl+'/system/examinations/questions/' + id;
    var myClass = this;
    // var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "GET",
        // data: data,
        success: function () {
            window.location.replace(myClass.baseUrl+'/system/examinations/questions/'+ id);
            // $('#checkall_process_per_group').click();
            // $('#status').attr('checked', 'true');

        }
    });
}