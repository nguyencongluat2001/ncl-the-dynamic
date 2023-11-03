/*
* Author: Toanph
* Idea: Class xu ly du lieu xml
*/
var TableXml = function (dirxml) {
    this.dirxml = dirxml;
    this.xml = '';
    this.identity = 0;
    this.loadfile();
    this.htmlexport = '';
    this.row_index = '';
    this.v_current_style_name = '';
    this.v_id_column = '';
    // this.v_onclick_up = '';
    this.v_onclick = '';
    this.v_ondblclick = '';
    // this.v_onclick_down = '';
    this.v_have_move = '';
    this.v_table = '';
    this.v_pk_column = '';
    this.v_filename_column = '';
    this.content_column = '';
    this.v_append_column = '';
    this.p_arr_item = '';
    this.display_option = '';
    this.url_exec = '';
    this.pClassname = '';
    this.objectId = '';
    this.v_width = '';
    this.v_label = '';
    this.v_align = '';
    this.columnName = '';
    this.alias_name = '';
    this.xmlData = '';
    this.xmlTagInDb = '';
    this.v_value = '';
    this.tablename = '';
    this.datasearch = false;
    this.oClass = '';
    this.phpfunction = '';
    this.modaldialog = false;
    this.functionload = 'loadList()';
}

TableXml.prototype.loadfile = function () {
    var Ncl_xml = this;
    $.ajax({
        type: "GET",
        url: this.dirxml,
        dataType: "xml",
        success: function (xml) {
            Ncl_xml.xml = xml;
        }
    })
}

/*
* @Idea: Xuat man hinh danh sach
* Param: 	- arrResult: Mang du lieu can xuat ra
            - objupdate: Doi tuong can update toi
            - v_pk_column: Key cua col
*/
TableXml.prototype.exportTable = function (arrResult, v_pk_column, objupdate, fulltextsearch, frm, numRow, addrow) {
    if (typeof (addrow) === 'undefined') {
        addrow = true;
    };
    if (typeof (numRow) === 'undefined') {
        numRow = 10;
    };
    var classXml = this;
    if (typeof (this.xml) !== 'object') {
        setTimeout(function () {
            classXml.exportTable(arrResult, v_pk_column, objupdate, fulltextsearch, frm);
        }, 100);
    } else {
        classXml.generalTable(arrResult, v_pk_column, objupdate, fulltextsearch, frm, numRow, addrow);
    }
}

