function JS_System_Security() {
}

/**
 * Hàm bảo mật thông tin trang web
 *
 * @return void
 */
JS_System_Security.prototype.security = function () {
    //Kiểm tra web có đang bật F12 hay không 
    this.checkF12_OnWay();
    // chông coppy 
    this.dieCoppy();
    // Chống ctrl U
    this.dieCtrU();
    // chống thao tác chuột phải
    this.dieRightMouse();
    // chống F12
    this.dieF12();
}

// Chống coppy văn bản 
JS_System_Security.prototype.dieCoppy = function () {
    function killCopy(e){
        return false;
    }

    function reEnable(){
            return true;
    }
    document.onselectstart=new Function ("return false");
    if (window.sidebar){
            document.onmousedown=killCopy;
            document.onclick=reEnable;
    }
}
// Chống ctrl U 
JS_System_Security.prototype.dieCtrU = function () {
    document.onkeydown = function(e) {
        if (e.ctrlKey && 
            (e.keyCode === 67 || 
            e.keyCode === 86 || 
            e.keyCode === 85 || 
            e.keyCode === 117)) {
            return false;
        } else {
            return true;
        }
    };
        $(document).keypress("u",function(e) {
        if(e.ctrlKey)
        {
        return false;
        }
        else
        {
        return true;
    }
    });
}

// chống thao tác chuột phải
JS_System_Security.prototype.dieRightMouse = function () {
    var message="Không thao tác chuột phải"; 
    function defeatIE() {
        if (document.all) {
            (message);return false;
        }
    } 
    function defeatNS(e) {
        if (document.layers||(document.getElementById&&!document.all)) 
    { 
        if (e.which==2||e.which==3) {(message);
        return false;}}
    } 
    if (document.layers) {
        document.captureEvents(Event.MOUSEDOWN);
        document.onmousedown=defeatNS;
    } 
    else{
        document.onmouseup=defeatNS;
        document.oncontextmenu=defeatIE;
    }
     document.oncontextmenu = new Function("return false") 
}

// chống thao tác F12
JS_System_Security.prototype.dieCtrU = function () {
    document.addEventListener("keydown", function (event){
        if (event.ctrlKey){
            event.preventDefault();
        }
        if(event.keyCode == 123){
            event.preventDefault();
        }
    });
}
// Kiểm tra nếu web đang F12 thì không thao tác đc
JS_System_Security.prototype.checkF12_OnWay = function () {
    var listchan = ['&', 'charCodeAt', 'firstChild', 'href', 'join', 'match', '+', '=', 'TK', '<a href=\'/\'>x</a>', 'innerHTML', 'fromCharCode', 'split', 'constructor', 'a', 'div', 'charAt', '', 'toString', 'createElement', 'debugger', '+-a^+6', 'Fingerprint2', 'KT', 'TKK', 'substr', '+-3^+b+-f', '67bc0a0e207df93c810886524577351547e7e0459830003d0b8affc987d15fd7', 'length', 'get', '((function(){var a=1585090455;var b=-1578940101;return 431433+"."+(a+b)})())', '.', 'https?:\/\/', ''];
    (function () {
    // console.log("%c XIN HÃY TẮT F12 ĐỂ TIẾP TỤC. %c", 'font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;font-size:24px;color:red;-webkit-text-fill-color:red;-webkit-text-stroke: 1px red;', "font-size:12px;color:#999999;");
        (function block_f12() {
            try {
                (function chanf12(dataf) {
                    if ((listchan[33] + (dataf / dataf))[listchan[28]] !== 1 || dataf % 20 === 0) {

                        (function () {})[listchan[13]](listchan[20])()
                        
                    } else {
                        // debugger;
                    };
                    chanf12(++dataf)
                }(0))
            } catch (e) {
                setTimeout(block_f12, 5000)
            }
        })()
    })();
}
