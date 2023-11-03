/**
 * Đổi mật khẩu
 * 
 * @author luatnc
 */
class ChangePassword {
    module;
    baseUrl;
    urlPath;

    constructor(baseUrl, module) {
        this.module = module;
        this.baseUrl = baseUrl;
        this.urlPath = baseUrl + '/' + module;
    }

    loadIndex() {
        $('#btn_update').on('click', () => this.updatePassword());
    }

    /**
     * Cập nhật mật khẩu
     * @returns 
     */
    updatePassword() {
        if ($("#password").val() == '') {
            NclLib .swalAlert('Mật khẩu không được để trống!', '', '#ff9429', '#ffffff');
            return false;
        }
        if ($("#new_password").val() == '') {
            NclLib .swalAlert('Mật khẩu mới không được để trống!', '', '#ff9429', '#ffffff');
            return false;
        }
        if ($("#re_password").val() == '') {
            NclLib .swalAlert('Chưa nhập mật khẩu mới!', '', '#ff9429', '#ffffff');
            return false;
        }

        let myClass = this;
        let url = this.urlPath + '/doi-mat-khau';
        let data = $('form#frm_info').serialize();
        data += '&url=' + this.baseUrl;
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (response) {
                if (response.data.success == true) {
                    NclLib .swalAlert(response.data.message, '', '#24dd00', '#ffffff');
                    setTimeout(() => {
                        window.location.href = myClass.urlPath;
                    }, 1000);
                } else {
                    NclLib .swalAlert(response.data.message, '', '#ff9429', '#ffffff');
                }
            }
        });
    }
}