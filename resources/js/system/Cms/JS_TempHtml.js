function JS_TempHtml(obj) {

}

JS_TempHtml.prototype.zend_categories = function (objTree, node) {
    var check_loaded = false;
    if (node.state.loaded) {
        check_loaded = true;
    }
    var htmls = '';
    htmls += '<table id="tb_list_record" class="table table-hover table-striped">';
    htmls += '<thead>';
    htmls += '<tr class="thead-inverse">';
    htmls += '<th class="col-md-1 text-center" style="width:2%;">STT</td>';
    htmls += '<th class="col-md-3">Tên chuyên mục</th>';
    htmls += '<th class="col-md-2">Layout</th>';
    htmls += '<th class="col-md-2">Slug</td>';
    htmls += '<th class="col-md-2 text-center">Trạng thái</td>';
    htmls += '<th class="col-md-1 text-center"></td>';
    htmls += '</tr>';
    htmls += '</thead>';
    htmls += '<tbody>';
    i = 1;
    var _class = v_status = '';
    var id, order, name, slug, status, v_status, layout;
    for (var prop in node.children) {
        if (check_loaded) {
            var objchild = objTree.jstree(true).get_node(node.children[prop]);
            id = objchild.original.id;
            order = objchild.original.order;
            name = objchild.original.name;
            slug = objchild.original.slug;
            layout = objchild.original.layout;
            status = objchild.original.status;
            _class = this.get_node_level(objchild.original.node_lv, objchild.original.type);
        } else {
            id = node.children[prop].id;
            order = node.children[prop].order;
            slug = node.children[prop].slug;
            name = node.children[prop].name;
            status = node.children[prop].status;
            layout = node.children[prop].layout;
            _class = this.get_node_level(node.children[prop].node_lv, node.children[prop].type);
        }
        if (status == '1') {
            v_status = 'Hoạt động';
        } else {
            v_status = 'Không hoạt động';
        }
        htmls += '<tr class="tr-tree-categories" id-categories="' + id + '">';
        htmls += '<td align="center">' + order + '</td>';
        htmls += '<td><i class="' + _class + '"></i> ' + name + '</td>';
        htmls += '<td>' + layout + '</td>';
        htmls += '<td>' + slug + '</td>';
        htmls += '<td align="center">' + v_status + '</td>';
        htmls += '<td><a class="categories-edit" id-categories="' + id + '"><span  class="fas fa-edit"></span></a> | <a class="delete-categories" id-categories="' + id + '" name-categories =  "' + name + '"><span class="fas fa-trash"></span></a></td>';
        htmls += '</tr>';
        i++;
    }
    htmls += '</tbody>';
    htmls += '</table>';
    return htmls;
}

JS_TempHtml.prototype.zend_categories_search = function (arrResult, node) {
    var htmls = '';
    var myClass = this;
    htmls += '<table id="tb_list_record" class="table table-hover">';
    htmls += '<thead>';
    htmls += '<tr class="thead-inverse">';
    htmls += '<th align="center" class="col-md-5">Tên chuyên mục</th>';
    htmls += '<th align="center" class="col-md-2">Slug</td>';
    htmls += '<th align="center" class="col-md-4">Địa chỉ</td>';
    htmls += '<th align="center" class="col-md-1"></td>';
    htmls += '</tr>';
    htmls += '</thead>';
    htmls += '<tbody>';
    htmls += '</tbody>';
    i = 1;
    var _class = this.get_node_level(3, '');
    $.each(arrResult, function (key, value) {
        htmls += '<tr>';
        htmls += '<td><i class="' + _class + '"></i> ' + value.C_ORDER + '</td>';
        htmls += '<td><i class="' + _class + '"></i> ' + value.C_NAME + '</td>';
        htmls += '<td>' + value.C_SLUG + '</td>';
        htmls += '<td>' + value.C_ADDRESS + '</td>';
        htmls += '<td><a class="categories-edit" id-categories="' + value.PK_CATEGORIES + '"><span  class="fas fa-edit"></span></a> | <a class="categories-delete" id-categories="' + value.PK_CATEGORIES + '"><span class="fas fa-trash categories-delete"></span></a></td>';
        htmls += '</tr>';
        i++;
    });
    htmls += '</table>';
    return htmls;
}

JS_TempHtml.prototype.get_node_level = function (node, type) {
    var icon = '';
    if (node == 1) {
        icon = 'fas fa-university folder-lv2';
    } else if (node == 2) {
        icon = 'fas fa-university folder-lv2';
    } else if (node == 3) {
        if (type == 'PHUONG_XA') {
            icon = 'fas fa-university folder-lv3';
        } else {
            icon = 'fas fa-square folder-lv3';
        }
    } else if (node == 4) {
        icon = 'fas fa-folder folder-lv4';
    } else if (node == 5) {
        icon = 'fas fa-folder folder-lv5';
    } else if (node == 0) {
        icon = 'fas fa-university folder-lv0';
    }
    return icon;
}


JS_TempHtml = new JS_TempHtml();