function JS_Questions(baseUrl, module, action) {
    // check side bar
    //    $("#main_cms").attr("class", "active");
    $("#main_questions").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action; //Biên public lưu tên module
    this.formdata = new FormData();
    this.countindex = 0;
    this.formdataQuestion = new FormData();
    this.countindexQuestions = 0;
}
JS_Questions.prototype.loadIndex = function() {
    var myClass = this;
    var oForm = 'form#frmQuestionsIndex';
    myClass.loadList(oForm);
    $(oForm).find('#btn_add').click(function() {
        myClass.add(oForm);
    });
    $(oForm).find('#btn_edit').click(function() {
        myClass.edit(oForm);
    });
    $(oForm).find('#btn_search').click(function() {
        myClass.loadList(oForm);
    });
    $(oForm).find('#btn_answer').click(function() {
        myClass.answer_question(oForm);
    });
    $(document).keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            myClass.loadList(oForm);
            return false;
        }
    });
    $(oForm).find('#btn_delete').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function() {
            myClass.delete(oForm);
        }
    });
}
JS_Questions.prototype.genQuestion = function(arrResult) {
    var shtml = '';
    var status = 'Không hoạt động';
    for (var x in arrResult) {
        if (arrResult[x]['C_STATUS'] == 'HOAT_DONG') {
            status = 'Hoạt động'
        } else {
            status = 'Không hoạt động'
        }
        shtml += '<tr>';
        shtml += '<td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="' + arrResult[x]['PK_CMS_QUESTION'] + '"></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_CONTENT'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_CATEGORY_NAME'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + ((arrResult[x]['TRA_LOI'] == 0) ? 'Chưa trả lời' : 'Đã trả lời') + '</td>';

        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_STATUS_QUESTION'] + '</td>';
        shtml += '</tr>';
    }
    $('#data-list-questions').html(shtml);
}
JS_Questions.prototype.loadList = function(oForm, currentPage = 1, perPage = 15) {
    oForm = 'form#frmQuestionsIndex';
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
        data: data,
        success: function(arrResult) {
            loadding.go(100);
            var shtml = myClass.genQuestion(arrResult['Dataloadlist']['data']);
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

JS_Questions.prototype.add = function(oForm) {
    NclLib .showmainloadding();
    var url = this.urlPath + '/questions_add';
    var myClass = this;
    myClass.countindex = 0;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult) {
            NclLib .successLoadImage();
            $('#modalQuestions').html(arrResult);
            $('#frmQuestionsIndex').hide();
            $('#modalQuestions').show();
            $('#close_modal').click(function() {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddQuestions'));
            $('.chzn-select').chosen({
                height: '100%',
                width: '100%'
            });
        }
    });
}

JS_Questions.prototype.update = function(oForm) {
    if ($(oForm).valid()) {
        var url = this.urlPath + '/update';
        var myClass = this;
        myClass.formdata.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        myClass.formdata.append('data', data);
        $.ajax({
            url: url,
            type: "POST",
            data: myClass.formdata,
            processData: false,
            contentType: false,
            success: function(arrResult) {
                myClass.formdata = new FormData();
                if (arrResult['success']) {
                    myClass.loadList(oForm);
                    $('#modalQuestions').html('');
                    $('#frmQuestionsIndex').show();
                    $('#modalQuestions').hide();
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

JS_Questions.prototype.delete = function(oForm) {
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
        NclLib .alertMessage('danger', "Bạn chưa chọn câu hỏi cần xóa");
        return false;
    }
    var data = $(oForm).serialize();
    data += '&listitem=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: data,
        success: function(arrResult) {
            if (arrResult['success']) {
                myClass.loadList(oForm);
                NclLib .alertMessage('success', arrResult['message']);
            } else {
                NclLib .alertMessage('danger', arrResult['message']);
            }
        }
    });
}

JS_Questions.prototype.edit = function(oForm) {
    var url = this.urlPath + '/questions_edit';
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
        NclLib .alertMessage('danger', "Bạn chưa chọn câu hỏi cần sửa");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một câu hỏi để sửa");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult) {
            $('#modalQuestions').html(arrResult);
            $('#frmQuestionsIndex').hide();
            $('#modalQuestions').show();
            $('#close_modal').click(function() {
                myClass.backtoindex();
            });
            myClass.validateform($('form#frmAddQuestions'));
            $('.chzn-select').chosen({
                height: '100%',
                width: '100%'
            });
        }
    });
}

JS_Questions.prototype.answer_question = function(oForm) {
    var url = this.urlPath + '/answer_question';
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
        NclLib .alertMessage('danger', "Bạn chưa chọn câu hỏi");
        return false;
    }
    if (i > 1) {
        NclLib .alertMessage('danger', "Bạn chỉ được chọn một câu hỏi");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function(arrResult) {
            $('#modalQuestions').html(arrResult);
            $('#frmQuestionsIndex').hide();
            $('#modalQuestions').show();
            $('#close_modal').click(function() {
                myClass.backtoindex();
            });
        }
    });
}

