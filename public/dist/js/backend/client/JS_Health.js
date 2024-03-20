function JS_Health(baseUrl, controller) {
    // $("#main_register").attr("class", "active");
    this.baseUrl = baseUrl;
    this.controller = controller;
    this.urlPath = baseUrl + "/" + controller;
    // NclLib.active(".link-health");
    this.isAjaxStop = false;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_Health.prototype.loadIndex = function () {
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
 * Hàm hiển thêm mới
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_Health.prototype.store = function (oFormCreate) {
    var url = this.urlPath + "/create";
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
