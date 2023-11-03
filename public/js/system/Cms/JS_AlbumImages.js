function JS_AlbumImages(baseUrl, module, action) {
    // check side bar
    //    $("#main_cms").attr("class", "active");
    $("#main_album-images").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action; //Biên public lưu tên module
    this.formdata = new FormData();
    this.formdataImage = new FormData();
    this.countindex = 0;
    this.countindexImage = 0;
}
// Load su kien tren man hinh index
JS_AlbumImages.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmAlbumImagesIndex';
    myClass.loadList(oForm);
    // Them moi loai danh muc
    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
    $(oForm).find('#btn_search').click(function () {
        myClass.loadList(oForm);
    });
    $(oForm).find('#btn_manager_album').click(function () {
        myClass.btn_manager_album(oForm);
    });

    $(document).keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            myClass.loadList(oForm);
            return false;
        }
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function () {
            myClass.delete(oForm);
        }
    });
    // Tim kiem loai danh muc
    $(oForm).find('#listtype').change(function () {
        myClass.loadList(oForm);
    });
}
JS_AlbumImages.prototype.loadIndexManagerImage = function () {
    var myClass = this;
    var oForm = 'form#frmImagesIndex';
    myClass.loadListImage(oForm);
    // Them moi loai danh muc
    $(oForm).find('#btn_add').click(function () {
        myClass.add_image(oForm);
    });
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit_image(oForm);
    });
    $(oForm).find('#btn_search').click(function () {
        myClass.loadListImage(oForm);
    });

    $(document).keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            myClass.loadList(oForm);
            return false;
        }
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function () {
            myClass.delete_image(oForm);
        }
    });
    // Tim kiem loai danh muc
    $(oForm).find('#listtype').change(function () {
        myClass.loadList(oForm);
    });
}
// Load su kien tren cac minh khac
JS_AlbumImages.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}
// Lay du lieu cho man hinh danh sach
JS_AlbumImages.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmAlbumImagesIndex';
    var myClass = this;
    var loadding = NclLib .loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadlist';
    var data = $(oForm).serialize();
    data += '&currentPage=' + currentPage;
    data += '&perPage=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            loadding.go(100);
            var shtml = myClass.genTable(arrResult['Dataloadlist']['data']);
            // phan trang
            $('#pagination').html(arrResult.pagination);
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').val(arrResult.perPage);
            loadding.go(100);
        }
    });
}
JS_AlbumImages.prototype.genTable = function (arrResult) {
    var shtml = '';
    var status = 'Không hoạt động';
    var oldPosGroup = '';
    for (var x in arrResult) {
        if (arrResult[x]['C_STATUS'] == '1') {
            status = 'Hoạt động'
        } else {
            status = 'Không hoạt động'
        }
        shtml += '<tr>';
        shtml += '<td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="' + arrResult[x]['PK_CMS_ALBUM_IMAGES'] + '"></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}"> <img src="' + arrResult[x]['C_IMAGE_FILE_NAME'] + '" style="max-width:100%;max-height:150px"/></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_NAME'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + status + '</td>';
        shtml += '</tr>';
    }
    $('#data-list-album-images').html(shtml);
}
JS_AlbumImages.prototype.genTableImage = function (arrResult) {
    var shtml = '';
    var status = 'Không hoạt động';
    var i = 1;
    for (var x in arrResult) {
        if (arrResult[x]['C_STATUS'] == '1') {
            status = 'Hoạt động'
        } else {
            status = 'Không hoạt động'
        }
        shtml += '<tr>';
        shtml += '<td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="' + arrResult[x]['PK_CMS_RELATE_IMAGE_ALBUM'] + '"></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}"> <img src="' + arrResult[x]['C_IMAGE_FILE_NAME'] + '" style="max-width:100%;max-height:150px"/></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_NAME'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + status + '</td>';
        shtml += '</tr>';
        i++;
    }
    $('#data-list-images').html(shtml);
}
// Them loai danh muc
JS_AlbumImages.prototype.add = function (oForm) {
    NclLib .showmainloadding();
    var url = this.urlPath + '/album-images_add';
    var myClass = this;
    myClass.countindex = 0;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            NclLib .successLoadImage();
            $('#modalImages').html(arrResult);
            $('#frmAlbumImagesIndex').hide();
            $('#modalImages').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddAlbumImages'));
            $('.chzn-select').chosen();
            $('.datepicker').datepicker();
        }
    });
}
JS_AlbumImages.prototype.backtoindex = function () {
    $('#modalImages').html('');
    $('#frmAlbumImagesIndex').show();
    $('#modalImages').hide();
}
JS_AlbumImages.prototype.validateform = function (oForm) {
    jQuery.validator.addMethod("rangePhone", function (value, element, params) {
        if (value.length >= params[0] && value.length <= params[1]) {
            return true;
        } else {
            return false;
        }
    });
    oForm.validate({
        rules: {
            C_POSITION: "required",
            C_NAME: "required",
            C_URL: "required",
            //            email: "email",
        },
        messages: {
            C_POSITION: "Vị trí không được để trống",
            C_NAME: "Tên ảnh không được để trống",
            C_URL: "Địa chỉ liên kết không được để trống",
            //            phone_number: {
            //                digits: "Số điện thoại phải là các chữ số",
            //                rangePhone: "Số điện thoại phải từ 9 đến 15 ký tự",
            //            },
            //            email: "Email phải đúng định dạng email",
        }
    });
}
// sua
JS_AlbumImages.prototype.edit = function (oForm) {
    var url = this.urlPath + '/album-images_edit';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
            i++;
        }
    });
    if (listitem == '') {
        NclLib .alertMessage('danger', "Bạn chưa chọn album cần sửa");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một album để sửa");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#modalImages').html(arrResult);
            $('#frmAlbumImagesIndex').hide();
            $('#modalImages').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddAlbumImages'));
            $('.datepicker').datepicker();
        }
    });
}
// Them loai danh muc
JS_AlbumImages.prototype.delete = function (oForm) {
    var url = this.urlPath + '/delete';
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == '') {
        NclLib .alertMessage('danger', "Bạn chưa chọn album cần xóa");
        return false;
    }
    var data = $(oForm).serialize();
    data += '&listitem=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                myClass.loadList(oForm);
                NclLib .alertMessage('success', arrResult['message']);
            } else {
                NclLib .alertMessage('danger', arrResult['message']);
            }
        }
    });
}
JS_AlbumImages.prototype.preview_albumimages = function () {
    $('#image_onserver').val('');
    var myClass = this;
    var filedata = document.getElementById("album-images");
    var file = filedata.files[0];
    var extensionFile = myClass.getextensionfile(file.name);
    if (extensionFile == 'jpg' || extensionFile == 'png') {
        $('#image_preview').html("<div class='col-md-2'></div><div class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[0]) + "'></div>");
        if (myClass.formdata) {
            myClass.formdata.append("album-images", file);
        }
        myClass.countindex++;
    } else {
        NclLib .alertMessage('warning', 'Thông báo', 'Bạn cần phải chọn đúng file ảnh (.png,.jpg)');
    }
}
JS_AlbumImages.prototype.preview_images = function () {
    var myClass = this;
    var filedata = document.getElementById("images");
    var file = filedata.files[0];
    var extensionFile = myClass.getextensionfile(file.name);
    if (extensionFile == 'jpg' || extensionFile == 'png') {
        $('#image_preview').html("<div class='col-md-2'></div><div class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[0]) + "'></div>");
        if (myClass.formdataImage) {
            myClass.formdataImage.append("images", file);
        }
        myClass.countindexImage++;
    } else {
        NclLib .alertMessage('warning', 'Thông báo', 'Bạn cần phải chọn đúng file ảnh (.png,.jpg)');
    }
}

JS_AlbumImages.prototype.PreviewFeatureImage = function () {
    var myClass = this;
    var filedata = document.getElementById("C_FEATURE_IMG");
    $('#PreviewFeatureImage').html("<div class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[0]) + "'></div>");
    if (myClass.formdata) {
        myClass.formdata.append("C_FEATURE_IMG", filedata.files[0]);
    }
}

// Cap nhat loai danh muc
JS_AlbumImages.prototype.update = function (oForm) {
    if (oForm.valid()) {
        var url = this.urlPath + '/update';
        var myClass = this;
        myClass.formdata.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        myClass.formdata.append('data', data);
        if ($('#filename').val() == '' && $('#image_onserver').val() == '') {
            NclLib .alertMessage('danger', 'Cảnh báo', 'Ảnh album không được để trống', 6000);
            return false;
        }
        NclLib .showmainloadding();
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: myClass.formdata,
            processData: false,
            contentType: false,
            success: function (arrResult) {
                NclLib .successLoadImage();
                myClass.formdata = new FormData();
                if (arrResult['success']) {
                    $('#addListModal').modal('hide');
                    myClass.loadList(oForm);
                    $('#modalImages').html('');
                    $('#frmAlbumImagesIndex').show();
                    $('#modalImages').hide();
                    NclLib .alertMessage('success', arrResult['message']);
                } else {
                    NclLib .alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
                }
            },
            error: function (arrResult) {
                NclLib .alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
            }
        });
    }

}

JS_AlbumImages.prototype.getextensionfile = function (filename) {
    return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
}
JS_AlbumImages.prototype.deletefile = function (obj, a) {
    $('#file' + a).remove();
    this.formdata.delete("file" + a);
    $('#album-images').val('');
}

JS_AlbumImages.prototype.btn_manager_album = function (oForm) {
    var url = this.urlPath + '/album-images_manager';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
            i++;
        }
    });
    if (listitem == '') {
        NclLib .alertMessage('danger', "Bạn chưa chọn album ảnh");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một album ảnh");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#modalImages').html(arrResult);
            $('#frmAlbumImagesIndex').hide();
            $('#modalImages').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddAlbumImages'));
            $('.datepicker').datepicker();
            myClass.loadIndexManagerImage()
        }
    });
}
JS_AlbumImages.prototype.loadListImage = function (oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmImagesIndex';
    var myClass = this;
    var loadding = NclLib .loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadlist_image';
    var data = $(oForm).serialize();
    data += '&currentPage=' + currentPage;
    data += '&perPage=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            loadding.go(100);
            var shtml = myClass.genTableImage(arrResult['Dataloadlist']['data']);
            // phan trang
            $('#pagination1').html(arrResult.pagination);
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadListImage(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadListImage(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').val(arrResult.perPage);
            loadding.go(100);
        }
    });
}
JS_AlbumImages.prototype.add_image = function (oForm) {
    NclLib .showmainloadding();
    var url = this.urlPath + '/image-add';
    var myClass = this;
    myClass.countindex = 0;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            NclLib .successLoadImage();
            $('#modalRelateImages').html(arrResult);
            $('#frmImagesIndex').hide();
            $('#modalRelateImages').show();
            $('#close_modal_2').click(function () {
                myClass.backtoimagemanagerindex();
                return false;
            });
            myClass.validateform($('form#modalRelateImages'));
            $('.chzn-select').chosen();
            $('.datepicker').datepicker();
        }
    });
}
JS_AlbumImages.prototype.edit_image = function (oForm) {
    var url = this.urlPath + '/image_edit';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $(oForm + ' #table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
            i++;
        }
    });
    if (listitem == '') {
        NclLib .alertMessage('danger', "Bạn chưa chọn ảnh cần sửa");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một ảnh để sửa");
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#modalRelateImages').html(arrResult);
            $('#frmImagesIndex').hide();
            $('#modalRelateImages').show();
            $('#close_modal_2').click(function () {
                myClass.backtoimagemanagerindex();
                return false;
            });
            myClass.validateform($('form#frmAddImages'));
            $('.datepicker').datepicker();
        }
    });
}
JS_AlbumImages.prototype.backtoimagemanagerindex = function () {
    $('#modalRelateImages').html('');
    $('#frmImagesIndex').show();
    $('#modalRelateImages').hide();
}
JS_AlbumImages.prototype.update_image = function (oForm) {
    if (oForm.valid()) {
        var url = this.urlPath + '/update_image';
        var myClass = this;
        console.log(oForm);
        myClass.formdataImage.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        if ($('#filename').val() == '' && $('#album-images').val() == '') {
            NclLib .alertMessage('danger', 'Cảnh báo', 'Ảnh album không được để trống', 6000);
            return false;
        }
        myClass.formdataImage.append('data', data);
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: myClass.formdataImage,
            processData: false,
            contentType: false,
            success: function (arrResult) {
                myClass.formdataImage = new FormData();
                if (arrResult['success']) {
                    $('#addListModal').modal('hide');
                    myClass.loadListImage('form#frmImagesIndex');
                    $('#modalRelateImages').html('');
                    $('#frmImagesIndex').show();
                    $('#modalRelateImages').hide();
                    NclLib .alertMessage('success', arrResult['message']);
                } else {
                    NclLib .alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
                }
            },
            error: function (arrResult) {
                NclLib .alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
            }
        });
    }

}

