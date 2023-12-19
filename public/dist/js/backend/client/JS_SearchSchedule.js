function JS_SearchSchedule(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.menuActive('.link-searchschedule');
    NclLib.loadding();
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}
/**
 * Hàm load màn hình index
 *
 * @return void
 */
JS_SearchSchedule.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmHospital';
    NclLib.menuActive('.link-searchschedule');
    $('.chzn-select').chosen({ height: '100%', width: '100%' });

    myClass.loadList(oForm);
}
JS_SearchSchedule.prototype.loadevent = function (oForm) {
    var myClass = this;
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_SearchSchedule.prototype.loadList = function (value) {
    NclLib.loadding();
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var oForm = 'form#frmSearch';
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "GET",
        cache: true,
        data: data,
        success: function (arrResult) {
            $("#table-container").html(arrResult);
            myClass.loadevent(oForm);
        }
    });
}