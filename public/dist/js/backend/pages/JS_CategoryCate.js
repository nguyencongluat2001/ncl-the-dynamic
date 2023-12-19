function JS_CategoryCate(baseUrl, module, controller) {
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
JS_CategoryCate.prototype.loadIndex = function () {
    var myClass = this;
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmCategoryCate_index';
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
JS_CategoryCate.prototype.loadevent = function (oForm) {
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
JS_CategoryCate.prototype.add = function (oForm) {
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
JS_CategoryCate.prototype.store = function (oFormCreate) {
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
JS_CategoryCate.prototype.loadList = function (oForm = '#frmCategoryCate_index', numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadListCategory';
    var data = $(oForm).serialize();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    $.ajax({
        url: url,
        type: "GET",
        // cache: true,
        data: data,
        success: function (arrResult) {
            $("#table-container-category").html(arrResult);
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
JS_CategoryCate.prototype.edit = function (id) {
    var url = this.urlPath + '/editCategory';
    var myClass = this;
    var data = '_token=' + $('#frmCategoryCate_index #_token').val();
    data += '&id=' + id;
    var i = 0;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            if(arrResult['success'] == false){
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                return false;
            }
            $('#editmodalCategory').html(arrResult);
            $('#editmodalCategory').modal('show');
            $('form#frmAddCategory').find('#btn_create').click(function () {
                myClass.store('form#frmAddCategory');
            })

        }
    });
}
// Xoa mot doi tuong
JS_CategoryCate.prototype.delete = function (oForm) {
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
        var nameMessage = 'Bạn chưa chọn thể loại để xóa!';
        var icon = 'warning';
        var color = '#f5ae67';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    var data = $(oForm).serialize();
    // var url = this.urlPath + "/recordtype/" + listitem;
    var url = this.urlPath + '/deleteCategory';
    Swal.fire({
        title: 'Bạn có chắc chắn xóa vĩnh viễn thể loại này không?',
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
                            var icon = 'success';
                            var color = '#f5ae67';
                            NclLib.alerMesage(nameMessage,icon,color);
                            myClass.loadList(oForm);
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
 * Thêm một dòng mới trên danh sách
 */
JS_CategoryCate.prototype.addrow = function() {
    var numberRow = $("#body_data tr").length;
    var id = broofa();
    var html = '';
    html += '<tr>';
    // checkbox
    html += '<td align="center"><input type="checkbox" name="chk_item_id" value="' + id + '"></td>';
    // stt
    html += '<td align="center">' + (parseInt(numberRow) + 1) + '</td>';
    // code_category
    html += '<td class="td_code_category_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'code_category\')">';
    html += '<span id="span_code_category_' + id + '" class="span_code_category_' + id + '"></span>';
    html += '</td>';
    // name_category
    html += '<td class="td_name_category_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'name_category\')">';
    html += '<span id="span_name_category_' + id + '" class="span_name_category_' + id + '"></span>';
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
    html += '<input type="checkbox" hidden class="custom-control-input toggle-status" id="status_' + id + '" data-id="' + id + '" checked>';
    html += '<span class="custom-control-indicator p-0 m-0" onclick="JS_CategoryCate.changeStatusCategoryCate(\'' + id + '\')"></span>';
    html += '</label></td>';
    // edit
    html += '<td align="center"><span class="text-cursor text-warning" onclick="JS_CategoryCate.edit(\''+id+'\')"><i class="fas fa-edit"></i></span></td>';
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
            JS_CategoryCate.updateCategoryCate(id, type, $(".span_" + type + '_' + id).html());
        }
    })
}
/**
 * Cập nhật khi ở màn hình hiển thị danh sách
 */
JS_CategoryCate.prototype.updateCategoryCate = function(id, column, value = '') {
    var myClass = this;
    var url = myClass.urlPath + '/updateCategoryCate';
    var data = 'id=' + id;
    data += '&_token=' + $('#frmCategoryCate_index').find('#_token').val();
    data += '&cate=' + $('#frmCategoryCate_index').find('#cate').val();
    if(column == 'code_category'){ data += '&code_category=' + (column == 'code_category' ? value : ""); }
    else if(column == 'name_category'){ data += '&name_category=' + value; }
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
                    JS_CategoryCate.loadList();
                }
            } else {
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                JS_CategoryCate.loadList();
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
JS_CategoryCate.prototype.changeStatusCategoryCate = function(id){
    var myClass = this;
    var url = myClass.urlPath + '/changeStatusCategoryCate';
    var data = '_token=' + $("#frmCategoryCate_index #_token").val();
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