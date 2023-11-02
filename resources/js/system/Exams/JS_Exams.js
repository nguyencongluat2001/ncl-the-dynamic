function JS_Exams(baseUrl, module, action) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;
}

// Load su kien tren man hinh index
JS_Exams.prototype.loadIndex = function () {
    console.log(222)
    $(document).ajaxSend(function () {
        EfyLib.showmainloadding();
    });
    $(document).ajaxStop(function () {
        EfyLib.successLoadImage();
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
     // form load
     $(oForm).find('#cap_don_vi').change(function() {
        myClass.loadList(oForm);
    });
    // form load
    $(oForm).find('#don_vi').change(function() {
        myClass.loadList(oForm);
    });
}

// Load su kien tren cac minh khac
JS_Exams.prototype.loadevent = function (oForm) {
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
JS_Exams.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    var oForm = 'form#frmlist_index';
    var myClass = this;
    var loadding = EfyLib.loadding();
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
JS_Exams.prototype.add = function (oForm) {
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
JS_Exams.prototype.store = function (oFormCreate) {
    var url = this.urlPath + '/update';
    var myClass = this;
    var data = $(oFormCreate).serialize();
    if ($("#ten").val() == '') {
        var nameMessage = 'Tên danh mục không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#nam").val() == '') {
        var nameMessage = 'Hội thi năm không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#ngay_bat_dau").val() == '') {
        var nameMessage = 'Ngày bắt đầu không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#ngay_ket_thuc").val() == '') {
        var nameMessage = 'Ngày kết thúc không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#thoi_gian_lam_bai").val() == '') {
        var nameMessage = 'Thười gian làm bài không được để trống!';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#trang_thai").val() == '') {
        var nameMessage = 'Trạng thái là bắt buộc';
        EfyLib.alertMessage('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            console.log(arrResult);
            if (arrResult['success'] == true) {
                  var nameMessage = 'Cập nhật thành công!';
                  EfyLib.alertMessage('success', 'Thành công', 'Xóa thành công');
                  $('#addModal').modal('hide');
                  myClass.loadList(oFormCreate);

            } else {
                  EfyLib.alertMessage('danger', 'Thất bại', arrResult['message']);
                }
        }
    });
}
// sua
JS_Exams.prototype.edit = function (oForm) {
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

// Xoa mot doi tuong
JS_Exams.prototype.delete = function (oForm) {
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
JS_Exams.prototype.question = function (id) {
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
JS_Exams.prototype.show = function (id) {
    var url = this.urlPath + '/show';
    var myClass = this;
    var data = $('form#frmlist_index').serialize();
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
/**
 * Load màn hình danh sách huyện
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Exams.prototype.getUnit = function (type) {
    var myClass = this;
    var url = this.baseUrl + '/system/objects/get-unit';
    var data = $('form#frmlist_index').serialize();
    $.ajax({
        url: url,
        type: "POST",
        cache: true,
        data: data,
        success: function (arrResult) {
            var html = `<div id="unit">`
                html += `<select onchange="JS_Exams.loadList()" class="form-select form-select-md" id="don_vi" name="don_vi">`
                    html += `<option value="">Tất cả</option>`
                    $(arrResult.data.arrUnit).each(function(index,el) {
                        html += `<option value="`+ el.code +`" `+ (el.selected == 1 ? 'selected':'') +`>`+ el.name +`</option>`
                    });
                html += `<select>`
            html += `</div>`
            $("#unit").html(html);
            myClass.loadList();

        }
    });
}
/**
 * Load màn hình danh sách đợt thi
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Exams.prototype.getDotThi = function (type) {
    var myClass = this;
    var url = this.baseUrl + '/system/objects/get-dot-thi';
    var data = $('form#frmlist_index').serialize();
    $.ajax({
        url: url,
        type: "POST",
        cache: true,
        data: data,
        success: function (arrResult) {
            var html = `<div id="dot">`
                html += `<select onchange="JS_Exams.loadList()" class="form-select form-select-md" id="dot_thi" name="dot_thi">`
                    html += `<option value="">Tất cả</option>`
                    $(arrResult.data.arrDotThi).each(function(index,el) {
                        html += `<option value="`+ el.id +`" `+ (el.selected == 1 ? 'selected':'') +`>`+ el.ten +`</option>`
                    });
                html += `<select>`
            html += `</div>`
            $("#dot").html(html);
            myClass.loadList();

        }
    });
}