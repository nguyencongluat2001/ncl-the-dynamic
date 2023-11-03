function JS_Articles(baseUrl, module, action) {
    // check side bar
    //    $("#main_cms").attr("class", "active");
    $("#main_articles").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action; //Biên public lưu tên module
    this.formdata = new FormData();
    this.formdataArticle = new FormData();
    this.countindex = 0;
    this.countindexArticles = 0;
}
// Load su kien tren man hinh index
JS_Articles.prototype.loadIndex = function() {
        var myClass = this;
        var oForm = 'form#frmArticlesIndex';
        myClass.loadList(oForm);
        // Them moi loai danh muc
        $(oForm).find('#btn_add').click(function () {
            myClass.add(oForm);
        });
        $(oForm).find('#btn_edit').click(function () {
            myClass.edit(oForm);
        });
        $(oForm).find('#btn_search').click(function () {
            myClass.loadList();
        });
        $(oForm).find('#btn_manager_comment').click(function () {
            myClass.manager_comment(oForm);
        });
        $(oForm).find('#btn_approval').click(function () {
            myClass.approval(oForm);
        });
        $(oForm).find('#btn_see').click(function () {
            myClass.see(oForm);
        });
        $(document).keypress(function (event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                myClass.loadList();
                return false;
            }
        });
        // Xoa doi tuong
        $(oForm).find('#btn_delete').click(function() {
            myClass.delete(oForm);
        });
        // Tim kiem bài viết
        $(oForm).find('#listtype').change(function () {
            myClass.loadList();
        });
    }
    // Load su kien tren cac minh khac
JS_Articles.prototype.loadevent = function(oForm) {
        var myClass = this;
        $(oForm).find('#btn_update').click(function() {
            myClass.update(oForm);
        });
    }

// Lay du lieu cho man hinh danh sach
JS_Articles.prototype.loadList = function (currentPage = 1, perPage = 15) {
    var oForm = 'form#frmArticlesIndex';
    var myClass = this;
    var loadding = NclLib .loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadlist';
    var data = $(oForm).serialize();
    data += '&offset=' + currentPage;
    data += '&limit=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            loadding.go(100);
            $('#table-container').html(arrResult['data']);
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').val(arrResult.perPage);
        }
    });
}
// Them bài viết
JS_Articles.prototype.add = function (oForm) {
    var url = this.urlPath + '/create';
    var myClass = this;
    myClass.countindex = 0;
    var data = $(oForm).serialize();
    // NclLib .showmainloadding();
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            NclLib .successLoadImage();
            $('#modalArticles').html(arrResult);
            $('#frmArticlesIndex').hide();
            $('#modalArticles').show();
            $('#close_modal').click(function () {
                myClass.backtoindex();
            });
            $('.datepicker').datepicker();
            $('.chzn-select').chosen({height: '100%',width: '100%'});
        }
    });
}
// sua
JS_Articles.prototype.edit = function (oForm) {
    var url = this.urlPath + '/edit';
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
        NclLib .alertMessage('danger', "Bạn chưa chọn bài viết cần sửa");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một bài viết để sửa");
        return false;
    }
    data += '&itemId=' + listitem;
    NclLib .showmainloadding();
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            NclLib .successLoadImage();
            if (arrResult['danger']) {
                NclLib .alertMessage('danger', arrResult['message']);
            } else {
                $('#modalArticles').html(arrResult);
                $('#frmArticlesIndex').hide();
                $('#modalArticles').show();
                $('.chzn-select').chosen({height: '100%',width: '100%'});
                $(".chzn-select").trigger("chosen:updated");
                $('#close_modal').click(function () {
                    myClass.backtoindex();
                });
                $('.datepicker').datepicker();
            }

        }
    });
}
/**Quay lại */
JS_Articles.prototype.backtoindex = function () {
    $('#modalArticles').html('');
    $('#frmArticlesIndex').show();
    $('#modalArticles').hide();
}
// Cap nhat bài viết
JS_Articles.prototype.update = function (oForm) {
    var url = this.urlPath + '/update';
    var myClass = this;
    myClass.formdata.append('_token', $('#_token').val());
    var data = $(oForm).serialize();
    var ResultTree = $('#TreeCategories').jstree('get_selected', true);
    var categories_id = '';
    for (var i in ResultTree) {
        var li_attr = ResultTree[i].li_attr;
        if (li_attr['is_last_item'] == '1') {
            categories_id = ResultTree[i].id;
            break;
        }
    }
    var check = this.checkValidate();
    if(!check){
        return false;
    }
    myClass.formdata.append('data', data);
    myClass.formdata.append('content', CKEDITOR.instances['content'].getData());
    myClass.formdata.append('categories_id', categories_id);
    myClass.formdata.append('owner_code', $('#owner_code').val());
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
            var page = $('form#frmArticlesIndex').find('#_currentPage').val();
            var perPage = $('form#frmArticlesIndex').find('#cbo_nuber_record_page').val();
            if (arrResult['success']) {
                $('#frmArticlesIndex').show();
                $('#modalArticles').html('');
                $('#modalArticles').hide();
                myClass.loadList(page, perPage);
                $('main.main-content').animate({scrollTop: 0}, 1000);
                NclLib .alertMessage('success', 'Thông báo', arrResult['message']);
            } else {
                NclLib .alertMessage('warning', 'Cảnh báo', arrResult['message'], 6000);
            }
        },
        error: function (arrResult) {
            NclLib .alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
        }
    });
}
// Xóa bài viết
JS_Articles.prototype.delete = function (oForm) {
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
        NclLib .alertMessage('warning', 'Cảnh báo', "Bạn chưa chọn bài viết cần xóa", 4000);
        return false;
    }
    var data = $(oForm).serialize();
    data += '&listitem=' + listitem;
    $.confirm({
        title: 'Thông báo!',
        content: 'Bạn có chắc chắn muốn xóa những bài viết này không?',
        autoClose: 'Close|10000',
        buttons: {
            deleteUser: {
                btnClass: 'btn btn-primary',
                text: 'Xóa',
                action: function () {
                    NclLib .showmainloadding();
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
                        url: url,
                        type: "POST",
                        //cache: true,
                        dataType: 'json',
                        data: data,
                        success: function (arrResult) {
                            NclLib .successLoadImage();
                            if (arrResult['success']) {
                                var page = $('form#frmArticlesIndex').find('#_currentPage').val();
                                var perPage = $('form#frmArticlesIndex').find('#cbo_nuber_record_page').val();
                                myClass.loadList(page, perPage);
                                NclLib .alertMessage('success', 'Thông báo', arrResult['message']);
                            } else {
                                NclLib .alertMessage('danger', 'Lỗi', arrResult['message']);
                            }
                        }
                    });
                }
            },
            Close: {
                btnClass: 'btn btn-light',
                text: 'Đóng',
                action: function () {
                }
            }
        }
    });
}
/**
 * Validation
 */
