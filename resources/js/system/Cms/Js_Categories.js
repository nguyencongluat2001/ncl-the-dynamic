/**
 * Xử lý Js về người dùng
 *
 * @author Toanph <skype: toanph155>
 */
 function Js_Categories(baseUrl, module, controller) {
    this.objTree = $("#jstree-tree");
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    JS_Tree = new JS_Tree(baseUrl, 'system/cms', 'zendtree', this.objTree);
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}
/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
Js_Categories.prototype.loadIndex = function () {
    $(document).ajaxSend(function () {
        EfyLib.showmainloadding();
    });
    $(document).ajaxStop(function () {
        EfyLib.successLoadImage();
    });
    var myClass = this;
    myClass.loadListTree();
    $("#btn-add").click(function () {
        var idcategories = $('#check_add').attr('id-categories');
        myClass.add(idcategories, 'categories');
    });
    $("#search_text").keyup(function () {
        var search = $('#search_text').val();
        myClass.search_current_tree(search);
    });
    
    $('.chzn-select').chosen({ height: '100%', width: '100%' });

}
Js_Categories.prototype.loadEvenTree = function () {
    var myClass = this;
    $('.categories-edit').unbind("click");
    $('.categories-edit').click(function () {
        // lay Id cua categories
        var id = $(this).attr('id-categories');
        myClass.edit(name, id, 'categories');
        //alert(id);
    });

    $('.delete-categories').unbind("click");
    $('.delete-categories').click(function () {
        // lay Id cua categories
        var id = $(this).attr('id-categories');
        var name = $(this).attr('name-categories');
        myClass.delete(name, id, 'categories');
        //
    });

    $('.user-edit').unbind("click");
    $('.user-edit').click(function () {
        // lay Id cua categories
        var id = $(this).attr('id-user');
        //
        myClass.edit('', id, 'user');
    });

    $('.user-delete').unbind("click");
    $('.user-delete').click(function () {
        // lay Id cua categories
        var id = $(this).attr('id-user');
        var name = $(this).attr('name-user');
        //
        myClass.delete(name, id, 'user');
    });

    $('.tr-tree-categories').unbind("click");
    $('.tr-tree-categories').click(function () {
        $('.tr-tree-categories').removeClass('selected');
        $(this).addClass('selected');
    });

    $('.tr-tree-user').unbind("click");
    $('.tr-tree-user').click(function () {
        $('.tr-tree-user').removeClass('selected');
        $(this).addClass('selected');
    });

    $('.tr-tree-user').unbind("dblclick");
    $('.tr-tree-user').dblclick(function () {
        var id = $(this).attr('id-user');
        myClass.edit('', id, 'user');
    });

    $('.tr-tree-categories').unbind("dblclick");
    $('.tr-tree-categories').dblclick(function () {
        var id = $(this).attr('id-categories');
        var node = $('#jstree-tree').jstree(true).get_node(id);
        $('#jstree-tree').jstree('create_node', node);
        JS_Tree.zendList(node);
    });

    $('.tree-header-breadcrumb').unbind("click");
    $('.tree-header-breadcrumb').click(function () {
        var id = $(this).attr('id-categories');
        var node = $('#jstree-tree').jstree(true).get_node(id);
        JS_Tree.zendList(node);
    });

}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
Js_Categories.prototype.loadListTree = function (currentPage = 1, perPage = 15) {
    JS_Tree.zend_tree();
}
Js_Categories.prototype.search_current_tree = function (search) {
    var input, filter, table, tr, td, i, tendonvi, madonvi, chuoitimkiem;
    $('#tb_list_record > tbody  > tr').each(function () {
        tendonvi = $(this).find("td").eq(1).text();
        madonvi = $(this).find("td").eq(2).text();
        chuoitimkiem = tendonvi + ' ' + madonvi;
        if (chuoitimkiem.toUpperCase().indexOf(search.toUpperCase()) >= 0) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}
/**
 * Thêm mới người dùng
 *
 * @param node (đối tượng click)
 *
 * @return object
 */
Js_Categories.prototype.add = function (id, type) {
    var myClass = this;
    var url = myClass.urlPath + '/create';
    var data = {
        id: id,
        type: type,
    }
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $('#btn_update').click(function () {
                myClass.update();
            });
            $("#addLayout").click(function(){
                myClass.addList('layout', 'DM_LAYOUT', 'Danh mục layout');
            });
            $("#addCategoryType").click(function(){
                myClass.addList('category_type', 'DM_LOAI_CHUYEN_MUC', 'Danh mục loại chuyên mục');
            });
        }
    });
}
/**
 * Sửa mới người dùng
 *
 * @param node (đối tượng click)
 *
 * @return object
 */