//Generate column of table structure
TableXml.prototype.generalTable = function (arrResult, v_pk_column, objupdate, fulltextsearch, frm, numRow, addrow) {
    var Ncl_xml = this, count_column = '', colspan = 0, xmlString = '';
    Ncl_xml.v_pk_column = v_pk_column;
    Ncl_xml.htmlexport = '';
    var xml = this.xml, iTotalRecord = arrResult.length;
    Ncl_xml.htmlexport += '<table class="table table-striped table-bordered dataTable no-footer" align="center" id="table-data">';
    var psHtmlTempWidth = '', psHtmlTempLabel = '', v_type = '';
    $(xml).find('list_body col').each(function () {
        Ncl_xml.v_width = $(this).find('width').text();
        Ncl_xml.v_label = $(this).find('label').text();
        psHtmlTempLabel += '<td align="center"><b>' + Ncl_xml.v_label + '</b></td>';
    })
    Ncl_xml.htmlexport += psHtmlTempWidth + '<thead class="thead-inverse"><tr class="header">' + psHtmlTempLabel + '<tr></thead>';
    // groupcol
    var groupname = $(xml).find('level1groupid').text(), v_default1 = '', function_group = $(xml).find('function_group').text(), groupdisplay = '';
    if (groupname === '')
        groupname = $(xml).find('group_column').text();
    count_column = parseInt($(xml).find('col').length);
    colspan = count_column - 1;
    // lay cac phan tu trong bang
    for (var i = 0; i < iTotalRecord; i++) {
        Ncl_xml.objectId = arrResult[i][Ncl_xml.v_pk_column];
        //render rows
        $(xml).find('list_body').find('col').each(function () {
            Ncl_xml.v_width = $(this).find('width').text();
            Ncl_xml.v_label = $(this).find('label').text();
            v_type = $(this).find('type').text();
            v_type = v_type.toLowerCase();
            Ncl_xml.v_align = $(this).find('align').text();
            if (typeof (Ncl_xml.v_align) === 'undefined') Ncl_xml.v_align = '';
            Ncl_xml.xmlData = $(this).find('xml_data').text();

            if (typeof (Ncl_xml.xmlData) === 'undefined') Ncl_xml.xmlData = 'false';//Default get data in column name
            Ncl_xml.columnName = $(this).find('column_name').text();
            if (typeof (Ncl_xml.columnName) === 'undefined') Ncl_xml.columnName = '';
            Ncl_xml.alias_name = $(this).find('alias_name').text();
            if (typeof (Ncl_xml.alias_name) === 'undefined' || Ncl_xml.alias_name === '') Ncl_xml.alias_name = Ncl_xml.columnName;
            Ncl_xml.xmlTagInDb = $(this).find('xml_tag_in_db').text();
            if (typeof (Ncl_xml.xmlTagInDb) === 'undefined') Ncl_xml.xmlTagInDb = '';
            //Ncl_xml.v_value = arrResult[i][Ncl_xml.columnName];
            if (typeof (Ncl_xml.v_value) === 'undefined') Ncl_xml.v_value = '';
            //Xử lý kiểm tra lấy dữ liệu trong column or xml string
            if (Ncl_xml.xmlData == 'false') {
                Ncl_xml.v_value = arrResult[i][Ncl_xml.alias_name];
            } else {
                //get xml string in database
                if (Ncl_xml.xmlData == 'true') {
                    xmlString = arrResult[i][Ncl_xml.alias_name];
                    Ncl_xml.v_value = Ncl_xml._xmlGetXmlTagValue(xmlString, "data_list", Ncl_xml.xmlTagInDb);
                }
            }
            Ncl_xml.v_onclick = '';
            Ncl_xml.v_ondblclick = ($(this).find('event_url') ? $(this).find('event_url').text() : '');
            Ncl_xml.htmlexport += Ncl_xml._generateHtmlForColumn(v_type, i);
        })
        Ncl_xml.htmlexport += '</tr>';

    }
    $(objupdate).html(Ncl_xml.htmlexport);
}
/**
* @todo: Ham xuat ra chuoi column html
* @param obj: Doi tuong duoc chon
*/
TableXml.prototype._generateHtmlForColumn = function (v_type, i) {
    var htmloutput = '', Ncl_xml = this, cstar = 'unstar';
    v_type = v_type.toLowerCase();

    switch (v_type) {
        case "checkbox":
            Ncl_xml.v_onclick = 'select_checkbox_row(this);' + Ncl_xml.v_onclick;
            htmloutput += '<td align="' + this.v_align + '"><input type="checkbox" ondblclick="' + Ncl_xml.v_ondblclick + '" onclick="{' + Ncl_xml.v_onclick + '}" name="chk_item_id" value="' + this.v_value + '" />';
            htmloutput += '</td>';
            break;
        case "text_status":
            Ncl_xml.v_onclick = 'select_row(this);' + Ncl_xml.v_onclick;
            var value_status = 'Không hoạt động';
            if (this.v_value == 'HOAT_DONG') value_status = 'Hoạt động';
            htmloutput += '<td class="data" align="' + this.v_align + '" ondblclick="' + Ncl_xml.v_ondblclick + '" onclick="{' + Ncl_xml.v_onclick + '}">' + '' + value_status + '</td>';
            break;
        case "status":
            Ncl_xml.v_onclick = 'select_row(this);' + Ncl_xml.v_onclick;
            var value_status = 'Không hoạt động';
            if (this.v_value == 1 || this.v_value == "1") value_status = 'Hoạt động';
            htmloutput += '<td class="data" align="' + this.v_align + '" ondblclick="' + Ncl_xml.v_ondblclick + '" onclick="{' + Ncl_xml.v_onclick + '}">' + '' + value_status + '</td>';
            break;
        case "text":
            Ncl_xml.v_onclick = 'select_row(this);' + Ncl_xml.v_onclick;
            htmloutput += '<td class="data" align="' + this.v_align + '" ondblclick="' + Ncl_xml.v_ondblclick + '" onclick="{' + Ncl_xml.v_onclick + '}">' + '' + this.v_value + '</td>';
            break;
        //Kiểu date
        case "date":
            htmloutput += '<td class="data" align="' + this.v_align + '" ondblclick="' + Ncl_xml.v_ondblclick + '" onclick="select_row(this)">' + '' + Ncl_xml._yyyymmddToDDmmyyyy(this.v_value) + '</td>';
            break;
        case "identity":
            var identity = parseInt(i) + 1;
            Ncl_xml.v_onclick = 'select_row(this);' + Ncl_xml.v_onclick;
            htmloutput += '<td class="data" align="' + this.v_align + '" ondblclick="' + Ncl_xml.v_ondblclick + '" onclick="{' + Ncl_xml.v_onclick + '}">' + '' + identity + '</td>';
            break;
        case "inputtype":
            htmloutput += '<td class="data" align="' + this.v_align + '" "><button><i class="fa fa-pencil fa-fw"></i></button><button><i class="fa fa-trash-o fa-fw"></i></button></td>';
            break;
        default:
            htmloutput += this.v_value;
    }

    return htmloutput;
}

TableXml.prototype._xmlGetXmlTagValue = function (sXmlString, sXmlParentTag, sXmlTag) {
    var valueReturn = "";
    $(sXmlString).find(sXmlParentTag).each(function () {
        valueReturn = $(this).find(sXmlTag).text();
    });

    return valueReturn;
}

/**
* @todo: Ham xac dinh khi NSD chon 1 row tren man hinh danh sách
* @param obj: Doi tuong duoc chon
*/
function select_row(obj) {
    var oTable = $(obj).parent().parent().parent();
    $(oTable).find('td').parent().removeClass('selected');
    $(oTable).find('td').parent().find('input[name="chk_item_id"]').prop('checked', false);
    $(obj).parent().addClass('selected');
    var attDisabled = $(obj).parent().find('input[name="chk_item_id"]').prop('disabled');
    if (typeof (attDisabled) === 'undefined' || attDisabled == '') {
        $(obj).parent().find('input[name="chk_item_id"]').prop('checked', true);
        $(obj).parent().find('input[name="chk_item_id"]').prop('checked', 'checked');
    }
}

function select_checkbox_row(obj) {
    if (obj.checked) {
        $(obj).parent().parent().addClass('selected');
        $(obj).prop('checked', true);
        $(obj).prop('checked', 'checked');
    }
    else {
        $(obj).parent().parent().removeClass('selected');
        $(obj).prop('checked', false);
    }
}

//Ham checkbox all
function checkbox_all_item_id(p_chk_obj) {
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var v_count = p_chk_obj.length;
    //remove class cua tat ca cac tr trong table
    if ($('[name="chk_all_item_id"]').is(':checked')) {
        $(p_chk_obj).each(function () {
            $(this).prop('checked', true);
            $(this).parent().parent().addClass('selected');
        });
    } else {
        $(p_chk_obj).each(function () {
            $(this).prop('checked', false);
            $(this).parent().parent().removeClass('selected');
        });
    }
}