JS_Articles.prototype.checkValidate = function() {
    var content = CKEDITOR.instances['content'].getData();
    var ResultTree = $('#TreeCategories').jstree('get_selected', true);
    var categories_id = '';
    for (var i in ResultTree) {
        var li_attr = ResultTree[i].li_attr;
        if (li_attr['is_last_item'] == '1') {
            categories_id = ResultTree[i].id;
            break;
        }
    }
    if(categories_id == ''){
        NclLib .alertMessage('warning', 'Cảnh báo', 'Bạn chưa chọn chuyên mục cho bài viết!', 5000);
        return false;
    }
    if($("#create_date").val() == ''){
        NclLib .alertMessage('warning', 'Cảnh báo', 'Ngày đăng không được để trống!', 5000);
        $("#create_date").focus();
        return false;
    }
    if($("#author").val() == ''){
        NclLib .alertMessage('warning', 'Cảnh báo', 'Tác giả không được để trống!', 5000);
        $("#author").focus();
        return false;
    }
    if($("#source").val() == ''){
        NclLib .alertMessage('warning', 'Cảnh báo', 'Nguồn tin không được để trống!', 5000);
        $("#source").focus();
        return false;
    }
    if($("#title").val() == ''){
        NclLib .alertMessage('warning', 'Cảnh báo', 'Tiêu đề không được để trống!', 5000);
        $("#title").focus();
        return false;
    }
    if($("#slug").val() == ''){
        NclLib .alertMessage('warning', 'Cảnh báo', 'Đường dẫn không được để trống!', 5000);
        $("#slug").focus();
        return false;
    }
    if(content == ''){
        NclLib .alertMessage('warning', 'Cảnh báo', 'Nội dung bài viết không được để trống!', 5000);
        return false;
    }
    if($("#order").val() == ''){
        NclLib .alertMessage('warning', 'Cảnh báo', 'Thứ tự không được để trống!', 5000);
        $("#order").focus();
        return false;
    }
    return true;
}
/** Xem ảnh khi chọn ảnh tải lên */
JS_Articles.prototype.PreviewFeatureImage = function () {
    var myClass = this;
    var filedata = document.getElementById("choose_img");
    $('#PreviewFeatureImage').html("<div class='col-md-3 text-center' id='file_image'><img class='img-responsive' width='200px' src='" + URL.createObjectURL(event.target.files[0]) + "'><a href='javascript:;' style='margin-top:10px' onclick=\"JS_Articles.deletefile_FeatureImage(\'" + filedata.files[0].name + "\'" + ")\"><i class='fas fa-trash' > </i> Xóa</a></div>");
    if (myClass.formdata) {
        myClass.formdata.append("choose_img", filedata.files[0]);
        myClass.formdata.delete("IMG_FEATURE");
    }
    $("#feature_img").val('');
}
JS_Articles.prototype.deletefile_FeatureImage = function (obj) {
    $('#file_image').remove();
    $("#choose_img").val('');
    $("#feature_img").val('');
    this.formdata.delete("feature_img");
    if (obj == 'xoa') {
        // console.log(obj);
        this.formdata.append("IMG_FEATURE", 'xoa_anh');
    }
    // $('#files').val('');
}
/** Xem file đính kèm */
JS_Articles.prototype.preview_images = function () {
    var myClass = this;
    var filedata = document.getElementById("images");
    var total_file = filedata.files.length;
    for (var i = 0; i < total_file; i++) {
        var file = filedata.files[i];
        var extensionFile = myClass.getextensionfile(file.name);
        if ((extensionFile == 'jpg' || extensionFile == 'png') && 1 == 2) {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindex) + "' class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[i]) + "'></div>");
        } else {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindex) + "' class='col-md-3'><i class='fas fa-file' aria-hidden='true'></i> <a target='_blank' href='" + URL.createObjectURL(event.target.files[i]) + "'>" + file.name + " </a><a href='javascript:;'><i class='fas fa-trash' onclick=\"JS_Articles.deletefile(\'" + file.name + "\'," + (i + myClass.countindex) + ")\"></i></a></div >");
        }
        if (myClass.formdata) {
            myClass.formdata.append("file" + (i + myClass.countindex), file);
        }
        myClass.countindex++;

    }
}
/** Xóa tệp đính kèm */
JS_Articles.prototype.deletefile = function (obj, a) {
    $('#file' + a).remove();
    this.formdata.delete("file" + a);
    $('#images').val('');
}
/** Xóa file trong dữ liệu */
JS_Articles.prototype.deletefileInSerVer = function (file_name, a) {
    var url = this.urlPath + '/deletefile';
    var myClass = this;
    var filename = file_name;
    var id = $('#file-preview-' + a).attr('data-id');
    $.confirm({
        title: 'Thông báo!',
        titleClass: 'tittleclass',
        content: 'Bạn có chắc chắn muốn xóa file này!',
        autoClose: 'Close|10000',
        buttons: {
            deleteFile: {
                btnClass: 'btn-blue',
                text: 'Xóa',
                action: function () {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'JSON',
                        data: {
                            _token: $('#_token').val(),
                            id: id,
                            filename: filename,
                        },
                        success: function () {
                            $('#file-preview-' + a).remove();
                        }
                    });
                }
            },
            Close: {
                btnClass: 'btn-red',
                text: 'Đóng',
                action: function () { }
            }
        }
    });
}
/**  */
JS_Articles.prototype.getextensionfile = function (filename) {
    return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
}
/** Xem thông tin bài viết */
JS_Articles.prototype.see = function (oForm) {
    var url = this.urlPath + '/see';
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
        NclLib .alertMessage('danger', "Bạn chưa chọn bài viết");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một bài viết");
        return false;
    }
    data += '&itemId=' + listitem;
    NclLib .showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            NclLib .successLoadImage();
            if (arrResult['danger']) {
                NclLib .alertMessage('danger', arrResult['message']);
            } else {
                $('#addList').html(arrResult);
                $('#addList').modal('show');
                $('.datepicker').datepicker();
            }
        }
    });
}




