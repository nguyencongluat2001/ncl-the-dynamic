function NclLib(options = null) {

}
// loadding image index
NclLib.prototype.successLoadding = function () {
  $('#imageLoading').even().removeClass( "loader_bg_of" );
    setTimeout(() => {
       $('#imageLoading').addClass("loader_bg_of");
    }, 100)
}
// loadding image index
NclLib.prototype.loadding = function () {
  $('#imageLoading').removeClass( "loader_bg_of" );
  setTimeout(() => {
      $('#imageLoading').addClass("loader_bg_of");
  }, 500)
}
// alerMesage thông báo sau khi có sự kiện
NclLib.prototype.alerMesage = function(nameMessage,icon,color){
  Swal.fire({
      position: 'top-start',
      icon: icon,
      title: nameMessage,
      color: color,
      showConfirmButton: false,
      // width:'30%',
      timer: 2500
    })
}
// alerMesage thông báo sau khi có sự kiện
NclLib.prototype.alerMesageClient = function(nameMessage,icon,color,background){
  Swal.fire({
      position: 'top-start',
      icon: icon,
      title: nameMessage,
      color: color,
      background:background,
      showConfirmButton: false,
      // width:'30%',
      timer: 2500
    })
}
NclLib.prototype.alertMessageBackend = function(type, label, message, s = 30000) {
    $.toast({
        title: label,
        content: message,
        type: type,
        delay: s,
        dismissible: true,
        positionDefaults: 'top-left'
    });
}
// add class hiển thị đang ở page nào 
NclLib.prototype.active = function(nameMenu){
  $(nameMenu).addClass("active");
}
// add class hiển thị đang ở page nào 
NclLib.prototype.menuActive = function(nameMenu){
  $(nameMenu).addClass("active-menuClient");
}
// add class hiển thị đang ở page nào 
NclLib.prototype.menuActive_child = function(nameMenu_child){
  $(nameMenu_child).addClass("active-menuClient_background");
}

NclLib = new NclLib();

function checkallper(obj, name) {
  if (obj.checked) {
    $('input[name="' + name + '"]').prop('checked', true);
  } else {
    $('input[name="' + name + '"]').prop('checked', false);
  }
}
// $(window).scroll(function() {
//   if ($(this).scrollTop() > 20) {
//       $('#goTop').fadeIn();
//   } else {
//       $('#goTop').fadeOut();
//   }
// });
function gotop(){
  $("html, body").animate({scrollTop: 0}, 100);
}
var set_checked_checbox = function (obj) {
  var sValue = '';
  var oTable = $(obj).parent().parent().parent();
  // Duyet update
  oTable.find('.checkvaluemark:checked').each(function () {
    sValue += $(this).val() + ',';
  })
  var id = $(obj).attr('id-value')
  sValue = sValue.substr(0, sValue.length - 1);
  $("#" + id).val(sValue);
}