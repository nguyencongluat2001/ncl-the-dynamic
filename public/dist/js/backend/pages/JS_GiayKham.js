function JS_GiayKham(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active(".link-product");
    this.urlPath = baseUrl + "/" + module + "/" + controller;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_GiayKham.prototype.loadIndex = function () {
    var myClass = this;
    $(".chzn-select").chosen({ height: "100%", width: "100%" });
    var oForm = "form#frmProduct_index";
    myClass.loadList(oForm);
    // form load
    $(oForm)
        .find("#cate")
        .change(function () {
            var page = $(oForm).find("#limit").val();
            var perPage = $(oForm).find("#cbo_nuber_record_page").val();
            myClass.loadList(oForm, page, perPage);
        });
    $(oForm)
        .find("#txt_search")
        .click(function () {
            /* ENTER PRESSED*/
            var page = $(oForm).find("#limit").val();
            var perPage = $(oForm).find("#cbo_nuber_record_page").val();
            myClass.loadList(oForm, page, perPage);
            // return false;
        });
    // Xoa doi tuong
    $(oForm)
        .find("#btn_delete")
        .click(function () {
            myClass.deleteGiayKham(oForm);
        });
};
JS_GiayKham.prototype.loadevent = function (oForm) {
    var myClass = this;
    $("form#frmProduct_index")
        .find("#btn_create")
        .click(function () {
            myClass.store("form#frmProduct_index");
        });
};
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_GiayKham.prototype.loadList = function (
    oForm = "#frmProduct_index",
    numberPage = 1,
    perPage = 15
) {
    var myClass = this;
    var url = this.urlPath + "/loadList";
    var data = "search=" + $("#search").val();
    var data = $(oForm).serialize();
    data += "&offset=" + numberPage;
    data += "&limit=" + perPage;
    $.ajax({
        url: url,
        type: "GET",
        // cache: true,
        data: data,
        success: function (arrResult) {
            console.log(arrResult);
            $("#table-container-category").html(arrResult);
            // phan trang
            $(oForm)
                .find(".main_paginate .pagination a")
                .click(function () {
                    var page = $(this).attr("page");
                    var perPage = $("#cbo_nuber_record_page").val();
                    myClass.loadList(oForm, page, perPage);
                });
            $(oForm)
                .find("#cbo_nuber_record_page")
                .change(function () {
                    var page = $(oForm).find("#_currentPage").val();
                    var perPages = $(oForm)
                        .find("#cbo_nuber_record_page")
                        .val();
                    myClass.loadList(oForm, page, perPages);
                });
            $(oForm).find("#cbo_nuber_record_page").val(perPage);
            var loadding = NclLib.successLoadding();
            myClass.loadevent(oForm);
        },
    });
};
/**
 * Hàm hiển thị modal edit
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_GiayKham.prototype.infoGiayKham = function (id) {
    var url = this.urlPath + "/infor";
    var myClass = this;
    var data = "_token=" + $("#frmProduct_index #_token").val();
    data += "&id=" + id;
    var i = 0;
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            console.log(arrResult);
            $("#editmodal").html(arrResult);
            $("#editmodal").modal("show");
        },
    });
};
// Xoa mot doi tuong
JS_GiayKham.prototype.deleteGiayKham = function (oForm) {
    var myClass = this;
    var listitem = "";
    var p_chk_obj = $("#table-data").find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(":checked")) {
            if (listitem !== "") {
                listitem += "," + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == "") {
        var nameMessage = "Bạn chưa chọn thể loại để xóa!";
        var icon = "warning";
        var color = "#f5ae67";
        NclLib.alerMesage(nameMessage, icon, color);
        return false;
    }
    var data = $(oForm).serialize();
    // var url = this.urlPath + "/recordtype/" + listitem;
    var url = this.urlPath + "/delete";
    Swal.fire({
        title: "Bạn có chắc chắn xóa vĩnh viễn thể loại này không?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#34bd57",
        cancelButtonColor: "#d33",
        confirmButtonText: "Xác nhận",
    }).then((result) => {
        if (result.isConfirmed == true) {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    _token: $("#_token").val(),
                    listitem: listitem,
                },
                success: function (arrResult) {
                    if (arrResult["success"] == true) {
                        if (result.isConfirmed) {
                            var nameMessage = "Xóa thành công!";
                            var icon = "success";
                            var color = "#f5ae67";
                            NclLib.alerMesage(nameMessage, icon, color);
                            myClass.loadList(oForm);
                        }
                    } else {
                        if (result.isConfirmed) {
                            var nameMessage = "Quá trình xóa đã xảy ra lỗi!";
                            var icon = "error";
                            var color = "#f5ae67";
                            NclLib.alerMesage(nameMessage, icon, color);
                        }
                    }
                },
            });
        }
    });
};
/**
 * Cập nhật khi ở màn hình hiển thị danh sách
 */
JS_GiayKham.prototype.updateCategoryCate = function (id, column, value = "") {
    var myClass = this;
    var url = myClass.urlPath + "/updateCategoryCate";
    var data = "id=" + id;
    data += "&_token=" + $("#frmProduct_index").find("#_token").val();
    data += "&cate=" + $("#frmProduct_index").find("#cate").val();
    if (column == "code_category") {
        data += "&code_category=" + (column == "code_category" ? value : "");
    } else if (column == "name_category") {
        data += "&name_category=" + value;
    } else if (column == "decision") {
        data += "&decision=" + value;
    } else if (column == "order") {
        data += "&order=" + value;
    }
    $.ajax({
        url: url,
        data: data,
        type: "POST",
        success: function (arrResult) {
            if (arrResult["success"] == true) {
                NclLib.alertMessageBackend(
                    "success",
                    "Thông báo",
                    arrResult["message"]
                );
                if (column == "order") {
                    JS_Product.loadList();
                }
            } else {
                NclLib.alertMessageBackend(
                    "danger",
                    "Lỗi",
                    arrResult["message"]
                );
                JS_Product.loadList();
            }
        },
        error: function (e) {
            console.log(e);
            NclLib.successLoadding();
        },
    });
    $("#" + id).prop("readonly");
};
/**
 * Thay đổi trạng thái
 */
JS_GiayKham.prototype.changeStatusGiayKham = function (id) {
    var myClass = this;
    var url = myClass.urlPath + "/changeStatus";
    var data = "_token=" + $("#frmProduct_index #_token").val();
    data += "&status=" + ($("#status_" + id).is(":checked") == true ? 0 : 1);
    data += "&id=" + id;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            console.log(arrResult);
            if (arrResult["success"] == true) {
                NclLib.alertMessageBackend(
                    "success",
                    "Thông báo",
                    arrResult["message"]
                );
            } else {
                NclLib.alertMessageBackend(
                    "danger",
                    "Lỗi",
                    arrResult["message"]
                );
            }
            NclLib.successLoadding();
        },
        error: function (e) {
            console.log(e);
            NclLib.successLoadding();
        },
    });
};