JS_Articles.prototype.check_duyet = function(id, C_STATUS_ARTICLES, PK_ARTICLES) {
    var url = this.urlPath + '/check_duyet';
    var data = {
        _token: $('#_token').val(),
        id: id,
        C_STATUS_ARTICLES: C_STATUS_ARTICLES,
        PK_ARTICLES: PK_ARTICLES
    }
    NclLib .showLoadMain();
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(result) {
            $('#trangthaitinbai').html(result);
            // $('#modalDialog').modal('show');
            NclLib .successLoadImage();
        }
    });
}
JS_Articles.prototype.validateform = function(oForm) {
    jQuery.validator.addMethod("rangePhone", function(value, element, params) {
        if (value.length >= params[0] && value.length <= params[1]) {
            return true;
        } else {
            return false;
        }
    });

    oForm.validate({
        onfocusout: false,
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                validator.errorList[0].element.focus();
            }
        },
        rules: {
            C_CREATE_DATE: "required",
            C_AUTHOR: "required",
            C_TITLE: "required",
            C_SLUG: "required",
            //            C_FEATURE_IMG: "required",
            // C_SUBJECT: "required",
            C_STATUS_ARTICLES: "required",
            C_ARTICLES_TYPE: "required",
        },
        messages: {
            C_CREATE_DATE: "Ngày đăng không được để trống",
            C_AUTHOR: "Tác giả không được để trống",
            C_TITLE: "Tiêu đề không được để trống",
            C_SLUG: "Đường dẫn không được để trống",
            //            C_FEATURE_IMG: "Ảnh bài viết không được để trống",
            // C_SUBJECT: "Trích dẫn không được để trống",
            C_STATUS_ARTICLES: "Trạng thái bài viết không được để trống",
            C_ARTICLES_TYPE: "Loại tin bài không được để trống",
        }
    });
}
JS_Articles.prototype.preview_images = function() {
    var myClass = this;
    var filedata = document.getElementById("images");
    var total_file = filedata.files.length;
    for (var i = 0; i < total_file; i++) {
        var file = filedata.files[i];
        var extensionFile = myClass.getextensionfile(file.name);
        if ((extensionFile == 'jpg' || extensionFile == 'png') && 1 == 2) {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindex) + "' class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[i]) + "'></div>");
        } else {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindex) + "' class='col-md-3'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='" + URL.createObjectURL(event.target.files[i]) + "'>" + file.name + " </a><i class='fa fa-trash-o' onclick=\"JS_Articles.deletefile(\'" + file.name + "\'," + (i + myClass.countindex) + ")\"></i></div >");
        }
        if (myClass.formdata) {
            myClass.formdata.append("file" + (i + myClass.countindex), file);
        }
        myClass.countindex++;

    }
}
JS_Articles.prototype.preview_file_articles = function() {
    var myClass = this;
    var filedata = document.getElementById("files");
    var total_file = filedata.files.length;
    for (var i = 0; i < total_file; i++) {
        var file = filedata.files[i];
        var extensionFile = myClass.getextensionfile(file.name);
        if ((extensionFile == 'jpg' || extensionFile == 'png') && 1 == 2) {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindexArticles) + "' class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[i]) + "'></div>");
        } else {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindexArticles) + "' class='col-md-3'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='" + URL.createObjectURL(event.target.files[i]) + "'>" + file.name + " </a><i class='fa fa-trash-o' onclick=\"JS_Articles.deletefile_articles(\'" + file.name + "\'," + (i + myClass.countindexArticles) + ")\"></i></div>");
        }
        if (myClass.formdataArticle) {
            myClass.formdataArticle.append("file" + (i + myClass.countindexArticles), file);
        }
        myClass.countindexArticles++;

    }
}
JS_Articles.prototype.updateComment = function(oForm) {
    if ($(oForm).valid()) {
        var myClass = this;
        var url = this.baseUrl + '/updateComment';
        myClass.formdataArticle.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        myClass.formdataArticle.append('data', data);
        if ($('#g-recaptcha-response').val() == '') {
            NclLib .alertMessage('danger', 'Cảnh báo', 'Bạn chưa xác thực người máy', 6000);
            return false;
        }
        NclLib .showmainloadding();
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: myClass.formdataArticle,
            processData: false,
            contentType: false,
            success: function(arrResult) {
                NclLib .successLoadImage();
                myClass.formdataArticle = new FormData();
                if (arrResult['success']) {
                    $('form#frmArticleComment').hide();
                    NclLib .alertMessage('success', arrResult['message']);
                } else {
                    NclLib .alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
                }
            },
            error: function(arrResult) {
                NclLib .alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
            }
        });
    }
}
JS_Articles.prototype.validate_comment = function(oForm) {
    jQuery.validator.addMethod("rangePhone", function(value, element, params) {
        if (value.length >= params[0] && value.length <= params[1]) {
            return true;
        } else {
            return false;
        }
    });

    oForm.validate({
        onfocusout: false,
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                validator.errorList[0].element.focus();
            }
        },
        rules: {
            C_NAME_SENDER: "required",
            C_ADDRESS_SENDER: "required",
            C_PHONE_SENDER: { required: true, "number": true },
            C_SUBJECT: "required",
            C_CONTENT: "required",
        },
        messages: {
            C_NAME_SENDER: "Họ và tên không được để trống",
            C_ADDRESS_SENDER: "Địa chỉ không được để trống",
            C_PHONE_SENDER: {
                required: "Số điện thoại không được để trống",
                number: "Phải là ký tự số"
            },
            C_SUBJECT: "Tiêu đề không được để trống",
            C_CONTENT: "Nội dung không được để trống",
        }
    });
}
JS_Articles.prototype.manager_comment = function(oForm) {
    var url = this.urlPath + '/manager_comment';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    $(p_chk_obj).each(function() {
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
        NclLib .alertMessage('danger', "Bạn chưa chọn bài viết");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một bài viết");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function(arrResult) {
            $('#modalArticles').html(arrResult);
            $('#frmArticlesIndex').hide();
            $('#modalArticles').show();
            $('#close_modal').click(function() {
                myClass.backtoindex();
            });
            $('.datepicker').datepicker();
            myClass.loadIndexManagerComment()
        }
    });
}