JS_AlbumImages.prototype.delete_image = function (oForm) {
    var url = this.urlPath + '/image_delete';
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $(oForm + ' #table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == '') {
        NclLib .alertMessage('danger', "Bạn chưa chọn ảnh cần xóa");
        return false;
    }
    var data = $(oForm).serialize();
    data += '&listitem=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                myClass.loadListImage(oForm);
                NclLib .alertMessage('success', arrResult['message']);
            } else {
                NclLib .alertMessage('danger', arrResult['message']);
            }
        }
    });
}

JS_AlbumImages.prototype.preview_images_onserver = function () {
    var myClass = this;
    var url = this.baseUrl + '/system/cms/dirfile';
    var myClass = this;
    NclLib .showmainloadding();
    $.ajax({
        url: url,
        type: "GET",
        //cache: true,
        data: {
            objJS:'JS_AlbumImages'
        },
//        processData: false,
//        contentType: false,
        success: function (arrResult) {
            NclLib .successLoadImage();
            $('#FileOnServerModal').html(arrResult);
            $('#FileOnServerModal').modal('show');
        },
        error: function (arrResult) {
            NclLib .alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
        }
    });
}


JS_AlbumImages.prototype.selectImage = function (url) {
    $('#image_onserver').val(url);
    var html = '<div class="col-md-2"></div>';
    html = " <div class='col-md-3'><img class='img-responsive' src='" + url + "'></div>";
    $('#image_preview').html(html);
    $('#FileOnServerModal').html('');
    $('#FileOnServerModal').modal('hide');
}
JS_AlbumImages.prototype.selectFolder = function (path) {
    var myClass = this;
    var url = this.baseUrl + '/system/cms/dirfile/getAllFolderInPath';
    var myClass = this;
    NclLib .showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: {
            _token: $("#_token").val(),
            input_path: path,
            objJS:'JS_AlbumImages'
        },
//        processData: false,
//        contentType: false,
        success: function (arrResult) {
            NclLib .successLoadImage();
            $('#DuLieuFolder').html(arrResult);
        },
        error: function (arrResult) {
            NclLib .alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
        }
    });
}