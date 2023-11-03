/**
 * Thông tin tài khoản
 * 
 * @author khuongtq
 */
class Info {
    module;
    baseUrl;
    urlPath;

    constructor(baseUrl, module) {
        this.module = module;
        this.baseUrl = baseUrl;
        this.urlPath = baseUrl + '/' + module;
    }

    loadIndex() {
        let myClass = this;
        $('#btn_edit').click(() => this.edit());
        $('#btn_cancel').click(() => this.cancel());
        $('#btn_update').click(() => this.update());

        $('#cap_don_vi').on('change', function (e) {
            myClass.getUnit(this.value);
            var selectedValue = $(this).val();
            var selectedLabel = $('option[value="' + selectedValue + '"]').text();
            $('#cap_don_vi_text').text(selectedLabel);
        });
        $('#don_vi').on('change', function (e) {
            var selectedValue = $(this).val();
            var selectedLabel = $('option[value="' + selectedValue + '"]').text();
            $('#don_vi_text').text(selectedLabel);
        });
        $('#cap_don_vi').change();
    }

    /**
     * Lấy html option đơn vị
     *
     * @return void
     */
    getUnit(level) {
        let myClass = this;
        let url = this.baseUrl + '/data/unit-by-level/' + level;
        $.ajax({
            type: "GET",
            url: url,
            cache: false,
            dataType: 'json',
            success: function (response) {
                $('#don_vi').html(myClass.genHtmlUnit(response));
                $('.chzn-select').trigger('chosen:updated');
                $('#don_vi').change();
            }
        });
    }

    /**
     * Tạo html option cho đơn vị
     * 
     * @param {*} data 
     * @returns 
     */
    genHtmlUnit(data) {
        let html = '', arrKeyName = [], arrWard = [];
        let currentUnit = $('#current_unit').val();
        data.forEach(unit => {
            if (unit.unit_level === 'PHUONG_XA') {
                // Lấy tên quận huyện ra 1 mảng riêng
                if (!arrKeyName.find(item => item.code === unit.parent_code)) {
                    arrKeyName.push({ code: unit.parent_code, name: unit.parent_name });
                }

                // Nhóm các xã theo huyện
                let item = arrWard.find(item => item.parent_code === unit.parent_code);
                if (item) {
                    // Nếu đã có key huyện thì add xã vào child của huyện
                    item.children.push({ code: unit.code, name: unit.name });
                } else {
                    // Nếu chưa có key huyện thì thêm key huyện và add xã child 
                    arrWard.push({ parent_code: unit.parent_code, children: [{ code: unit.code, name: unit.name }] });
                }
            } else html += `<option value="${unit.code}" ${unit.code === currentUnit ? 'selected' : ''}>${unit.name}</option>`;
        });

        if (arrWard.length > 0) {
            arrWard.forEach(district => {
                html += `<optgroup label="${arrKeyName.find(item => item.code === district.parent_code)?.name}">`;
                district.children.forEach(ward => {
                    html += `<option value="${ward.code}" ${ward.code === currentUnit ? 'selected' : ''}>${ward.name}</option>`;
                });
            });
        }

        return html;
    }

    /**
     * Sửa thông tin
     */
    edit() {
        $('#btn_edit').hide();
        $('#btn_update').show();
        $('#btn_cancel').show();
        $('#cap_don_vi_text').hide();
        $('#don_vi_text').hide();
        $('.chzn-select').chosen({ height: '100%', width: '100%' });
    }

    /**
     * Hủy việc sửa
     */
    cancel() {
        $('#btn_edit').show();
        $('#btn_update').hide();
        $('#btn_cancel').hide();
        $('#cap_don_vi_text').show();
        $('#don_vi_text').show();
        $('.chzn-select').chosen('destroy');
        $('#cap_don_vi').hide();
        $('#don_vi').hide();
    }

    /**
     * Cập nhật thông tin
     */
    update() {
        let url = this.baseUrl + '/tai-khoan/cap-nhat';
        let data = $('form#frm_info').serialize();
        data += '&url=' + this.baseUrl;
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (arrResult) {
                let nameMessage = arrResult['message'];
                let icon = '';
                let color = '#24dd00';
                let background = '#ffffff';
                NclLib .swalAlert(nameMessage, icon, color, background);
                window.location.reload();
            }
        });
    }



}