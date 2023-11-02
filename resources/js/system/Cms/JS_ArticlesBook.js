function JS_ArticlesBook(baseUrl, module, action) {
    // check side bar
    //    $("#main_cms").attr("class", "active");
    $("#main_articlesbook").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action; //Biên public lưu tên module
    this.formdata = new FormData();
    this.formdataArticle = new FormData();
    this.countindex = 0;
    this.countindexArticles = 0;
}
// Load su kien tren man hinh index
JS_ArticlesBook.prototype.loadIndex = function() {
        var myClass = this;
        var oForm = 'form#frmArticlesBookIndex';
        myClass.loadList(oForm);
        // Them moi loai danh muc
        $(oForm).find('#btn_add').click(function() {
            myClass.add(oForm);
        });
        $(oForm).find('#btn_edit').click(function() {
            myClass.edit(oForm);
        });
        $(oForm).find('#btn_search').click(function() {
            myClass.loadList(oForm);
        });
        $(oForm).find('#btn_manager_comment').click(function() {
            myClass.manager_comment(oForm);
        });
        $(document).keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                myClass.loadList(oForm);
                return false;
            }
        });
        // Xoa doi tuong
        $(oForm).find('#btn_delete').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function() {
                myClass.delete(oForm);
            }
        });
        // Tim kiem loai danh muc
        $(oForm).find('#listtype').change(function() {
            myClass.loadList(oForm);
        });
    }
    // Load su kien tren cac minh khac
JS_ArticlesBook.prototype.loadevent = function(oForm) {
        var myClass = this;
        $(oForm).find('#btn_update').click(function() {
            myClass.update(oForm);
        });
    }
    // Lay du lieu cho man hinh danh sach
JS_ArticlesBook.prototype.loadList = function(oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmArticlesBookIndex';
    var myClass = this;
    var loadding = EFYLib.loadding();
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
        success: function(arrResult) {
            loadding.go(100);
            var shtml = myClass.genTable(arrResult['Dataloadlist']['data']);
            // phan trang
            $('#pagination').html(arrResult.pagination);
            $(oForm).find('.main_paginate .pagination a').click(function() {
                var page = $(this).attr('page');
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function() {
                var page = $(oForm).find('#_currentPage').val();
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').val(arrResult.perPage);
            loadding.go(100);
        }
    });
}
JS_ArticlesBook.prototype.genTable = function(arrResult) {
        var shtml = '';
        var status = 'Không hoạt động';
        var oldPosGroup = '';
        for (var x in arrResult) {
            if (arrResult[x]['C_STATUS'] == 'HOAT_DONG') {
                status = 'Hoạt động'
            } else {
                status = 'Không hoạt động'
            }
            shtml += '<tr>';
            shtml += '<td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="' + arrResult[x]['PK_CMS_ARTICLE_BOOK'] + '"></td>';
            shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_CREATE'] + '</td>';
            shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_TITLE'] + '</td>';
            shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_AUTHOR'] + '</td>';
            shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + status + '</td>';
            shtml += '</tr>';
        }
        $('#data-list-articles').html(shtml);
    }
    // Them loai danh muc
JS_ArticlesBook.prototype.add = function(oForm) {
    EFYLib.showmainloadding();
    var url = this.urlPath + '/articlesbook_add';
    var myClass = this;
    myClass.countindex = 0;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function(arrResult) {
            EFYLib.successLoadImage();
            $('#modalArticlesBook').html(arrResult);
            $('#frmArticlesBookIndex').hide();
            $('#modalArticlesBook').show();
            $('#close_modal').click(function() {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddArticles'));
            $('.datepicker').datepicker();
            $('.chzn-select').chosen({
                height: '100%',
                width: '100%'
            });
        }
    });
}

JS_ArticlesBook.prototype.validateform = function(oForm) {
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
            FK_PUBLICATION: "required",
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
            FK_PUBLICATION: "Ấn phẩm không được để trống"
        }
    });
}

JS_ArticlesBook.prototype.backtoindex = function() {
        $('#modalArticlesBook').html('');
        $('#frmArticlesBookIndex').show();
        $('#modalArticlesBook').hide();
    }
    // sua
