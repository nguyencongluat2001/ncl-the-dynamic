function JS_Sql(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-faq');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}
/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_Sql.prototype.loadIndex = function() {
    var myClass = this;
    var oForm = 'form#frmFaq_index';

    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    $("#btn_add").click(function(){
        myClass.add();
    })
    $("#btn_delete").click(function(){
        myClass.delete();
    })
    $("#parent_id").on('change', function(){
        myClass.loadList();
    })
    $(oForm).find('#txt_search').click(function () {
            var page = $(oForm).find('#limit').val();
            var perPage = $(oForm).find('#cbo_nuber_record_page').val();
            myClass.loadList(oForm, page, perPage);
        
    });
    myClass.loadList();
}
/**
 * Danh sách
 */
JS_Sql.prototype.loadList = function(numberPage = 1, perPage = 15){
    var myClass = this;
    var oForm = '#frmFaq_index';
    var url = myClass.urlPath + '/loadList';
    var data = $(oForm).serialize();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $("#table-container").html(arrResult);
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
            NclLib.successLoadding();
        }
    });
}
/**
 * Thêm
 */
JS_Sql.prototype.add = function () {
    var url = this.urlPath + '/create';
    var myClass = this;
    var data = 'parent_id=' + $("#parent_id").val();
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $('#status').attr('checked', true);
            $("#btn_update").click(function(){
                myClass.update();
            });
        }
    });
}
/**
 * Hàm hiển thị modal edit
 *
 */
JS_Sql.prototype.edit = function (id) {
    var myClass = this;
    var url = this.urlPath + '/create';
    var data = '_token=' + $('#frmFaq_index #_token').val();
    data += '&id=' + id;
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            if(arrResult['success'] == false){
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                return false;
            }
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $("#btn_update").click(function(){
                myClass.update();
            });
        }
    });
}
/**
 * Cập nhật
 */
JS_Sql.prototype.update = function () {
    var oForm = '#frmAddFAQ';
    var url = this.urlPath + '/update';
    var myClass = this;
    var data = $(oForm).serialize();
    if ($("#name").val() == '') {
        var nameMessage = 'Tên câu hỏi không được để trống!';
        NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                  var nameMessage = 'Cập nhật thành công!';
                  NclLib.alertMessageBackend('success', 'Thông báo', nameMessage);
                  $('#addmodal').modal('hide');
                  myClass.loadList(oForm);
            } else {
                  var nameMessage = arrResult['message'];
                  NclLib.alertMessageBackend('danger', 'Lỗi', nameMessage);
            }
        }
    });
}
/**
 * Xoa doi tuong
 */
JS_Sql.prototype.delete = function (oForm) {
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
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Chọn ít nhất một đổi tượng để xóa');
        return false;
    }
    var data = $(oForm).serialize();
    var url = this.urlPath + '/delete';
    Swal.fire({
        title: 'Bạn có chắc chắn xóa vĩnh viễn đối tượng này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#34bd57',
        confirmButtonText: 'Xác nhận',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Đóng'
      }).then((result) => {
        if(result.isConfirmed == true){
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: {
                    _token: $('#_token').val(),
                    listitem: listitem,
                },
                success: function (arrResult) {
                    if (arrResult['success'] == true) {
                        NclLib.alertMessageBackend('success', 'Thông báo', arrResult['message']);
                        myClass.loadList();
                    } else {
                        NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                    }
                }
            });
        }
      })
}