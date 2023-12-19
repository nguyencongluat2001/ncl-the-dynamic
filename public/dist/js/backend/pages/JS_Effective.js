function JS_Effective(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-recommended');
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_Effective.prototype.loadIndex = function () {
    var myClass = this;
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmRecommended_index';
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
JS_Effective.prototype.loadevent = function (oForm) {
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
JS_Effective.prototype.add = function (oForm) {
    var url = this.urlPath + '/add';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#addfile').html(arrResult);
            $('#addfile').modal('show');
            $("#status").attr('checked', true);
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
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
JS_Effective.prototype.store = function (oFormCreate) {
    var url = this.urlPath + '/create';
    var myClass = this;
    var data = $(oFormCreate).serialize();
    var check = myClass.checkValidate();
    if(check == false){
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
                  NclLib.alertMessageBackend('success', 'Thông báo', nameMessage);
                  $('#addfile').modal('hide');
                  myClass.loadList(oFormCreate);
            } else {
                  var nameMessage = arrResult['message'];
                  NclLib.alertMessageBackend('danger', 'Thông báo', nameMessage);
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
JS_Effective.prototype.loadList = function (oForm = '#frmRecommended_index', numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var data = $(oForm).serialize();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    $.ajax({
        url: url,
        type: "GET",
        // cache: true,
        data: data,
        success: function (arrResult) {
            $("#table-container-recommended").html(arrResult);
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
JS_Effective.prototype.edit = function (id) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = '_token=' + $('#frmRecommended_index #_token').val();
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
            $('#addfile').html(arrResult);
            $('#addfile').modal('show');
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $('form#frmAdd').find('#btn_create').click(function () {
                myClass.store('form#frmAdd');
            })

        }
    });
}
// Xoa mot doi tuong
JS_Effective.prototype.delete = function (oForm) {
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
    var url = this.urlPath + '/delete';
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
JS_Effective.prototype.addrow = function() {
    var numberRow = $("#body_data tr").length;
    var id = broofa();
    var html = '';
    html += '<tr>';
    // checkbox
    html += '<td align="center"><input type="checkbox" name="chk_item_id" value="' + id + '"></td>';
    // stt
    html += '<td align="center">' + (parseInt(numberRow) + 1) + '</td>';
    // closing_percentage
    html += '<td class="td_closing_percentage_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'closing_percentage\')">';
    html += '<span id="span_closing_percentage_' + id + '" class="span_closing_percentage_' + id + '"></span>';
    html += '</td>';
    // code_cp
    html += '<td class="td_code_cp_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'code_cp\')">';
    html += '<span id="span_code_cp_' + id + '" class="span_code_cp_' + id + '"></span>';
    html += '</td>';
    // code_category
    html += '<td class="td_code_category_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'code_category\')">';
    html += '<span id="span_code_category_' + id + '" class="span_code_category_' + id + '"></span>';
    html += '</td>';
    // created_at
    html += '<td onclick="{select_row(this);}"></td>';
    // percent_of_assets
    html += '<td class="td_percent_of_assets_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'percent_of_assets\')">';
    html += '<span id="span_percent_of_assets_' + id + '" class="span_percent_of_assets_' + id + '"></span>';
    html += '</td>';
    // price
    html += '<td class="td_price_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'price\')">';
    html += '<span id="span_price_' + id + '" class="span_price_' + id + '"></span>';
    html += '</td>';
    // date_close
    html += '<td class="td_date_close_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'date_close\')">';
    html += '<span id="span_date_close_' + id + '" class="span_date_close_' + id + '"></span>';
    html += '</td>';
    // price_close
    html += '<td class="td_price_close_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'price_close\')">';
    html += '<span id="span_price_close_' + id + '" class="span_price_close_' + id + '"></span>';
    html += '</td>';
    // profit_and_loss
    html += '<td class="td_profit_and_loss_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'profit_and_loss\')">';
    html += '<span id="span_profit_and_loss_' + id + '" class="span_profit_and_loss_' + id + '"></span>';
    html += '</td>';
    // note
    html += '<td class="td_note_' + id + '" onclick="{select_row(this);}" ondblclick="click2(\'' + id + '\', \'note\')">';
    html += '<span id="span_note_' + id + '" class="span_note_' + id + '"></span>';
    html += '</td>';
    // status
    // html += '<td onclick="{select_row(this);}" align="center">';
    // html += '<label class="custom-control custom-checkbox p-0 m-0 pointer " style="cursor: pointer;">';
    // html += '<input type="checkbox" hidden class="custom-control-input toggle-status" id="status_' + id + '" data-id="' + id + '" checked>';
    // html += '<span class="custom-control-indicator p-0 m-0" onclick="JS_Effective.changeStatus(\'' + id + '\')"></span>';
    // html += '</label></td>';
    // edit
    html += '<td align="center"><span class="text-cursor text-warning" onclick="JS_Effective.edit(\''+id+'\')"><i class="fas fa-edit"></i></span></td>';
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
            JS_Effective.updateColumn(id, type, $(".span_" + type + '_' + id).html());
        }
    })
}
/**
 * Cập nhật khi ở màn hình hiển thị danh sách
 */
JS_Effective.prototype.updateColumn = function(id, column, value = '') {
    var myClass = this;
    var url = myClass.urlPath + '/updateColumn';
    var data = 'id=' + id;
    data += '&_token=' + $('#frmRecommended_index').find('#_token').val();
    if(column == 'code_cp'){ data += '&code_cp=' + value }
    else if(column == 'code_category'){ data += '&code_category=' + value; }
    else if(column == 'percent_of_assets'){ data += '&percent_of_assets=' + value; }
    else if(column == 'closing_percentage'){ data += '&closing_percentage=' + value; }
    else if(column == 'price'){ data += '&price=' + value; }
    else if(column == 'date_close'){ data += '&date_close=' + value; }
    else if(column == 'price_close'){ data += '&price_close=' + value; }
    else if(column == 'profit_and_loss'){ data += '&profit_and_loss=' + value; }
    else if(column == 'note'){ data += '&note=' + value; }
    $.ajax({
        url: url,
        data: data,
        type: "POST",
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                NclLib.alertMessageBackend('success', 'Thông báo', arrResult['message']);
                if(column == 'order'){
                    JS_Effective.loadList();
                }
            } else {
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
                JS_Effective.loadList();
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
JS_Effective.prototype.changeStatus = function(id){
    var myClass = this;
    var url = myClass.urlPath + '/changeStatus';
    var data = '_token=' + $("#frmRecommended_index #_token").val();
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
/**
 * Kiểm tra
 */
JS_Effective.prototype.checkValidate = function(){
    if($("#closing_percentage").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', '% Chốt không được để trống!');
        $("#closing_percentage").focus();
        return false;
    }
    if($("#code_cp").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Mã cổ phiếu không được để trống!');
        $("#code_cp").focus();
        return false;
    }
    if($("#code_category").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Nhóm ngành không được để trống!');
        $("#code_category").focus();
        return false;
    }
    if($("#percent_of_assets").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', '% Tài sản không được để trống!');
        $("#percent_of_assets").focus();
        return false;
    }
    if($("#price").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Giá mua không được để trống!');
        $("#price").focus();
        return false;
    }
    if($("#date_close").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Ngày chốt không được để trống!');
        $("#date_close").focus();
        return false;
    }
    if($("#price_close").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Giá chốt không được để trống!');
        $("#price_close").focus();
        return false;
    }
    if($("#profit_and_loss").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Lãi lỗ không được để trống!');
        $("#profit_and_loss").focus();
        return false;
    }
}