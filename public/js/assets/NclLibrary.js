function NclLib(options = null) { }

// NclLib.prototype.loadding = function () {
//     var loadding = new Nanobar();
//     return loadding;
// }
NclLib.prototype.loadding = function () {
    $('#imageLoading').removeClass( "loader_bg_of" );
    setTimeout(() => {
        $('#imageLoading').addClass("loader_bg_of");
    }, 500)
  }
NclLib.prototype.showmainloadding = function () {
    $("#loading").show();
}

NclLib.prototype.successLoadImage = function () {
    // $("#loadding").hide();
    $("#loading").hide();
    $(".search").button('reset');
}

// Load Modul tu sidebar
NclLib.prototype.loadModul = function (url, Module, SubModul = '') {
    data = '';
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $("#main-content").html(arrResult);
            $("li").removeClass('active');
            $("#" + Module).addClass("active");
        }
    });
}

// alerMesage thông báo sau khi có sự kiện
NclLib.prototype.swalAlert = function (nameMessage, icon, color, background) {
    Swal.fire({
        position: 'top-start',
        icon: icon,
        title: nameMessage,
        color: color,
        background: background,
        showConfirmButton: false,
        timer: 2000
    })
}

/**
 * Hiển thị alert thông báo
 * 
 * @param {*} type 
 * @param {*} label 
 * @param {*} message 
 * @param {*} s 
 */
NclLib.prototype.alertMessage = function (type, label, message, s = 2000) {
    var vclass = 'alert';
    lclass = 'fas ';
    if (type == 'success') {
        vclass += ' alert-success';
        lclass += 'fa-check-circle';
    } else if (type == 'info') {
        vclass += ' alert-info';
        lclass += 'fa-info-circle';
    } else if (type == 'warning') {
        vclass += ' alert-warning';
        lclass += 'fa-exclamation-triangle';
    } else if (type == 'danger') {
        vclass += ' alert-danger';
        lclass += 'fa-skull-crossbones';
    }
    $("#message-alert").alert();
    $("#message-alert").removeClass();
    $("#message-alert").addClass(vclass);
    $("#message-icon").removeClass();
    $("#message-icon").addClass(lclass);
    $("#message-label").html(label);
    $("#message-infor").html(message);
    $("#message-alert").fadeTo(s, 500).slideUp(500, function () {
        $("#message-alert").slideUp(500);
    })
}

// Hien thi loadding
NclLib.prototype.showLoadList = function () { }

NclLib.prototype.showLoadSearch = function () {
    $('.search').button('loading');
}

NclLib.prototype.showLoadMain = function () {
    $(".main_loadding").show();
}

NclLib.prototype.successLoad = function () {
    simplebar.go(100);
}

NclLib.prototype.cbDateRange = function (start, end) {
    $('input[name="daterange"]').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}

NclLib.prototype.createDateRange = function (obj) {
    var currentTime = new Date();
    var lastyear = currentTime.getFullYear() - 1;
    var start = moment().startOf('month');
    var end = moment().endOf('month');
    obj.daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Hôm nay': [moment(), moment()],
            'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Tháng này': [moment().startOf('month'), moment().endOf('month')],
            'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Năm này': [moment().startOf('year'), moment().endOf('year')],
            // 'Năm trước': [moment([lastyear, 00, 01]), moment([lastyear, 11, 31])]
        }, locale: {
            format: 'DD/MM/YYYY',
            customRangeLabel: "Tùy chọn",
            "daysOfWeek": [
                "CN",
                "T2",
                "T3",
                "T4",
                "T5",
                "T6",
                "T7"
            ],
            "monthNames": [
                "Tháng một",
                "Tháng hai",
                "Tháng ba",
                "Tháng bốn",
                "Tháng năm",
                "Tháng sáu",
                "Tháng bẩy",
                "Tháng tám",
                "Tháng chín",
                "Tháng mười",
                "Tháng mười một",
                "Tháng mười hai"
            ],
            "firstDay": 1,
            "applyLabel": "Lưu lại",
            "cancelLabel": "Hủy",
        }, "alwaysShowCalendars": true
    }, this.cbDateRange);

    this.cbDateRange(start, end);
}

