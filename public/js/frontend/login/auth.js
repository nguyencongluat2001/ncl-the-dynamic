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
        let url = this.urlPath;
        let data = $('form#frm_sign_in').serialize();
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

}