JS_ArticlesBook.prototype.edit = function(oForm) {
        var url = this.urlPath + '/articlesbook_edit';
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
            EFYLib.alertMessage('danger', "Bạn chưa chọn bài viết cần sửa");
            return false;
        }
        if (i > 1) {
            EFYLib.alertMessage('danger', "Bạn chỉ được chọn một bài viết để sửa");
            return false;
        }
        data += '&itemId=' + listitem;
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: data,
            success: function(arrResult) {
                $('#modalArticlesBook').html(arrResult);
                $('#frmArticlesBookIndex').hide();
                $('#modalArticlesBook').show();
                $('#close_modal').click(function() {
                    myClass.backtoindex();
                });
                myClass.validateform($('form#frmAddArticles'));
                $('.datepicker').datepicker();
                $('.chzn-select').chosen({
                    height: '100%',
                    width: '100%'
                });
            }
        });
    }
    // Them loai danh muc
JS_ArticlesBook.prototype.delete = function(oForm) {
    var url = this.urlPath + '/delete';
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
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
        EFYLib.alertMessage('danger', "Bạn chưa chọn bài viết cần xóa");
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
                myClass.loadList(oForm);
                EFYLib.alertMessage('success', arrResult['message']);
            } else {
                EFYLib.alertMessage('danger', arrResult['message']);
            }
        }
    });
}
JS_ArticlesBook.prototype.preview_images = function() {
    var myClass = this;
    var filedata = document.getElementById("images");
    var total_file = filedata.files.length;
    for (var i = 0; i < total_file; i++) {
        var file = filedata.files[i];
        var extensionFile = myClass.getextensionfile(file.name);
        if ((extensionFile == 'jpg' || extensionFile == 'png') && 1 == 2) {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindex) + "' class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[i]) + "'></div>");
        } else {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindex) + "' class='col-md-3'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='" + URL.createObjectURL(event.target.files[i]) + "'>" + file.name + " </a><i class='fa fa-trash-o' onclick=\"JS_ArticlesBook.deletefile(\'" + file.name + "\'," + (i + myClass.countindex) + ")\"></i></div >");
        }
        if (myClass.formdata) {
            myClass.formdata.append("file" + (i + myClass.countindex), file);
        }
        myClass.countindex++;

    }
}
JS_ArticlesBook.prototype.preview_file_articles = function() {
    var myClass = this;
    var filedata = document.getElementById("files");
    var total_file = filedata.files.length;
    for (var i = 0; i < total_file; i++) {
        var file = filedata.files[i];
        var extensionFile = myClass.getextensionfile(file.name);
        if ((extensionFile == 'jpg' || extensionFile == 'png') && 1 == 2) {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindexArticles) + "' class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[i]) + "'></div>");
        } else {
            $('#image_preview').append("<div  id='file" + (i + myClass.countindexArticles) + "' class='col-md-3'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='" + URL.createObjectURL(event.target.files[i]) + "'>" + file.name + " </a><i class='fa fa-trash-o' onclick=\"JS_ArticlesBook.deletefile_articles(\'" + file.name + "\'," + (i + myClass.countindexArticles) + ")\"></i></div>");
        }
        if (myClass.formdataArticle) {
            myClass.formdataArticle.append("file" + (i + myClass.countindexArticles), file);
        }
        myClass.countindexArticles++;

    }
}

JS_ArticlesBook.prototype.PreviewFeatureImage = function() {
    var myClass = this;
    var filedata = document.getElementById("C_FEATURE_IMG");
    $('#PreviewFeatureImage').html("<div class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[0]) + "'></div>");
    if (myClass.formdata) {
        myClass.formdata.append("C_FEATURE_IMG", filedata.files[0]);
    }
}

// Cap nhat loai danh muc
JS_ArticlesBook.prototype.update = function(oForm) {
    if ($(oForm).valid()) {
        var url = this.urlPath + '/update';
        var myClass = this;
        myClass.formdata.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        var value = CKEDITOR.instances['C_CONTENT'].getData();
        var ResultTree = $('#TreeCategories').jstree('get_selected', true);
        var FK_CATEGORY = '';
        if (value == '') {
            EFYLib.alertMessage('danger', "Nội dung không được để trống");
            return false;
        }
        myClass.formdata.append('data', data);
        myClass.formdata.append('C_CONTENT', value);
        myClass.formdata.append('FK_CATEGORY', FK_CATEGORY);
        myClass.formdata.append('C_OWNER_CODE', $('#C_OWNER_CODE').val());
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: myClass.formdata,
            processData: false,
            contentType: false,
            success: function(arrResult) {
                myClass.formdata = new FormData();
                if (arrResult['success']) {
                    myClass.loadList(oForm);
                    $('#btn_add').click();
                    EFYLib.alertMessage('success', arrResult['message']);
                } else {
                    EFYLib.alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
                }
            },
            error: function(arrResult) {
                EFYLib.alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
            }
        });
    }
}
JS_ArticlesBook.prototype.getextensionfile = function(filename) {
    return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
}
JS_ArticlesBook.prototype.deletefile = function(obj, a) {
    $('#file' + a).remove();
    this.formdata.delete("file" + a);
    $('#images').val('');
}
JS_ArticlesBook.prototype.deletefile_articles = function(obj, a) {
    $('#file' + a).remove();
    this.formdataArticle.delete("file" + a);
    $('#files').val('');
}

