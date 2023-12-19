function JS_UpgradeAcc(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.menuActive('.link-privileges');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}
/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_UpgradeAcc.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmAdd_updateAcc';
    $(oForm).find('#updateVip').click(function () {
        myClass.updateVip(oForm);
    });
    myClass.loadevent(oForm);
}
JS_UpgradeAcc.prototype.loadevent = function (oForm) {
    var myClass = this;
    $('form#frmAdd_updateAcc').find('#updateVip').click(function () {
        myClass.updateVip(oForm);
    });
   
}
// /**
//  * Load màn hình danh sách
//  *
//  * @param oForm (tên form)
//  *
//  * @return void
//  */
// JS_UpgradeAcc.prototype.loadList = function (oForm) {
//     var myClass = this;
//     var url = this.urlPath + '/loadList';
//     var data = $(oForm).serialize();
//     $.ajax({
//         url: url,
//         type: "POST",
//         data: data,
//         success: function (arrResult) {
//             $("#table-container-library").html(arrResult);
//         }
//     });
// }
/**
 * Hàm hiển thị modal
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_UpgradeAcc.prototype.viewForm = function (id) {
    var url = this.urlPath + '/viewForm';
    var myClass = this;
    var data = 'id=' + id;
    $.ajax({
        url: url,
        type: "GET",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#formmodal').html(arrResult);
            $('#formmodal').modal('show');
        }
    });
}
/**
 * Hàm hiển thêm mới
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_UpgradeAcc.prototype.updateVip = function (oForm) {
    var url = this.urlPath + '/updateVip';
    var myClass = this;
    var formdata = new FormData();
    // var check = myClass.checkValidate();
    // if(check == false){
    //     return false;
    // }
    // var status = ''
    // $('input[name="status"]:checked').each(function() {
    //     status =  $(this).val();
    // });
    formdata.append('_token', $("#_token").val());
    formdata.append('id_user', $("#id").val());
    formdata.append('wrap', $("#wrap").val());
    $('form#frmAdd_updateAcc input[type=file]').each(function () {
        var count = $(this)[0].files.length;
        for (var i = 0; i < count; i++) {
            formdata.append('file-attack-' + i, $(this)[0].files[i]);
        }
    });

    $.ajax({
        url: url,
        type: "POST",
        data: formdata,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                  Swal.fire({
                    position: 'top-start',
                    icon: 'success',
                    title: 'Gửi yêu cầu phê duyệt thành công!, vui lòng chờ phê duyệt từ nhân viên FinTop.',
                    showConfirmButton: false,
                    timer: 5000
                  })
                  $('#formmodal').modal('hide');
                //   myClass.loadList(oForm);

            } else {
                Swal.fire({
                    position: 'top-start',
                    icon: 'error',
                    title: arrResult['message'],
                    showConfirmButton: false,
                    timer: 3000
                  })
            }
        }
    });
}