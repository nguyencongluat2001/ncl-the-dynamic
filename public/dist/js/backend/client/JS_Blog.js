function JS_Blog(baseUrl, module, controller) {
    // $("#main_register").attr("class", "active");
    this.baseUrl = baseUrl;
    this.controller = controller;
    this.urlPath = baseUrl + "/" + module + "/" + controller;
    // NclLib.active(".link-health");
    this.isAjaxStop = false;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_Blog.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = "form#frmHealth_index";
    myClass.loadList(oForm);
    $(oForm)
        .find("#btn_add")
        .click(function () {
            myClass.add(oForm);
        });
    $("form#frmHealth_index")
        .find("#btn_create")
        .click(function () {
            myClass.store("form#frmHealth_index");
        });
};
/**
 * Hàm thêm mới giấy khám sức khỏe
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_Blog.prototype.store = function (oFormCreate) {
    var url = this.urlPath + "/createGiayKham";
    var myClass = this;
    var formdata = new FormData();
    // var check = myClass.checkValidate();
    // if (check == false) {
    //     return false;
    // }
    formdata.append("_token", $("#_token").val());
    formdata.append("name", $("#name").val());
    formdata.append("email", $("#email").val());
    formdata.append("dateOfBirth", $("#dateOfBirth").val());
    formdata.append("phone", $("#phone").val());
    formdata.append("address", $("#address").val());
    formdata.append("history", $("#history").val());
    formdata.append("weighed", $("#weighed").val());
    formdata.append("height", $("#height").val());
    formdata.append("sex", $("input[name='sex']:checked").val());
    $("form#frmHealth_index input[type=file]").each(function () {
        var count = $(this)[0].files.length;
        for (var i = 0; i < count; i++) {
            formdata.append("file-attack-" + i, $(this)[0].files[i]);
        }
    });
    console.log(formdata);
    $.ajax({
        url: url,
        type: "POST",
        data: formdata,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function (arrResult) {
            console.log(arrResult);
            if (arrResult["success"] == true) {
                NclLib.alerMesageWeb(arrResult["message"], "success");
            } else {
                NclLib.successLoadding();
                NclLib.alerMesageWeb(arrResult["message"], "error");
            }
        },
    });
};

/**
 * Hàm hiển thêm mới bằng cấp
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_Blog.prototype.storeBangCap = function (oFormCreate) {
    var url = this.urlPath + "/createBangCap";
    var myClass = this;
    var formdata = new FormData();
    formdata.append("_token", $("#_token").val());
    formdata.append("name", $("#name").val());
    formdata.append("email", $("#email").val());
    formdata.append("dateOfBirth", $("#dateOfBirth").val());
    formdata.append("phone", $("#phone").val());
    formdata.append("school", $("#school").val());
    formdata.append("address", $("#address").val());
    formdata.append("code_category", $("#code_category").val());
    formdata.append("industry", $("#industry").val());
    formdata.append("graduate_time", $("#graduate_time").val());
    formdata.append("level", $("#level").val());
    formdata.append("permanent_residence", $("#permanent_residence").val());
    formdata.append("identity", $("#identity").val());
    formdata.append("identity_time", $("#identity_time").val());
    formdata.append("identity_address", $("#identity_address").val());
    formdata.append("sex", $("input[name='sex']:checked").val());
    var avatarFile = document.getElementById("avatar1").files[0];
    if (avatarFile) {
        formdata.append("image", avatarFile);
    }

    var transferFile = document.getElementById("image_transfer").files[0];
    if (transferFile) {
        formdata.append("image_transfer", transferFile);
    }
    // console.log(formdata);
    $.ajax({
        url: url,
        type: "POST",
        data: formdata,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function (arrResult) {
            console.log(arrResult);
            if (arrResult["success"] == true) {
                NclLib.alerMesageWeb(arrResult["message"], "success");
            } else {
                NclLib.successLoadding();
                NclLib.alerMesageWeb(arrResult["message"], "error");
            }
        },
    });
};
