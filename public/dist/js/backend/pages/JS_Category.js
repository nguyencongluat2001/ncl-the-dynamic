function JS_Category(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-category');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_Category.prototype.loadIndex = function () {
    var myClass = this;
    // $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmCategory_index';
    var oFormCreate = 'form#frmAdd';
    myClass.loadList(oForm);

    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
     // form load
     $(oForm).find('#role').change(function () {
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
JS_Category.prototype.loadevent = function (oForm) {
    var myClass = this;
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
}
/**
 * Hàm hiển thị modal
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Category.prototype.add = function (oForm) {
    var url = this.urlPath + '/createForm';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $('#editmodal').html(arrResult);
            $('#editmodal').modal('show');
            $("#status").attr('checked', true);
            myClass.loadevent(oForm);

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
JS_Category.prototype.store = function (oFormCreate) {
    var url = this.urlPath + '/create';
    var myClass = this;
    var data = $(oFormCreate).serialize();
    if ($("#name").val() == '') {
        var nameMessage = 'Tên danh mục không được để trống!';
        NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    if ($("#code_cate").val() == '') {
        var nameMessage = 'Mã danh mục không được để trống!';
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
                  $('#editmodal').modal('hide');
                  myClass.loadList(oFormCreate);

            } else {
                  var nameMessage = arrResult['message'];
                  NclLib.alertMessageBackend('danger', 'Lỗi', nameMessage);
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
JS_Category.prototype.loadList = function (oForm, numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var data = 'search=' + $("#search").val();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
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
JS_Category.prototype.edit = function (id) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = '_token=' + $('#frmCategory_index #_token').val();
    data += '&id=' + id;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            if(arrResult['success'] == false){
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                return false;
            }
            $('#editmodal').html(arrResult);
            $('#editmodal').modal('show');
            myClass.loadevent('form#frmAdd');

        }
    });
}
// Xoa mot doi tuong
JS_Category.prototype.delete = function (oForm) {
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
        var nameMessage = 'Bạn chưa chọn danh mục để xóa!';
        NclLib.alertMessageBackend('warning', 'Cảnh báo', nameMessage);
        return false;
    }
    var data = $(oForm).serialize();
    // var url = this.urlPath + "/recordtype/" + listitem;
    var url = this.urlPath + '/delete';
    Swal.fire({
        title: 'Bạn có chắc chắn xóa vĩnh viễn danh mục này không?',
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
                    listitem: listitem,
                },
                success: function (arrResult) {
                    if (arrResult['success'] == true) {
                        if (result.isConfirmed) {
                              var nameMessage = 'Xóa thành công!';
                              NclLib.alertMessageBackend('success', 'Thông báo', nameMessage);
                              myClass.loadList(oForm);
                          }
                    } else {
                        if (result.isConfirmed) {
                              var nameMessage = 'Quá trình xóa đã xảy ra lỗi!';
                              NclLib.alertMessageBackend('danger', 'Lỗi', nameMessage);
                          }
                    }
                }
            });
        }
      })
}
/**
 * Thêm một dòng mới trên danh sách
 */
JS_Category.prototype.addrow = function() {
    var numberRow = $("#body_data tr").length;
    var id = broofa();
    var html = '';
    html += '<tr>';
    // checkbox
    html += '<td align="center"><input type="checkbox" name="chk_item_id" value="' + id + '"></td>';
    // stt
    html += '<td align="center">' + (parseInt(numberRow) + 1) + '</td>';
    // code_cate
    html += '<td class="td_code_cate_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'code_cate\')">';
    html += '<span id="span_code_cate_' + id + '" class="span_code_cate_' + id + '"></span>';
    html += '</td>';
    // name
    html += '<td class="td_name_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'name\')">';
    html += '<span id="span_name_' + id + '" class="span_name_' + id + '"></span>';
    html += '</td>';
    // decision
    html += '<td class="td_decision_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'decision\')">';
    html += '<span id="span_decision_' + id + '" class="span_decision_' + id + '"></span>';
    html += '</td>';
    // order
    html += '<td class="td_order_' + id + '" onclick="{select_row(this);}" align="center" ondblclick="click2(\'' + id + '\', \'order\')">';
    html += '<span id="span_order_' + id + '" class="span_order_' + id + '">' + (parseInt(numberRow) + 1) + '</span>';
    html += '</td>';
    // status
    html += '<td onclick="{select_row(this);}" align="center">';
    html += '<label class="custom-control custom-checkbox p-0 m-0 pointer " style="cursor: pointer;">';
    html += '<input type="checkbox" hidden class="custom-control-input toggle-status" name="status" id="status_' + id + '" data-id="' + id + '" checked>';
    html += '<span class="custom-control-indicator p-0 m-0" onclick="JS_Category.changeStatus(\'' + id + '\')"></span>';
    html += '</label></td>';
    // edit
    html += '<td align="center"><span class="text-cursor text-warning" onclick="JS_Category.edit(\''+id+'\')"><i class="fas fa-edit"></i></span></td>';
    html += '</tr>';
    $("#body_data").append(html);
}
/**
 * Tạo một id mới ngẫu nhiên
 */
function broofa() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}
/**
 * Sự kiện khi nhấn 2 lần vào dòng td để sửa
 */
function click2(id, type) {
    $(".td_"+type+"_" + id).removeAttr('ondblclick');
    var text = $("#span_"+type+"_" + id).html();
    $("#"+type+"_" + id).removeAttr('hidden');
    $("#span_"+type+"_" + id).html('<textarea name="'+type+'" id="'+type+'_' + id + '" rows="2">'+text+'</textarea>');
    $("#"+type+"_" + id).focus();
    $("#span_"+type+"_" + id).removeAttr('id');
    $("#"+type+"_" + id).focusout(function(){
        $(".td_"+type+"_" + id).attr('ondblclick', "click2('"+id+"', '"+type+"')");
        $("#"+type+"_" + id).attr('hidden', true);
        $(".span_"+type+"_" + id).attr('id', 'span_'+type+'_' + id);
        $(".span_"+type+"_" + id).html($("#"+type+"_" + id).val());
        if(text != $(".span_" + type + '_' + id).html()){
            JS_Category.updateCategory(id, type, $(".span_" + type + '_' + id).html());
        }
    })
}
/**
 * Cập nhật khi ở màn hình hiển thị danh sách
 */
JS_Category.prototype.updateCategory = function(id, column, value = '') {
    var myClass = this;
    var url = myClass.urlPath + '/updateCategory';
    var data = 'id=' + id;
    data += '&_token=' + $('#frmCategory_index').find('#_token').val();
    if(column == 'code_cate'){ data += '&code_cate=' + (column == 'code_cate' ? value : ""); }
    else if(column == 'name'){ data += '&name=' + value; }
    else if(column == 'decision'){ data += '&decision=' + value; }
    else if(column == 'order'){ data += '&order=' + value; }
    $.ajax({
        url: url,
        data: data,
        type: "POST",
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                NclLib.alertMessageBackend('success', 'Thông báo', arrResult['message']);
                if(column == 'order'){
                    JS_Category.loadList();
                }
            } else {
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                JS_Category.loadList();
            }
        }, error: function(e){
            console.log(e);
            NclLib.successLoadding();
        }
    });
    $("#" + id).prop('readonly');
}
/**
 * Thay đổi trạng thái
 */
JS_Category.prototype.changeStatusCate = function(id){
    var myClass = this;
    var url = myClass.urlPath + '/changeStatusCate';
    var data = '_token=' + $("#frmCategory_index #_token").val();
    data += '&status=' + ($("#status_" + id).is(":checked") == true ? 0 : 1);
    data += '&id=' + id;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult){
            if(arrResult['success'] == true){
                NclLib.alertMessageBackend('success', 'Thông báo', arrResult['message']);
            }else{
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
            }
            NclLib.successLoadding();
        }, error: function(e){
            console.log(e);
            NclLib.successLoadding();
        }
    });
}