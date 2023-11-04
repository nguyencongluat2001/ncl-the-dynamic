class Auth {
    module;
    baseUrl;
    urlPath;

    constructor(baseUrl, module) {
        this.module = module;
        this.baseUrl = baseUrl;
        this.urlPath = baseUrl + '/' + module;
    }

    // Load su kien tren man hinh index
    loadIndex() {
        NclLib.loadding();
        let myClass = this;
        let oForm = 'form#frm_sign_in';
        $('.chzn-select').chosen({ height: '100%', width: '100%' });
    }

    /**
     * Đăng nhập
     *
     * @param oFormCreate (tên form)
     *
     * @return void
     */
    signIn(oFormCreate) {
        NclLib.loadding();
        let url = this.urlPath +'checkLogin';
        let data = $('form#frm_sign_in').serialize();
        console.log(data);
        data += '&url=' + this.baseUrl;
        if ($("#username").val() == '') {
            let nameMessage = 'Tên tài khoản không được để trống!';
            let icon = '';
            let color = '#ff9429';
            let background = '#ffffff';
            NclLib .swalAlert(nameMessage, icon, color, background);
            return false;
        }
        if ($("#password").val() == '') {
            let nameMessage = 'Mật khẩu không được để trống!';
            let icon = '';
            let color = '#ff9429';
            let background = '#ffffff';
            NclLib .swalAlert(nameMessage, icon, color, background);
            return false;
        }
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (response) {
                if (response.success) {
                    window.location.replace(response.url);
                } else {
                    $('#msg_error').text(response.message);
                    $('#msg_error').show();
                }
            }
        });
    }
    signUp() {
        NclLib.loadding();
        let url = this.urlPath+'checkRegister';
        let data = $('#frm_sign_up').serialize();
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function(arrResult) {
                if (arrResult['success'] == true) {
                    NclLib.swalAlert(arrResult['message'], '', '#24dd00', '#ffffff');
                    setTimeout(() => {
                        window.location.replace(arrResult['url']);
                    }, 2000)
                } else {
                    NclLib.swalAlert(arrResult['message'], '', '#ff9429', '#ffffff');
                }
            }
        });
    }
    getHuyen = function (codeTinh) {
        var myClass = this;
        NclLib.loadding();
        var url = this.urlPath + 'getHuyen';
        var data = '&codeTinh=' + codeTinh;
        $.ajax({
            url: url,
            type: "GET",
            cache: true,
            data: data,
            success: function (arrResult) {
                // $('.chzn-select').chosen({ height: '100%', width: '100%' });
                // var html = `<select onchange="JS_Auth.getXa(this.value)" class="form-control input-sm chzn-select" name="code_huyen" id="code_huyen">`
                var html = `<option value="">--Chọn quận huyện--</option>`
                $(arrResult.data.huyen).each(function(index,el) {
                     html += `<option value="`+ el.code_huyen +`">`+ el.name +`</option>`
                 });
                //  html += `</select>`
                $("#code_huyen").html(html);
                $("#code_huyen").trigger("chosen:updated")

            }
        });
    
    }
    /**
     * Load màn hình danh sách phường xã
     *
     * @param oForm (tên form)
     *
     * @return void
     */
    getXa = function (codeHuyen) {
        NclLib.loadding();
        var myClass = this;
        var url = this.urlPath + 'getXa';
        var data = '&codeHuyen=' + codeHuyen;
        $.ajax({
            url: url,
            type: "GET",
            cache: true,
            data: data,
            success: function (arrResult) {
                // var html = `<select class="form-control input-sm chzn-select" name="code_xa" id="code_xa">`
                var html = `<option value="">--Chọn phường xã--</option>`
                $(arrResult.data.xa).each(function(index,el) {
                    html += `<option value="`+ el.code_xa +`">`+ el.name +`</option>`
                });
                // html += `</select>`
                $("#code_xa").html(html);
                $("#code_xa").trigger("chosen:updated")

            }
        });

    }

}
