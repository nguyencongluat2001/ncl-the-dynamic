
function select_row(obj){
    var oTable = $(obj).parent().parent().parent();
    $(oTable).find('td').parent().removeClass('selected');
    $(oTable).find('td').parent().find('input[name="chk_item_id"]').prop('checked',false);
    $(obj).parent().addClass('selected');
    var attDisabled = $(obj).parent().find('input[name="chk_item_id"]').prop('disabled');
    if(typeof(attDisabled) === 'undefined' || attDisabled == ''){
        $(obj).parent().find('input[name="chk_item_id"]').prop('checked',true);
        $(obj).parent().find('input[name="chk_item_id"]').prop('checked','checked');
    }
}
function select_checkbox_row(obj){
    if (obj.checked) {
        $(obj).parent().parent().addClass('selected');
        $(obj).prop('checked',true);
        $(obj).prop('checked','checked');
    }
    else{
        $(obj).parent().parent().removeClass('selected');
        $(obj).prop('checked',false);
    }
}
//Ham checkbox all
function checkbox_all_item_id(p_chk_obj){
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var v_count = p_chk_obj.length;
    //remove class cua tat ca cac tr trong table
    if ($('[name="chk_all_item_id"]').is(':checked')) {
        $(p_chk_obj).each(function() {
          $(this).prop('checked',true);
           $(this).parent().parent().addClass('selected');
        });
    }else{
        $(p_chk_obj).each(function() {
          $(this).prop('checked',false);
           $(this).parent().parent().removeClass('selected');
        });   
    }
}