class ForgotPassword {

    module;
    baseUrl;
    urlPath;

    /** mã captcha */
    captcha;

    constructor(baseUrl, module) {
        this.module = module;
        this.baseUrl = baseUrl;
        this.urlPath = baseUrl + '/' + module;
    }

    // Load su kien tren man hinh index
    loadIndex() {
        let myClass = this;
        this.generateCaptcha();
        $('#btn_refresh_captcha').click(function (e) {
            e.preventDefault();
            myClass.generateCaptcha();
        });
        $('#btn_forgot').on('click', function (e) {
            myClass.forgotPassword();
        });
    }

    /**
     * Tạo mã ngẫu nhiên
     */
    generateCaptcha() {
        let charsArray = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        let lengthOtp = 6;
        let captchaChar = [];
        for (let i = 0; i < lengthOtp; i++) {
            // below code will not allow Repetition of Characters
            let index = Math.floor(Math.random() * charsArray.length + 1); //get the next character from the array
            if (captchaChar.indexOf(charsArray[index]) == -1) captchaChar.push(charsArray[index]);
            else i--;
        }
        // storing captcha so that can validate you can save it somewhere else according to your specific requirements
        this.captcha = captchaChar.join("").trim();
        // create canvas element
        let canv = document.createElement("canvas");
        canv.id = "captcha";
        canv.width = 130;
        canv.height = 36;
        let ctx = canv.getContext("2d");
        ctx.font = "30px Garamond";
        ctx.strokeText(this.captcha, 0, 25);

        // return canv;
        $('#captcha').val('');
        $('#random_captcha').html(canv);
    }

    /**
     * Lấy lại mật khẩu
     */
    forgotPassword() {
        let myClass = this;
        let cap = $('#captcha').val();
        if (cap != this.captcha) {
            $('#msg_err').show();
            myClass.generateCaptcha();
        } else {
            $('#msg_err').hide();
            let url = this.urlPath;
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    email: $('#email').val()
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: response.title,
                            text: response.text,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(result => {
                            if (response.redirect_to) {
                                window.location.replace(myClass.baseUrl + response.redirect_to);
                            }
                        });
                    } else {
                        Swal.fire({
                            title: response.title,
                            text: response.text || '',
                            icon: "error",
                            allowOutsideClick: false,
                        });
                        myClass.generateCaptcha();
                    }
                }
            });
        }
    }
}