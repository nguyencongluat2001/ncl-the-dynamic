function JS_listIndications(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-category');
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_listIndications.prototype.loadIndex = function () {
    var myClass = this;
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmIndications';
    var oFormCreate = 'form#frmAdd';
    myClass.loadList(oForm);

    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $('form#frmAddCategory').find('#btn_create').click(function () {
        myClass.store('form#frmAddCategory');
    })
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
     // form load
     $(oForm).find('#cate').change(function () {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
    });
    // form load
    $(oForm).find('#fromDate').change(function() {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
    });
    // form load
    $(oForm).find('#toDate').change(function() {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
    });
    $(oForm).find('#txt_search').click(function () {
        /* ENTER PRESSED*/
            var page = $(oForm).find('#limit').val();
            var perPage = $(oForm).find('#cbo_nuber_record_page').val();
            myClass.loadList(oForm, page, perPage);
            // return false;
        
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').click(function () {
        myClass.delete(oForm)
    });
}
JS_listIndications.prototype.loadevent = function (oForm) {
    var myClass = this;
    $('form#frmAddCategory').find('#btn_create').click(function () {
        myClass.store('form#frmAddCategory');
    })
}
/**
 * Hàm hiển thị modal
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_listIndications.prototype.add = function (oForm) {
    var url = this.urlPath + '/createFormCategory';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#editmodalCategory').html(arrResult);
            $('#editmodalCategory').modal('show');
            $("#status").attr('checked', true);
            myClass.loadevent(oForm);

        }
    });
}
/**
 * Hàm thêm mới
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_listIndications.prototype.store = function (oFormCreate) {
    var url = this.urlPath + '/createCategory';
    var myClass = this;
    var data = $(oFormCreate).serialize();
    if ($("#code_cate").val() == '') {
        var nameMessage = 'Danh mục không được để trống!';
        var icon = 'warning';
        var color = '#f5ae67';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    if ($("#name_category").val() == '') {
        var nameMessage = 'Tên thể loại không được để trống!';
        var icon = 'warning';
        var color = '#f5ae67';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    if ($("#code_category").val() == '') {
        var nameMessage = 'Mã thể loại không được để trống!';
        var icon = 'warning';
        var color = '#f5ae67';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                  var nameMessage = 'Cập nhật thành công!';
                  var icon = 'success';
                  var color = '#f5ae67';
                  NclLib.alerMesage(nameMessage,icon,color);
                  $('#editmodalCategory').modal('hide');
                  myClass.loadList(oFormCreate);
            } else {
                  var nameMessage = arrResult['message'];
                  var icon = 'error';
                  var color = '#f5ae67';
                  NclLib.alerMesage(nameMessage,icon,color);
            }
        }
    });
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_listIndications.prototype.loadList = function (oForm = '#frmIndications', numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadList_Indications';
    var data = $(oForm).serialize();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    NclLib.loadding();

    $.ajax({
        url: url,
        type: "GET",
        // cache: true,
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
            var loadding = NclLib.successLoadding();
            myClass.loadevent(oForm);
        }
    });
}
/**
 * Hàm hiển thị modal edit
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_listIndications.prototype.edit = function (id) {
    var url = this.urlPath + '/appointmentathome/'+ id;
    // var myClass = this;
    var data = '_token=' + $('#frmIndications #_token').val();
    data += '&id=' + id;
    var i = 0;
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            window.location.replace(url);
        }
    });
}
// Xoa mot doi tuong
JS_listIndications.prototype.delete = function (id) {
    var myClass = this;
    var listitem = '';
    // var url = this.urlPath + "/recordtype/" + listitem;
    var url = this.urlPath + '/delete';
    Swal.fire({
        title: 'Bạn có chắc chắn xóa vĩnh viễn chỉ định này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#34bd57',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Xác nhận'
      }).then((result) => {
        if(result.isConfirmed == true){
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: {
                    _token: $('#_token').val(),
                    id: id,
                },
                success: function (arrResult) {
                    if (arrResult['success'] == true) {
                        myClass.loadList();
                        if (result.isConfirmed) {
                            var nameMessage = 'Xóa thành công!';
                            var icon = 'success';
                            var color = '#f5ae67';
                            NclLib.alerMesage(nameMessage,icon,color);
                          }
                    } else {
                        if (result.isConfirmed) {
                            var nameMessage = 'Quá trình xóa đã xảy ra lỗi!';
                            var icon = 'error';
                            var color = '#f5ae67';
                            NclLib.alerMesage(nameMessage,icon,color);
                          }
                    }
                }
            });
        }
      })
}
/**
 * Hàm hiển thị modal edit
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_listIndications.prototype.showDetail = function(id) {
    var url = this.urlPath + '/showDetail';
    var myClass = this;
    var data = '_token=' + $('#frmIndications #_token').val();
    data += '&id=' + id;
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function(arrResult) {
            if (arrResult['success'] == false) {
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                return false;
            }
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            // $("#role_client").change(function(){
            //     myClass.getUserVIP();
            // });
            myClass.loadevent('form#frmAdd');

        }
    });
}
// Xoa mot doi tuong
JS_listIndications.prototype.exportExcel = function (id) {
    var myClass = this;
    var url = this.urlPath + '/exportExcel';
    Swal.fire({
        title: 'Bạn có chắc chắn muốn xuất Excel này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#34bd57',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Xác nhận'
      }).then((result) => {
        if(result.isConfirmed == true){
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: {
                    _token: $('#_token').val(),
                    id: id,
                },
                success: function (arrResult) {
                    if (arrResult['success'] == true) {
                        window.open(arrResult.url);
                        myClass.loadList();
                        if (result.isConfirmed) {
                            var nameMessage = 'Xuất thành công!';
                            var icon = 'success';
                            var color = '#f5ae67';
                            NclLib.alerMesage(nameMessage,icon,color);
                            myClass.loadList(oForm);
                          }
                    } else {
                        if (result.isConfirmed) {
                            var nameMessage = 'Quá trình xuất đã xảy ra lỗi!';
                            var icon = 'error';
                            var color = '#f5ae67';
                            NclLib.alerMesage(nameMessage,icon,color);
                          }
                    }
                }
            });
        }
      })
}