NclLib.prototype.loadFileJsCss = function (arrUrl) {
    // Load file Js va Css
    var count = arrUrl.length;
    for (var i = 0; i < count; i++) {
        if (arrUrl[i]['type'] === 'js') {
            $('head').append('<script src="' + arrUrl[i]['src'] + '" type="text/javascript" charset="utf-8"></script>');
        } else {
            $('head').append('<link rel="stylesheet" type="text/css" href="' + arrUrl[i]['src'] + '">');
        }
    };
}

NclLib.prototype.changepassword = function (v_this) {
    var myClass = this;
    var url = v_this.attr('url');
    data = [];
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
        }
    });
}

NclLib.prototype.daterangepicker = function (obj) {
    var currentTime = new Date();
    var lastyear = currentTime.getFullYear() - 1;
    var start = moment().startOf('year');
    var end = moment().endOf('year');
    obj.daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Hôm nay': [moment(), moment()],
            'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Tháng này': [moment().startOf('month'), moment().endOf('month')],
            'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Năm này': [moment().startOf('year'), moment().endOf('year')],
            // 'Năm trước': [moment([lastyear, 00, 01]), moment([lastyear, 11, 31])]
        }, locale: {
            format: 'DD/MM/YYYY',
            customRangeLabel: "Tùy chọn",
            "daysOfWeek": [
                "CN",
                "T2",
                "T3",
                "T4",
                "T5",
                "T6",
                "T7"
            ],
            "monthNames": [
                "Tháng một",
                "Tháng hai",
                "Tháng ba",
                "Tháng bốn",
                "Tháng năm",
                "Tháng sáu",
                "Tháng bẩy",
                "Tháng tám",
                "Tháng chín",
                "Tháng mười",
                "Tháng mười một",
                "Tháng mười hai"
            ],
            "firstDay": 1,
            "applyLabel": "Lưu lại",
            "cancelLabel": "Hủy",
        }, "alwaysShowCalendars": true
    }, cb);

    cb(start, end);
}

NclLib.prototype._save_xml_tag_and_value_list = function (oForm) {
    var myclass = this;
    var xml_tag_in_db = value = type = '';
    var stringxml = '<root><data_list>';
    $(oForm).find('[xml_data="true"]').each(function () {
        xml_tag_in_db = $(this).attr("xml_tag_in_db");
        type = $(this).attr("type");
        if (xml_tag_in_db !== '') {
            value = myclass._get_value_xml_by_type(type, $(this))
            if (value !== '') {
                stringxml += '<' + xml_tag_in_db + '>' + value + '</' + xml_tag_in_db + '>';
            } else {
                stringxml += '<' + xml_tag_in_db + '></' + xml_tag_in_db + '>';
            }
        }
    });
    stringxml += '</data_list></root>';
    return stringxml;
}

NclLib.prototype._get_value_xml_by_type = function (type, obj) {
    var value = '';
    if (type == 'checkbox') {
        if (obj.is(':checked')) {
            value = 'HOAT_DONG';
        }
    } else {
        value = obj.val();
    }
    return value;
}

NclLib = new NclLib();

function cb(start, end) {
    $('input[name="daterange"]').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}

