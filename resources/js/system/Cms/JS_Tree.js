/**
 * Zend Tree
 *
 * @author Toanph <skype: toanph155>
 */
function JS_Tree(baseUrl, module, controller, objTree) {
    this.objTree = objTree;
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    this.loadding = EfyLib.loadding();
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}

/**
 * Zend tree with ajax
 */
JS_Tree.prototype.zend_tree = function () {
    var myClass = this;
    var objTree = myClass.objTree;
    var url = myClass.urlPath + '/getcategories';
    objTree.on('changed.jstree', function (e, data) {
        var node;
        for (i = 0, j = data.selected.length; i < j; i++) {
            node = data.instance.get_node(data.selected[i]);
        }
        if (node) {
            objTree.jstree('create_node', node);
            myClass.zendList(node);
        }
    }).jstree({
        "plugins": ["contextmenu", "json_data", "dnd", "search"],
        'core': {
            'data': {
                'url': function (node) {
                    return url + '?root=' + node.parents.length;
                },
                "data": function (node) {
                    return {"id": node.id};
                },
                "success": function (node) {
                    myClass.zendList(node);
                },
                'dataType': "json"
            }
        },
        "search": {
            "ajax": {
                "url": "Tree/Search",
                "data": function (str) {
                    return {
                        "operation": "search",
                        "q": str
                    };
                }
            }
        },
        'contextmenu': {
            'select_node': false,
            'items': myClass.rightclick
        }
    });
}

/**
 * Zend list with node jstree
 */
JS_Tree.prototype.zendList = function (node) {
    var myClass = this;
    var node_lv = 0;
    if (node.state.loaded) {
        JS_Tree.zend_breadcrumb_tree(node);
        node_lv = parseInt(node.original.node_lv);
        var id = node.original.id;
    } else {
        node_lv = parseInt(node.node_lv);
        var id = node.id;
        if (!node.state.opened) {
            JS_Tree.zend_breadcrumb_tree(node);
        }
    }
    JS_Tree.ClosedNodeTree(node);
    $('#search_text').val('');
    switch (node_lv) {
        case 1:
            // Don vi
            $("#btn-add").html('<i class="fas fa-plus"></i> Thêm chuyên mục');
            $("#check_add").val('categories');
            $('#check_add').attr('id-categories', id);
            htmls = JS_TempHtml.zend_categories(this.objTree, node);
            $('#zend_list').html(htmls);
            Js_Categories.loadEvenTree();
            break;
        case 2:
            // Phong ban
            $("#check_add").val('categories');
            $('#check_add').attr('id-categories', id);
            $("#btn-add").html('<i class="fas fa-plus"></i> Thêm chuyên mục con');
            htmls = JS_TempHtml.zend_categories(this.objTree, node);
            $('#zend_list').html(htmls);
            Js_Categories.loadEvenTree();
            break;
        default :
            $("#check_add").val('categories');
            $('#check_add').attr('id-categories', id);
            $("#btn-add").html('<i class="fas fa-plus"></i> Thêm chuyên mục con');
            htmls = JS_TempHtml.zend_categories(this.objTree, node);
            $('#zend_list').html(htmls);
            Js_Categories.loadEvenTree();
            break;
    }
}
JS_Tree.prototype.ClosedNodeTree = function (node) {
    // close all other nodes
    var myClass = this;
    var objTree = myClass.objTree;
    var nodesToKeepOpen = [];
    $('#' + node.id).parents('.jstree-node').each(function () {
        nodesToKeepOpen.push(this.id);
    });
    nodesToKeepOpen.push(node.id);
    $('.jstree-node').each(function () {
        if (nodesToKeepOpen.indexOf(this.id) === -1) {
            objTree.jstree().close_node(this.id);
        }
    })
    // open node
    objTree.jstree().open_node(node.id);
}

/**
 * show hide node breadcrumb
 */
JS_Tree.prototype.zend_breadcrumb_tree = function (node) {
    var idcategories, htmlheader, idparent, idroot;
    var parents = [];
    var i = 0;
    for (var prop in node.parents) {
        if (node.parents[prop] !== node.parents[parseInt(prop) + 1] && node.id !== node.parents[prop]) {
            parents[i] = node.parents[prop];
            i++;
        }
    }
    // hide all header node
    $('.tree-header').hide();
    var node_lv = 1;
    for (j = parents.length - 1; j >= 0; j--) {
        if (j == 0) {
            idparent = parents[j];
        }
        if (j == parents.length - 2) {
            idroot = parents[j];
        }
        // show parrent node
        if (parents[j] !== '#') {
            htmlheader = $('#' + parents[j] + '_anchor').html();
            htmlheader = '<a id-categories="' + parents[j] + '" class="tree-header-breadcrumb">' + htmlheader + '</a>';
            $('#tree-header-lv' + node_lv).html(htmlheader);
            $('#tree-header-lv' + node_lv).show();
            //console.log(parents[j]);
            node_lv++;
        }
        // show node
        htmlheader = $('#' + node.id + '_anchor').html();
        $('#tree-header-lv' + node_lv).html(htmlheader);
        $('#tree-header-lv' + node_lv).show();
    }
    $("#tree-header-root").attr("id-categories", idroot);
    // set id-categories for prev
    $("#tree-header-prev").attr("id-current", node.id);
    $("#tree-header-prev").attr("id-categories", idparent);
}

/**
 * click object node
 */
JS_Tree.prototype.rightclick = function (node) {
    // check if node categories or node user
    var myClass = Js_Categories;
    var categoriess = {
        createCategories: {
            label: "Thêm chuyên mục",
            icon: "fas fa-folder",
            //action: self.addinfo(node.id,'add')
            action: function (obj) {
                myClass.add_categories(node.id, 'add');
            }
        }
    }
    var common = {
        modify: {
            label: "Sửa",
            icon: "fas fa-edit",
            action: function (obj) {
                myClass.edit_common(node.original.name, node.id, 'categories');
            }
        }
    };
    var _delete = {
        delete: {
            label: "Xóa",
            icon: "fas fa-trash",
            action: function (obj) {
                myClass.delete_common(node.original.name, node.id, 'categories');
            }
        }
    }
    // check if categories is root
    if (node.parents.length == 1) {
        $.extend(categoriess, common);
        return categoriess;
    } else {
        $.extend(categoriess, common);
        $.extend(categoriess, _delete);
        return categoriess;
    }
}