JS_Articles.prototype.loadIndexManagerComment = function() {
    var myClass = this;
    var oForm = 'form#frmCommentIndex';
    myClass.loadListComment(oForm);
    // Them moi loai danh muc
    $(oForm).find('#btn_see_comment').click(function() {
        myClass.see_comment(oForm);
    });
    $(oForm).find('#btn_search').click(function() {
        myClass.loadListComment(oForm);
    });

    $(document).keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            myClass.loadList(oForm);
            return false;
        }
    });
    // Xoa doi tuong
    // $(oForm).find('#btn_delete').confirmation({
    //     rootSelector: '[data-toggle=confirmation]',
    //     onConfirm: function() {
    //         myClass.delete_comment(oForm);
    //     }
    // });
    // Tim kiem loai danh muc
    $(oForm).find('#listtype').change(function() {
        myClass.loadList(oForm);
    });
}
JS_Articles.prototype.loadListComment = function(oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmCommentIndex';
    var myClass = this;
    var loadding = NclLib .loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadlist_comment';
    var data = $(oForm).serialize();
    data += '&currentPage=' + currentPage;
    data += '&perPage=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function(arrResult) {
            loadding.go(100);
            var shtml = myClass.genTableComment(arrResult['Dataloadlist']['data']);
            // phan trang
            $('#pagination').html(arrResult.pagination);
            $(oForm).find('.main_paginate .pagination a').click(function() {
                var page = $(this).attr('page');
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadListComment(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function() {
                var page = $(oForm).find('#_currentPage').val();
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadListComment(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').val(arrResult.perPage);
            loadding.go(100);
        }
    });
}
JS_Articles.prototype.genTableComment = function(arrResult) {
    var shtml = '';
    var status = 'Không hiển thị';
    for (var x in arrResult) {
        if (arrResult[x]['C_STATUS'] == 'MOI_GUI') {
            status = 'Mới gửi'
        } else if (arrResult[x]['C_STATUS'] == 'HIEN_THI') {
            status = 'Hiển thị'
        }
        shtml += '<tr>';
        shtml += '<td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="' + arrResult[x]['PK_ARTICLES_COMMENT'] + '"></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_NAME_SENDER'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_CREATE'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + ((arrResult[x]['C_CONTENT'] == null) ? '' : arrResult[x]['C_CONTENT']) + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + status + '</td>';
        shtml += '</tr>';
    }
    $('#data-list-comment').html(shtml);
}
JS_Articles.prototype.see_comment = function(oForm) {
    var url = this.urlPath + '/see_comment';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $(oForm + ' #table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    $(p_chk_obj).each(function() {
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
    NclLib .showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function(arrResult) {
            NclLib .successLoadImage();
            $('#modalRelateImages').html(arrResult);
            $('#frmCommentIndex').hide();
            $('#modalRelateImages').show();
            $('#close_modal_2').click(function() {
                myClass.backtocommentmanagerindex();
                return false;
            });
            myClass.validateform($('form#frmAddImages'));
            $('.datepicker').datepicker();
        }
    });
}
JS_Articles.prototype.backtocommentmanagerindex = function() {
    $('#modalRelateImages').html('');
    $('#frmCommentIndex').show();
    $('#modalRelateImages').hide();
}
JS_Articles.prototype.ApproveComment = function(oForm) {
    if (oForm.valid()) {
        var url = this.urlPath + '/approve_comment';
        var myClass = this;
        var data = $(oForm).serialize();
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: data,
            //            processData: true,
            //            contentType: true,
            success: function(arrResult) {
                if (arrResult['success']) {
                    myClass.loadListComment('form#frmCommentIndex');
                    $('#modalRelateImages').html('');
                    $('#frmCommentIndex').show();
                    $('#modalRelateImages').hide();
                    NclLib .alertMessage('success', arrResult['message']);
                } else {
                    NclLib .alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
                }
            },
            error: function(arrResult) {
                NclLib .alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
            }
        });
    }
}