Js_Categories.prototype.edit = function (name, id, type) {
    var myClass = this;
    var url = myClass.urlPath + '/edit';
    var data = {
        itemId: id
    }
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            $('#btn_update').click(function () {
                myClass.update();
            });
            $("#addLayout").click(function(){
                myClass.addList('layout', 'DM_LAYOUT', 'Danh mục layout');
            });
            $("#addCategoryType").click(function(){
                myClass.addList('category_type', 'DM_LOAI_CHUYEN_MUC', 'Danh mục loại chuyên mục');
            });
        }
    });
}
/**
 * Thêm mới layout
 */
Js_Categories.prototype.addList = function(id, code, name){
    var myClass = this;
    var url = myClass.urlPath + '/addList';
    var data = 'code=' + code;
    data += '&name=' + name;
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function(arrResult){
            $("#addList").html(arrResult);
            $("#addList").modal('show');
            $("#addmodal").modal('hide');
            $(".close-list").click(function(){
                $("#addList").html('');
                $("#addList").modal('hide');
                $("#addmodal").modal('show');
            });
            $("#btn_updateList").click(function(){
                myClass.saveList(id, true);
            });
            $("#btn_updateAddList").click(function(){
                myClass.saveList(id, false);
            });
        }
    });
}
/**
 * Thêm mới loại chuyên mục
 */
Js_Categories.prototype.saveList = function(id, type){
    var url = this.urlPath + '/saveList';
    var data = $("#frmAddListType").serialize();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType: "JSON",
        success: function (arrResult) {
            if (arrResult['success']) {
                $("#frmAddListType")[0].reset();
                if(type){
                    $("#addList").html('');
                    $("#addList").modal('hide');
                    $("#addmodal").modal('show');
                }
                $("#" + id).append('<option value="' + arrResult['data'].code + '">' + arrResult['data'].name + '</option>');
                $("#" + id).trigger("chosen:updated");
                $("#frmAddListType").find('#order').val(parseInt(arrResult['data'].order) + 1);
            } else {
                EfyLib.alertMessage('danger', 'Thất bại', arrResult['message'], 6000);
            }
        }
    });
}
/**
 * Cập nhật
 */
Js_Categories.prototype.update = function () {
    var oForm = 'form#frmAddCategories';
    var myClass = this;
    var url = this.urlPath + '/update';
    var data = $(oForm).serialize();
    var role = $('#role').val();
    var check = myClass.checkValidate();
    if(!check){
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType: "JSON",
        success: function (arrResult) {
            if (arrResult['success']) {
                $('#addmodal').modal('hide');
                var node = $('#jstree-tree').jstree(true).get_node(arrResult['parent_id']);
                node.state.loaded = false;
                $('#jstree-tree').jstree('create_node', node);
            } else {
                EfyLib.alertMessage('danger', 'Thất bại', arrResult['message'], 6000);
                $("#message-infor").addClass('text-white');
            }
        }
    });
}
/**
 * Xóa
 */
Js_Categories.prototype.delete = function (name, id, type) {
    var myClass = this;
    var check = false;
    var url = this.urlPath + '/delete';
    var data = {
        itemId: id
    }
    if (confirm("Bạn có chắc chắn muốn xóa chuyên mục: " + name)) {
        check = true;
    }
    if (check) {
        $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: data,
            success: function (arrResult) {
                if (arrResult['success']) {
                    EfyLib.alertMessage('success', 'Thành công', arrResult['message'], 6000);
                    var node = $('#jstree-tree').jstree(true).get_node(arrResult['parent_id']);
                    node.state.loaded = false;
                    $('#jstree-tree').jstree('create_node', node);
                } else {
                    EfyLib.alertMessage('danger', 'Thất bại', arrResult['message'], 6000);
                }
            }
        });
    };
}
/**
 * Kiểm tra dữ liệu đầu vào
 */
Js_Categories.prototype.checkValidate = function(){
    var oForm = 'form#frmAddCategories';
    if($(oForm).find("#name").val() == ''){
        EfyLib.alertMessage('warning', 'Cảnh báo', 'Tên chuyên mục không được để trống!', 5000);
        $(oForm).find("#name").focus();
        return false;
    }
    if($(oForm).find("#id_menu").val() == ''){
        EfyLib.alertMessage('warning', 'Cảnh báo', 'ID chuyên mục không được để trống!', 5000);
        $(oForm).find("#id_menu").focus();
        return false;
    }
    if($(oForm).find("#slug").val() == ''){
        EfyLib.alertMessage('warning', 'Cảnh báo', 'Đường dẫn không được để trống!', 5000);
        $(oForm).find("#slug").focus();
        return false;
    }
    if($(oForm).find("#layout").val() == ''){
        EfyLib.alertMessage('warning', 'Cảnh báo', 'Layout không được để trống!', 5000);
        $(oForm).find("#layout").focus();
        return false;
    }
    if($(oForm).find("#order").val() == ''){
        EfyLib.alertMessage('warning', 'Cảnh báo', 'Thứ tự không được để trống!', 5000);
        $(oForm).find("#order").focus();
        return false;
    }
    return true;
}
