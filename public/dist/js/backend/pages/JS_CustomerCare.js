function JS_CustomerCare(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-customerCare');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_CustomerCare.prototype.loadIndex = function () {
    var myClass = this;
    // $('.chzn-select').chosen({ height: '100%', width: '100%' });
    var oForm = 'form#frmCustomerCare_index';
    var oFormCreate = 'form#frmAdd';
    myClass.loadList(oForm);

    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_CustomerCare.prototype.loadList = function (oForm, numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var data = 'search=' + $("#search").val();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $("#table-container").html(arrResult);
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
        }
    });
}
/**
 * Thay đổi trạng thái
 */
JS_CustomerCare.prototype.message = function(phone){
    var myClass = this;
    var url = myClass.urlPath + '/message';
    var data = '_token=' + $("#frmCustomerCare_index #_token").val();
    data += '&phone=' + phone;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult){
            if(arrResult['success'] == false){
                NclLib.alertMessageBackend('danger', 'Lỗi', arrResult['message']);
            }
            $("#message").html(arrResult);
            $("#message").show();
            $("#message-title").show();
            $("#message-title").removeClass('message-title');
            $("#discussions").addClass('show-mobile');
            $(".discussion").removeClass('message-active');
            $("#active_" + phone).addClass('message-active');
            $("#message").attr('class', 'chat col-md-9 active_' + phone);
            $("#sendMessage").attr('onclick', "JS_CustomerCare.broadcast('" + phone + "')");
            $("#active_" + phone + " .name").removeClass('font-bold');
            $("#active_" + phone + " .message").removeClass('font-bold');
            $(".message-title-title").html(phone);
            $(".message-title-back").click(function(){
                $("#message-title").hide();
                $("#message").hide();
                $("#discussions").removeClass('show-mobile');
                $("#message-title").addClass('message-title');
            });
            // $(window).on("navigate", function (event, data) {
            //     var direction = data.state.direction;
            //     if (direction == 'back') {
            //         alert(1234);
            //     }
            //     if (direction == 'forward') {
            //         alert(12344444);
            //     }
            //   });
            // $(document).keypress(function(e){
            //     alert(1234);
            //     if(e.keyCode == 8){
            //     }
            // });
            NclLib.successLoadding();
        }, error: function(e){
            console.log(e);
            NclLib.successLoadding();
        }
    });
}
/**
 * 
 */
JS_CustomerCare.prototype.broadcast = function(phone){
    $.ajax({
        url: '/system/customerCare/broadcast',
        method: 'POST',
        headers: {
            'X-Socket-Id': pusher.connection.socket_id
        },
        data: {
            _token: $("#_token").val(),
            message: $("#txt-message").val(),
            phone: phone,
        },
        success: function(res) {
            // $(".response-time").html('');
            // $(".response-time").removeClass('response-time');
            $(".messages-chat").append(res);
            $("#txt-message").val('');
        }
    });
}
function mouseover(_this){
    $(_this).find(".discussion-time").hide();
    $(_this).find(".show-more").show();
    // $(".discussion").removeAttr('onclick');
}
function mouseout(_this, phone){
    $(_this).find(".discussion-time").show();
    $(_this).find(".show-more").hide();
    // $(".discussion").attr('onclick', `JS_CustomerCare.message('${phone}')`);
}

function showMore(_this, phone){
    console.log(phone);
    $(_this).parent().find(".menuShowMore").show();
    $(".deleteMes").click(function(){
        Swal.fire({
            title: 'Bạn có chắc chắn xóa vĩnh viễn cuộc trò chuyện này không?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Đóng',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xác nhận',
            confirmButtonColor: '#34bd57',
        }).then((result) => {
            if (result.isConfirmed == true) {
                var url = JS_CustomerCare.urlPath + '/delete';
                var data = 'phone=' + phone;
                data += '&_token=' + $("#_token").val();
                $.ajax({
                    url: url,
                    data: data,
                    type: "POST",
                    success: function(arrResult){
                        Swal.fire({
                            icon: 'success',
                            title: 'Xóa thành công',
                            showConfirmButton: false,
                            timer: 3000
                        })
                        setTimeout(function(){window.location.reload()}, 2500)
                    }
                });
            }
        });
    });
}