JS_ArticlesBook.prototype.updateComment = function(oForm) {
    if ($(oForm).valid()) {
        var myClass = this;
        var url = this.baseUrl + '/updateComment';
        myClass.formdataArticle.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        myClass.formdataArticle.append('data', data);
        if ($('#g-recaptcha-response').val() == '') {
            EFYLib.alertMessage('danger', 'Cảnh báo', 'Bạn chưa xác thực người máy', 6000);
            return false;
        }
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: myClass.formdataArticle,
            processData: false,
            contentType: false,
            success: function(arrResult) {
                myClass.formdataArticle = new FormData();
                if (arrResult['success']) {
                    $('form#frmArticleComment').hide();
                    EFYLib.alertMessage('success', arrResult['message']);
                } else {
                    EFYLib.alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
                }
            },
            error: function(arrResult) {
                EFYLib.alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
            }
        });
    }
}

JS_ArticlesBook.prototype.validate_comment = function(oForm) {
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
JS_ArticlesBook.prototype.manager_comment = function(oForm) {
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
        EFYLib.alertMessage('danger', "Bạn chưa chọn bài viết");
        return false;
    }
    if (i > 1) {
        EFYLib.alertMessage('danger', "Bạn chỉ được chọn một bài viết");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function(arrResult) {
            $('#modalArticlesBook').html(arrResult);
            $('#frmArticlesBookIndex').hide();
            $('#modalArticlesBook').show();
            $('#close_modal').click(function() {
                myClass.backtoindex();
            });
            $('.datepicker').datepicker();
            myClass.loadIndexManagerComment()
        }
    });
}
JS_ArticlesBook.prototype.loadIndexManagerComment = function() {
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
    $(oForm).find('#btn_delete').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function() {
            myClass.delete_comment(oForm);
        }
    });
    // Tim kiem loai danh muc
    $(oForm).find('#listtype').change(function() {
        myClass.loadList(oForm);
    });
}
JS_ArticlesBook.prototype.loadListComment = function(oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmCommentIndex';
    var myClass = this;
    var loadding = EFYLib.loadding();
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
JS_ArticlesBook.prototype.genTableComment = function(arrResult) {
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
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_SUBJECT'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + status + '</td>';
        shtml += '</tr>';
    }
    $('#data-list-comment').html(shtml);
}
JS_ArticlesBook.prototype.see_comment = function(oForm) {
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
        EFYLib.alertMessage('danger', "Bạn chưa chọn ảnh cần sửa");
        return false;
    }
    if (i > 1) {
        EFYLib.alertMessage('danger', "Bạn chỉ được chọn một ảnh để sửa");
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function(arrResult) {
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
JS_ArticlesBook.prototype.backtocommentmanagerindex = function() {
    $('#modalRelateImages').html('');
    $('#frmCommentIndex').show();
    $('#modalRelateImages').hide();
}
JS_ArticlesBook.prototype.ApproveComment = function(oForm) {
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
                    EFYLib.alertMessage('success', arrResult['message']);
                } else {
                    EFYLib.alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
                }
            },
            error: function(arrResult) {
                EFYLib.alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
            }
        });
    }
}

JS_ArticlesBook.prototype.delete_comment = function(oForm) {
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
        EFYLib.alertMessage('danger', "Bạn chưa chọn bình luận cần xóa");
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
                EFYLib.alertMessage('success', arrResult['message']);
            } else {
                EFYLib.alertMessage('danger', arrResult['message']);
            }
        }
    });
}

JS_ArticlesBook.prototype.deletefileInSerVer = function(file_name, a) {
    var url = this.urlPath + '/deletefile';
    var myClass = this;
    var filename = file_name;
    var pkrecord = $('#file-preview-' + a).attr('data-id');
    $.confirm({
        title: 'Thông báo!',
        titleClass: 'tittleclass',
        content: 'Bạn có chắc chắn muốn xóa file này!',
        autoClose: 'Close|10000',
        buttons: {
            deleteFile: {
                btnClass: 'btn-blue',
                text: 'Xóa',
                action: function() {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'JSON',
                        data: {
                            _token: $('#_token').val(),
                            filename: filename,
                            pkrecord: pkrecord
                        },
                        success: function() {
                            $('#file-preview-' + a).remove();
                        }
                    });
                }
            },
            Close: {
                btnClass: 'btn-red',
                text: 'Đóng',
                action: function() {}
            }
        }
    });
}