shortcut = {
    'all_shortcuts': {},//All the shortcuts are stored in this array
    'add': function (shortcut_combination, callback, opt) {
        //Provide a set of default options
        var default_options = {
            'type': 'keydown',
            'propagate': false,
            'disable_in_input': false,
            'target': document,
            'keycode': false
        }
        if (!opt) opt = default_options;
        else {
            for (var dfo in default_options) {
                if (typeof opt[dfo] == 'undefined') opt[dfo] = default_options[dfo];
            }
        }

        var ele = opt.target;
        if (typeof opt.target == 'string') ele = document.getElementById(opt.target);
        var ths = this;
        shortcut_combination = shortcut_combination.toLowerCase();

        //The function to be called at keypress
        var func = function (e) {
            e = e || window.event;

            if (opt['disable_in_input']) { //Don't enable shortcut keys in Input, Textarea fields
                var element;
                if (e.target) element = e.target;
                else if (e.srcElement) element = e.srcElement;
                if (element.nodeType == 3) element = element.parentNode;

                if (element.tagName == 'INPUT' || element.tagName == 'TEXTAREA') return;
            }

            //Find Which key is pressed
            if (e.keyCode) code = e.keyCode;
            else if (e.which) code = e.which;
            var character = String.fromCharCode(code).toLowerCase();

            if (code == 188) character = ","; //If the user presses , when the type is onkeydown
            if (code == 190) character = "."; //If the user presses , when the type is onkeydown

            var keys = shortcut_combination.split("+");
            //Key Pressed - counts the number of valid keypresses - if it is same as the number of keys, the shortcut function is invoked
            var kp = 0;

            //Work around for stupid Shift key bug created by using lowercase - as a result the shift+num combination was broken
            var shift_nums = {
                "`": "~",
                "1": "!",
                "2": "@",
                "3": "#",
                "4": "$",
                "5": "%",
                "6": "^",
                "7": "&",
                "8": "*",
                "9": "(",
                "0": ")",
                "-": "_",
                "=": "+",
                ";": ":",
                "'": "\"",
                ",": "<",
                ".": ">",
                "/": "?",
                "\\": "|"
            }
            //Special Keys - and their codes
            var special_keys = {
                'esc': 27,
                'escape': 27,
                'tab': 9,
                'space': 32,
                'return': 13,
                'enter': 13,
                'backspace': 8,

                'scrolllock': 145,
                'scroll_lock': 145,
                'scroll': 145,
                'capslock': 20,
                'caps_lock': 20,
                'caps': 20,
                'numlock': 144,
                'num_lock': 144,
                'num': 144,

                'pause': 19,
                'break': 19,

                'insert': 45,
                'home': 36,
                'delete': 46,
                'end': 35,

                'pageup': 33,
                'page_up': 33,
                'pu': 33,

                'pagedown': 34,
                'page_down': 34,
                'pd': 34,

                'left': 37,
                'up': 38,
                'right': 39,
                'down': 40,

                'f1': 112,
                'f2': 113,
                'f3': 114,
                'f4': 115,
                'f5': 116,
                'f6': 117,
                'f7': 118,
                'f8': 119,
                'f9': 120,
                'f10': 121,
                'f11': 122,
                'f12': 123
            }

            var modifiers = {
                shift: { wanted: false, pressed: false },
                ctrl: { wanted: false, pressed: false },
                alt: { wanted: false, pressed: false },
                meta: { wanted: false, pressed: false } //Meta is Mac specific
            };

            if (e.ctrlKey) modifiers.ctrl.pressed = true;
            if (e.shiftKey) modifiers.shift.pressed = true;
            if (e.altKey) modifiers.alt.pressed = true;
            if (e.metaKey) modifiers.meta.pressed = true;

            for (var i = 0; k = keys[i], i < keys.length; i++) {
                //Modifiers
                if (k == 'ctrl' || k == 'control') {
                    kp++;
                    modifiers.ctrl.wanted = true;

                } else if (k == 'shift') {
                    kp++;
                    modifiers.shift.wanted = true;

                } else if (k == 'alt') {
                    kp++;
                    modifiers.alt.wanted = true;
                } else if (k == 'meta') {
                    kp++;
                    modifiers.meta.wanted = true;
                } else if (k.length > 1) { //If it is a special key
                    if (special_keys[k] == code) kp++;

                } else if (opt['keycode']) {
                    if (opt['keycode'] == code) kp++;

                } else { //The special keys did not match
                    if (character == k) kp++;
                    else {
                        if (shift_nums[character] && e.shiftKey) { //Stupid Shift key bug created by using lowercase
                            character = shift_nums[character];
                            if (character == k) kp++;
                        }
                    }
                }
            }

            if (kp == keys.length &&
                modifiers.ctrl.pressed == modifiers.ctrl.wanted &&
                modifiers.shift.pressed == modifiers.shift.wanted &&
                modifiers.alt.pressed == modifiers.alt.wanted &&
                modifiers.meta.pressed == modifiers.meta.wanted) {
                callback(e);

                if (!opt['propagate']) { //Stop the event
                    //e.cancelBubble is supported by IE - this will kill the bubbling process.
                    e.cancelBubble = true;
                    e.returnValue = false;

                    //e.stopPropagation works in Firefox.
                    if (e.stopPropagation) {
                        e.stopPropagation();
                        e.preventDefault();
                    }
                    return false;
                }
            }
        }
        this.all_shortcuts[shortcut_combination] = {
            'callback': func,
            'target': ele,
            'event': opt['type']
        };
        //Attach the function with the event
        if (ele.addEventListener) ele.addEventListener(opt['type'], func, false);
        else if (ele.attachEvent) ele.attachEvent('on' + opt['type'], func);
        else ele['on' + opt['type']] = func;
    },

    //Remove the shortcut - just specify the shortcut and I will remove the binding
    'remove': function (shortcut_combination) {
        shortcut_combination = shortcut_combination.toLowerCase();
        var binding = this.all_shortcuts[shortcut_combination];
        delete (this.all_shortcuts[shortcut_combination])
        if (!binding) return;
        var type = binding['event'];
        var ele = binding['target'];
        var callback = binding['callback'];

        if (ele.detachEvent) ele.detachEvent('on' + type, callback);
        else if (ele.removeEventListener) ele.removeEventListener(type, callback, false);
        else ele['on' + type] = false;
    }
}