JS_Questions.prototype.send_answer = function(oForm) {
    if ($(oForm).valid()) {
        var url = this.urlPath + '/send-answer';
        var myClass = this;
        myClass.formdataQuestion.append('_token', $('#_token').val());
        var data = $(oForm).serialize();
        var value = CKEDITOR.instances['C_ANSWER_CONTENT'].getData();
        if (value == '') {
            NclLib .alertMessage('danger', "Nội dung câu trả lời không được để trống");
            return false;
        }
        var FK_QUESTION = '';
        myClass.formdataQuestion.append('data', data);
        myClass.formdataQuestion.append('C_ANSWER_CONTENT', value);
        myClass.formdataQuestion.append('FK_QUESTION', FK_QUESTION);
        myClass.formdataQuestion.append('C_OWNER_CODE', $('#C_OWNER_CODE').val());
        $.ajax({
            url: url,
            type: "POST",
            data: myClass.formdataQuestion,
            processData: false,
            contentType: false,
            success: function(arrResult) {
                myClass.formdataQuestion = new FormData();
                if (arrResult['success']) {
                    myClass.loadList(oForm);
                    $('#modalQuestions').html('');
                    $('#frmAnswerQuestion').hide();
                    $('#frmQuestionsIndex').show();
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

JS_Questions.prototype.preview_files = function() {
    var myClass = this;
    var filedata = document.getElementById("file");
    var total_file = filedata.files.length;
    for (var i = 0; i < total_file; i++) {
        var file = filedata.files[i];
        var extensionFile = myClass.getextensionfile(file.name);
        if ((extensionFile == 'jpg' || extensionFile == 'png') && 1 == 2) {
            $('#image_preview').append("<div name='file" + i + "' id='file" + (i + myClass.countindexQuestions) + "' class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[i]) + "'></div>");
        } else {
            $('#image_preview').append("<div name='file" + i + "' id='file" + (i + myClass.countindexQuestions) + "' class='col-md-3'><i class='fa fa-file' aria-hidden='true'></i> <a target='_blank' href='" + URL.createObjectURL(event.target.files[i]) + "'>" + file.name + " </a><i class='fa fa-trash-o' onclick=\"JS_Questions.deletefile(\'" + file.name + "\'," + (i + myClass.countindexQuestions) + ")\"></i></div>");
        }
        if (myClass.formdataQuestion) {
            console.log("file" + (i + myClass.countindexQuestions));
            myClass.formdataQuestion.append("file" + (i + myClass.countindexQuestions), file);
        }
        myClass.countindexQuestions++;

    }
}

JS_Questions.prototype.backtoindex = function() {
    $('#modalQuestions').html('');
    $('#frmQuestionsIndex').show();
    $('#modalQuestions').hide();
}

JS_Questions.prototype.getextensionfile = function(filename) {
    return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
}
JS_Questions.prototype.deletefile = function(obj, a) {
    $('#file' + a).remove();
    this.formdata.delete("file" + a);
    $('#files').val('');
}

JS_Questions.prototype.validateform = function(oForm) {
    oForm.validate({
        onfocusout: false,
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                validator.errorList[0].element.focus();
            }
        },
        rules: {
            C_CONTENT: "required",
            C_CATEGORY: "required",
        },
        messages: {
            C_CONTENT: "Nội dung câu hỏi không được để trống",
            C_CATEGORY: "Lĩnh vực không được để trống",
        }
    });
}