JS_ArticlesBook.prototype.search_document = function() {
    var url = this.urlPath + '/search_publications';
    var data = $('#frmArticlesBookIndex').serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function(arrResult) {
            $('#modalDialog').html(arrResult);
            $('#modalDialog').modal('show');
        }
    });
}
JS_ArticlesBook.prototype.load_document = function(oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmSearchDocuments';
    var myClass = this;
    var loadding = EFYLib.loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/load_publication';
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
            var shtml = myClass.genTablePublication(arrResult['Dataloadlist']['data']);
            // phan trang
            $('form#frmSearchDocuments #pagination').html(arrResult.pagination);
            $(oForm).find('.main_paginate .pagination a').click(function() {
                var page = $(this).attr('page');
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.load_document(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function() {
                var page = $(oForm).find('#_currentPage').val();
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.load_document(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').val(arrResult.perPage);
            loadding.go(100);
        }
    });

}

JS_ArticlesBook.prototype.genTablePublication = function(arrResult) {
    var shtml = '',
        filename = '';
    var status = 'Không hoạt động';
    var oldPosGroup = '';
    for (var x in arrResult) {
        if (arrResult[x]['C_FILE_NAME'] != '' && arrResult[x]['C_FILE_NAME'] != null) {
            var arrFile = arrResult[x]['C_FILE_NAME'].split('!~!');
            var namefile = arrFile.pop();
            filename = '<div> <i class="fa fa-paperclip" aria-hidden="true"></i> <a target="_blank" href="' + this.baseUrl + '/admin/catalog/getfile/' + arrFile[0] + '/' + namefile + '">' + namefile + ' </a></div> ';
        } else {
            filename = '';
        }
        if (arrResult[x]['C_NAME'] != null && arrResult[x]['C_NAME'] != '') {
            filename = '<br>' + filename;
        }
        shtml += '<tr style="cursor:pointer">';
        shtml += '<td align="center"><input type="checkbox"  fullname="' + arrResult[x]['C_FULL_NAME'] + '" onclick="{select_checkbox_row(this);}" name="chk_item_id_publication" value="' + arrResult[x]['PK_PUBLICATION'] + '"></td>';
        shtml += '<td class="data" align="left"  onclick="{select_row(this);}">' + arrResult[x]['C_CODE'] + '</td>';
        shtml += '<td class="data" align="left"  onclick="{select_row(this);}">' + (arrResult[x]['C_FULL_NAME'] == null ? '' : arrResult[x]['C_FULL_NAME']) + filename + '</td>';
        shtml += '<td class="data" align="left"  onclick="{select_row(this);}">' + (arrResult[x]['C_TAC_GIA'] == null ? '' : arrResult[x]['C_TAC_GIA']) + '</td>';
        shtml += '<td class="data" align="left"  onclick="{select_row(this);}">' + (arrResult[x]['NHA_XUAT_BAN'] == null ? '' : arrResult[x]['NHA_XUAT_BAN']) + '</td>';
        shtml += '<td class="data" align="center"  onclick="{select_row(this);}">' + arrResult[x]['C_STATUS_BOOKSHELF'] + '</td>';
        shtml += '</tr>';
    }
    $('#data-list').html(shtml);
}

JS_ArticlesBook.prototype.submit_document = function(oForm) {
    var listitem = '';
    var i = 0,
        nhande;
    $('input[name="chk_item_id_publication"]').each(function() {
        if ($(this).is(':checked')) {
            listitem += $(this).val();
            nhande = $(this).attr('fullname');
            i++;
        }
    });
    if (i == 0) {
        EFYLib.alertMessage('danger', "Bạn chưa chọn văn bản cần thêm");
        return false;
    }
    if (i > 1) {
        EFYLib.alertMessage('danger', "Bạn chỉ được chọn một văn bản để thêm");
        return false;
    }
    $('form#frmAddArticles #FK_PUBLICATION').val(listitem);
    $('form#frmAddArticles #text_nhande').text(nhande);
    $('#modalDialog').html('');
    $('#modalDialog').modal('hide');
}