$('#id-search-record').click(function () {
    alert('okoko');
});

$('html').bind('keypress', function (e) {
    if (e.keyCode == 13) {
        var oForm = 'form#frm-search-form';
        var strrsearch = $(oForm).find("#input-search-form").val();
        if (strrsearch) {
            search_record();
            return false;
        }
    }
});

function checkallper(obj, name) {
    if (obj.checked) {
        $('input[name="' + name + '"]').prop('checked', true);
    } else {
        $('input[name="' + name + '"]').prop('checked', false);
    }
}

function search_record() {
    var oForm = 'form#frm-search-form';
    var strrsearch = $(oForm).find("#input-search-form").val();
    if (strrsearch) {
        NclLib.showmainloadding();
        var url = '../admin/main/search_record';
        var data = $(oForm).serialize();
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (arrResult) {
                NclLib.successLoadImage();
                $('#search_infor_record').html(arrResult);
                $('#search_infor_record').modal('show');
            },
            error: function (arrResult) {
                NclLib.successLoadImage();
                NclLib.alertMessage('danger', "Không tìm thấy kết quả, vui lòng xem lại!");
            }
        });
    } else {
        NclLib.alertMessage('danger', "Bạn chưa nhập từ khóa cần tìm");
        return false;
    }
}

function set_checked_multi(obj) {
    var oTable = $(obj).parent().parent().parent();
    var oCheckbox = $(obj).prev().find('input.checkvaluemark');
    if ($(oCheckbox).is(':checked')) {
        $(oCheckbox).attr('checked', false);
    } else {
        $(oCheckbox).attr('checked', true);
    }
    var id = $(oCheckbox).attr('id-value'), sValue = '';
    // Duyet update
    oTable.find('.checkvaluemark:checked').each(function () {
        sValue += $(this).val() + ',';
    })
    sValue = sValue.substr(0, sValue.length - 1);
    $("#" + id).val(sValue);
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

$(document).ready(function () {
    // Toggle the side navigation
    $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        };
    });

    // Close any open menu accordions when window is resized below 768px
    $(window).resize(function () {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        };

        // Toggle the side navigation when window is resized below 480px
        if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
            $("body").addClass("sidebar-toggled");
            $(".sidebar").addClass("toggled");
            $('.sidebar .collapse').collapse('hide');
        };
    });

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent,
                delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });

    // Scroll to top button appear
    $(document).on('scroll', function () {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // Smooth scrolling using jQuery easing
    $(document).on('click', 'a.scroll-to-top', function (e) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });
    // change pas
    $("#change-password").click(function () {
        NclLib.changepassword($(this));
    });
});

/**
 * Kiểm tra xem chuẩn định dạng JSON chưa
 * 
 * @param {*} str 
 * @returns 
 * @author khuongtq
 */
function isJSON(str) {
    try {
        JSON.parse(str);
        return true;
    } catch (error) {
        return false;
    }
}