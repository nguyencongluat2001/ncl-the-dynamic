function JS_TempHtml(obj) {
}

JS_TempHtml.prototype.zend_user = function (arrResult) {
	var htmls = '';
	htmls += '<table id="tb_list_record" class="table table-striped table-hover table-bordered table-bordered">';
	htmls += '<thead>';
	htmls += '<tr class="thead-inverse">';
	htmls += '<th align="center" style="width:2%;">STT</td>';
	htmls += '<th style="text-align: center">Tên người dùng</th>';
	htmls += '<th style="text-align: center">Tên đăng nhập</td>';
	htmls += '<th style="text-align: center">Vai trò</td>';
	htmls += '<th style="text-align: center">Trạng thái</td>';
	htmls += '<th style="text-align: center">Hành động</td>';
	htmls += '</tr>';
	htmls += '</thead>';
	htmls += '<tbody>';
	i = 1;
	var role = status = '';
	var name = '';
	$.each(arrResult, function (key, value) {
		name = value.position + ' - ' + value.name;
		role = 'Người dùng';
		if (value.role == 'ADMIN_SYSTEM') {
			role = 'Quản trị hệ thống';
		} else if (value.role == 'ADMIN_OWNER') {
			role = 'Quản trị đơn vị triển khai';
		} else if (value.role == 'ADMIN_REPORT') {
			role = 'Quản trị báo cáo';
		}
		if (value.status == 1) {
			status = 'Hoạt động';
		} else {
			status = 'Không hoạt động';
		}
		htmls += '<tr class="tr-tree-user" id-user="' + value.id + '">';
		htmls += '<td align="center">' + value.order + '</td>';
		htmls += '<td>' + name + '</td>';
		htmls += '<td>' + value.username + '</td>';
		htmls += '<td>' + role + '</td>';
		htmls += '<td>' + status + '</td>';
		htmls += '<td align="center"><span class="user-edit" style="color: #337ab7; cursor: pointer;" id-user="' + value.id + '"><i class="fas fa-edit"></i></span>&nbsp;&nbsp;&nbsp;<span class="user-delete" style="color: #337ab7; cursor: pointer;" id-user="' + value.id + '"  name-user ="' + value.name + '"><i class="fas fa-trash-alt"></i></span></td>';
		htmls += '</tr>';
		i++;
	});
	htmls += '</tbody>';
	htmls += '</table>';
	return htmls;
}

JS_TempHtml.prototype.zend_unit = function (objTree, node) {
	var check_loaded = false;
	if (node.state.loaded) {
		check_loaded = true;
	}
	var htmls = '';
	htmls += '<table id="tb_list_record" class="table table-striped table-hover table-bordered">';
	htmls += '<thead>';
	htmls += '<tr class="thead-inverse">';
	htmls += '<th align="center" style="width:2%;">STT</td>';
	htmls += '<th style="text-align: center">Tên đơn vị</th>';
	htmls += '<th style="text-align: center">Mã đơn vị</td>';
	htmls += '<th style="text-align: center">Trạng thái</td>';
	htmls += '<th style="text-align: center">Hành động</td>';
	htmls += '</tr>';
	htmls += '</thead>';
	htmls += '<tbody>';
	i = 1;
	var _class = v_status = '';
	var id = order = name = code = address = status = '';
	for (var prop in node.children) {
		if (check_loaded) {
			var objchild = objTree.jstree(true).get_node(node.children[prop]);
			id = objchild.original.id;
			order = objchild.original.order;
			name = objchild.original.name;
			code = objchild.original.code;
			address = objchild.original.address;
			status = objchild.original.status;
			_class = this.get_node_level(objchild.original.node_lv, objchild.original.type);
		} else {
			id = node.children[prop].id;
			order = node.children[prop].order;
			name = node.children[prop].name;
			code = node.children[prop].code;
			address = node.children[prop].address;
			status = node.children[prop].status;
			_class = this.get_node_level(node.children[prop].node_lv, node.children[prop].type);
		}
		if (status == 1) {
			v_status = 'Hoạt động';
		} else {
			v_status = 'Không hoạt động';
		}
		htmls += '<tr class="tr-tree-unit" id-unit="' + id + '">';
		htmls += '<td align="center">' + order + '</td>';
		htmls += '<td><i class="' + _class + '"></i> ' + name + '</td>';
		htmls += '<td>' + code + '</td>';
		htmls += '<td>' + v_status + '</td>';
		// htmls += '<td align="center"><button type="button" class="btn btn-secondary btn-sm unit-edit" id-unit="' + id + '">Sửa</button> <button type="button" class="btn btn-danger btn-sm delete-unit" id-unit="' + id + '" name-unit =  "' + name + '">Xóa</button></td>';
		htmls += '<td align="center"><span class="unit-edit" style="color: #337ab7; cursor: pointer;" id-unit="' + id + '"><i class="fas fa-edit"></i></span>&nbsp;&nbsp;&nbsp;<span class="delete-unit" style="color: #337ab7; cursor: pointer;" id-unit="' + id + '" name-unit =  "' + name + '"><i class="fas fa-trash-alt"></i></span></td>';
		htmls += '</tr>';
		i++;
	};
	htmls += '</tbody>';
	htmls += '</table>';
	return htmls;
}


JS_TempHtml.prototype.zend_unit_search = function (arrResult, node) {
	var htmls = '';
	var myClass = this;
	htmls += '<table id="tb_list_record" class="table table-striped table-hover table-bordered">';
	htmls += '<thead>';
	htmls += '<tr class="thead-inverse">';
	htmls += '<th align="center">Tên đơn vị</th>';
	htmls += '<th align="center">Mã đơn vị</td>';
	htmls += '<th align="center">Địa chỉ</td>';
	htmls += '<th align="center"></td>';
	htmls += '</tr>';
	htmls += '</thead>';
	htmls += '<tbody>';
	htmls += '</tbody>';
	i = 1;
	var _class = this.get_node_level(3, '');
	$.each(arrResult, function (key, value) {
		htmls += '<tr>';
		htmls += '<td><i class="' + _class + '"></i> ' + value.name + '</td>';
		htmls += '<td>' + value.code + '</td>';
		htmls += '<td>' + value.C_ADDRESS + '</td>';
		htmls += '<td><a class="unit-edit" id-unit="' + value.id + '"><span  class="fa fa-pencil-square-o"></span></a> | <a class="unit-delete" id-unit="' + value.id + '"><span class="glyphicon glyphicon-trash unit-delete"></span></a></td>';
		htmls += '</tr>';
		i++;
	});
	htmls += '</table>';
	return htmls;
}

JS_TempHtml.prototype.get_node_level = function (node, type) {
	var icon = '';
	if (node == 1) {
		icon = 'fa fa-home mfa-2x folder-lv1';
	} else if (node == 2) {
		icon = 'fa fa-university folder-lv2';
	} else if (node == 3) {
		if (type == 'PHUONG_XA') {
			icon = 'fa fa-university folder-lv3';
		} else {
			icon = 'fa fa-square folder-lv3';
		}
	} else if (node == 4) {
		icon = 'glyphicon glyphicon-folder-close folder-lv4';
	} else if (node == 5) {
		icon = 'glyphicon glyphicon-folder-close folder-lv5';
	} else if (node == 0) {
		icon = 'glyphicon glyphicon-folder-close folder-lv0';
	}
	return icon;
}

JS_TempHtml = new JS_TempHtml();