JS_Articles.prototype.delete_comment = function(oForm) {
    var url = this.urlPath + '/delete_comment';
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $(oForm + ' #table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function() {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == '') {
        NclLib .alertMessage('danger', "Bạn chưa chọn bình luận cần xóa");
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
        success: function(arrResult) {
            if (arrResult['success']) {
                myClass.loadListComment(oForm);
                NclLib .alertMessage('success', arrResult['message']);
            } else {
                NclLib .alertMessage('danger', arrResult['message']);
            }
        }
    });
}
JS_Articles.prototype.approval = function(oForm) {
    var url = this.urlPath + '/approval';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    $(p_chk_obj).each(function() {
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
        NclLib .alertMessage('danger', "Bạn chưa chọn bài viết");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một bài viết");
        return false;
    }
    data += '&itemId=' + listitem;
    NclLib .showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function(arrResult) {
            NclLib .successLoadImage();
            if (arrResult['danger']) {
                NclLib .alertMessage('danger', arrResult['message']);
            } else {
                $('#modalArticles').html(arrResult);
                $('#frmArticlesIndex').hide();
                $('#modalArticles').show();
                $('#close_modal').click(function() {
                    myClass.backtoindex();
                });
                $('.datepicker').datepicker();
            }
        }
    });
}

JS_Articles.prototype.update_approval = function(oForm) {
    var url = this.urlPath + '/update_approval';
    var myClass = this;
    var data = $(oForm).serialize();
    NclLib .showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function(arrResult) {
            NclLib .successLoadImage();
            myClass.formdata = new FormData();
            // oForm = 'form#frmArticlesIndex';
            var page = $('form#frmArticlesIndex').find('#_currentPage').val();
            var perPage = $('form#frmArticlesIndex').find('#cbo_nuber_record_page').val();
            if (arrResult['success']) {
                $('#frmArticlesIndex').show();
                $('#modalArticles').hide();
                myClass.loadList(oForm, page, perPage);
                NclLib .alertMessage('success', arrResult['message']);
            } else {
                NclLib .alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
            }
        },
        error: function(arrResult) {
            NclLib .alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
        }
    });
}

JS_Articles.prototype.refuse = function(oForm) {
    var url = this.urlPath + '/refuse';
    var myClass = this;
    var data = $(oForm).serialize();
    NclLib .showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function(arrResult) {
            NclLib .successLoadImage();
            myClass.formdata = new FormData();
            // oForm = 'form#frmArticlesIndex';
            var page = $('form#frmArticlesIndex').find('#_currentPage').val();
            var perPage = $('form#frmArticlesIndex').find('#cbo_nuber_record_page').val();
            if (arrResult['success']) {
                $('#frmArticlesIndex').show();
                $('#modalArticles').hide();
                myClass.loadList(oForm, page, perPage);
                NclLib .alertMessage('success', arrResult['message']);
            } else {
                NclLib .alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
            }
        },
        error: function(arrResult) {
            NclLib .alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
        }
    });
}
JS_Articles.prototype.view_detail = function() {
    var url = this.urlPath + '/view_detail';
    $.ajax({
        url: url,
        type: "GET",
        //cache: true,
        success: function(arrResult) {
            NclLib .successLoadImage();
            $('#modalDialog').html(arrResult);
            $('#modalDialog').modal('show');
        }
    });
}
