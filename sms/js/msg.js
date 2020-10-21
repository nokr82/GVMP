isNewSubject = true; // 타이틀 자동 생성 여부.

/**
 * 예약일시를 현재시간으로 맞추는 처리.
 * body.onload 시점에 동작함.
 */
function setDate() {
    var date = new Date();
    document.MsgForm.R_YEAR.value = date.getFullYear();
    document.MsgForm.R_MONTH.value = fillString(date.getMonth()+1, 2, '0');
    document.MsgForm.R_DAY.value = fillString(date.getDate(), 2, '0');
    document.MsgForm.R_HOUR.value = fillString(date.getHours(), 2, '0');
    document.MsgForm.R_MIN.value = fillString(date.getMinutes(), 2, '0');
}

/**
 * 문자열을 원하는 길이만큼 특정 문자로 채워주는 기능
 * 채워질 문자는 앞자리부터 적용된다.
 *
 * @param     str           채우기위한 문자열
 *            n             원하는 문자열을 전체 길이
 *            chr           채우려하는 문자
 * @return    string        변형된 문자열
 */
function fillString(str, n, chr){
    var i, c = '';
    str += ""; // 오류 방지를 위해 문자열로 변환
    if(chr==undefined) chr = ' ';
    if(n < 0){
        for(i=str.length;i<-n;i++) c += chr;
        return str + c;
    }else{
        for(i=str.length;i<n;i++) c += chr;
        return c + str;
    }
}


/**
 * 넘어온 번호가 주어진 글자로만 이루어 졌는지 확인합니다.
 * 정상적인 것은 mode 변수로 분류하는데 '0'인 경우 숫자만,
 * '1'인 경우는 ',' 기호를 포함해서 확인합니다.
 *
 * @param    contents    숫자만있는지 확인하려 하는 값
 *            moce        검색형태
 * @return    -true-        정상적으로 이루어진 값 일때
 *            -false-        숫자만인 아닌 비정상적인 값인 경우
 */
function isInteger(contents, mode) {
    var isNum = true;

    if (contents == "")
        return false;

    for (j=0; (j<contents.length); j++) {
        if (mode == 0){
            if ((contents.charAt(j) < "0")||(contents.charAt(j) > "9")) {
                isNum = false;
            }
        } else {
            if ((contents.charAt(j) < '0' || contents.charAt(j) > '9') && contents.charAt(j) != ',') {
                isNum = false;
            }
        }
    }
    return isNum;
}

/**
 * 기호 및 이모티콘을 입력합니다.
 * 
 * @param    str        입력할 기호 및 이모티콘
 */ 
function add(str) {
    document.MsgForm.strData.focus();
    document.MsgForm.strData.value+=str; 
    ChkLen();
    return;
}


/**
 * 글자수 체크
 * 액정화면이 변경될때(글을 쓰거나, 지울때 등) 글자수를 계산해서 보여줍니다.
 * 문자가 90바이트가 넘어가면 경고 문구를 띄워 지우도록 유도합니다.
 */
function ChkLen() {
    var pos, pch, ch;
    var msglen = 0;

    var len = MsgForm.strData.value.length;
    for (i=0;i<len;i++){
        pos = MsgForm.strData.value.charAt(i);
        if (pos == ".") isNewTitle = false;

        // 자리수 계산.
        ch = escape(pos);
        if (pch == '%0D' && ch == '%0A') { } // 2byte enter (\n\r)
        else if (ch.length > 4) msglen += 2;
        else msglen++;
        pch = ch;
    }
    
    if(isNewSubject && msglen <= 30) MsgForm.strSubject.value = MsgForm.strData.value;

    MsgForm.strDataCount.value = msglen;
    
    if (msglen > 90) {
        document.getElementById("maxLength").innerHTML = "1500";
        document.getElementById("divLmsTitle").style.display = "";
        document.getElementById("msgType").innerHTML = "LMS";
        if (msglen > 1500) alert('문자메시지는 1500바이트를 넘을 수 없습니다.');
    } else {
        document.getElementById("msgType").innerHTML = "SMS";
        document.getElementById("maxLength").innerHTML = "90";
        document.getElementById("divLmsTitle").style.display = 'none';
    }
}

function chkTitle() {
    isNewSubject = false;
}

/**
 * 발송번호 추가
 * 발송번호를 추가합니다. 추가를 하기위해 addCallNum 박스에 전화번호를 입력하고
 * 추가버튼을 눌러 추가를 하게 됩니다. 여러사람을 넣고 싶을때는 ',' 기호를
 * 이용하여 여러명 추가도 가능합니다. 다만 500명을 넘기지 못하도록 규정해놓
 * 았습니다.
 */ 
function addItem() {
    if (MsgForm.addCallNum.value == "" ) {
        alert("받는분 번호를 정확히 입력하시고 추가해주세요.");
        MsgForm.addCallNum.value = "";
        MsgForm.addCallNum.focus();
        return;
    } 

    if (!isInteger(MsgForm.addCallNum.value, 1)) {
        window.alert('받는사람은 숫자와 (,)만 입력하실 수 있습니다.');            
        MsgForm.addCallNum.value = "";
        MsgForm.addCallNum.focus();
        return;
    }

    var i;
    rcvPhnId    = MsgForm.addCallNum.value;
    rcvPhnIdArr    = rcvPhnId.split(',');
    rcvPhnIdLen    = rcvPhnIdArr.length;

    if (rcvPhnIdLen > 500) {
        window.alert('500명 이상은 구분자 전송을 이용하실수 없습니다.');
        MsgForm.addCallNum.value = "";
        MsgForm.addCallNum.focus();
        return;
    }else{
        for (i=0 ; i < rcvPhnIdLen; i++) {
            addSelectBox(" ", rcvPhnIdArr[i]);
            MsgForm.addCallNum.value = "";
        }
    }
}

/**
 * 발송번호 삭제
 * 발송번호를 발송목록에서 삭제합니다. 삭제시에는 발송목록에서 원하는 항목을
 * 선택한 상태여야 합니다.
 */ 
function delItem() {
    var i,j;
    var Cnt;
    var recvVList = new Array();
    var recvTList = new Array();
        
    recvList = MsgForm.strDest;

    for (i = 1, Cnt = 0; i < recvList.options.length; i++) {
        if (recvList.options[i].selected == false) {
            recvVList[Cnt]        = recvList.options[i].value;
            recvTList[Cnt++]    = recvList.options[i].text;
        }
    }

    recvList.options.length = 1;
    for (i = 0; i < Cnt; i++) {
        index = recvList.options.length;
        recvList.options.length            = index+1;
        recvList.options[index].value    = recvVList[i];
        recvList.options[index].text    = recvTList[i];
    }
        
    delete(recvVList);
    delete(recvTList);
}

/**
 * 발송번호 박스에 추가
 * 값을 넘겨받아 박스에 추가합니다.
 * 
 * @param    name       발송자명
 *            tel       발송번호
 */ 
function addSelectBox(name, tel) {

    index = MsgForm.strDest.options.length;

    if (name.length > 8) name = name.substr(0,8);
    if (index == 0)    index = index+1;

    phoneRe = /(01[016789])-?([0-9]{3,4})-?([0-9]{4})/;
    phoneRe.exec(tel)

    if(phoneRe.exec(tel)){
        
        tel = RegExp.$1 + RegExp.$2 + RegExp.$3;

        for (i=0; i < index ; i++) {
            if (MsgForm.strDest.options[i].text == tel) {
                alert(name+"님은 이미 수신자에 포함되어 있습니다.");
                return;
            }
        }

        MsgForm.strDest.options.length = index+1;
        MsgForm.strDest.options[index].value = tel +";";
        MsgForm.strDest.options[index].text = tel;
        MsgForm.strDest.options.length = index+1;
    } 
}   

/**
 * 즉시전송과 예약전송 상태에 따라 폼 안에 포함된 예약날짜입력 부분의 사용여부를 결정합니다.
 *
 * @param    form    적용될 폼입니다.
 */
function CWCheck(form) {
    
    dest = document.all["sDest"]
    if (document.MsgForm.chkSendFlag[0].checked) {    // 즉시전송을 선택했을 경우
        
        sDest.style.display = "none";
        document.MsgForm.R_YEAR.disabled = true;
        document.MsgForm.R_MONTH.disabled = true;
        document.MsgForm.R_DAY.disabled = true;
        document.MsgForm.R_HOUR.disabled = true;
        document.MsgForm.R_MIN.disabled = true;
    }
    else                                            // 예약전송을 선택했을 경우
    {
        sDest.style.display = "block";    
        document.MsgForm.R_YEAR.disabled = false;
        document.MsgForm.R_MONTH.disabled = false;
        document.MsgForm.R_DAY.disabled = false;
        document.MsgForm.R_HOUR.disabled = false;
        document.MsgForm.R_MIN.disabled = false;
        document.MsgForm.R_YEAR.focus();
    }
    document.MsgForm.strTelList.value = "";
}

/**
 * 문자를 발송하기 위하여 총괄 점검을 합니다.
 *
 * return    -false-    점검이 실패로 돌아가면 보내기가 무효화 됩니다.
 */
function varcheck() {

    // 일괄 문구 치환
    MsgForm.strData.value = MsgForm.strData.value.replace(/\u200b/g, '');

    var varStrData = document.MsgForm.strData.value;
    var varStrSubject = document.MsgForm.strSubject.value;
    var varStrCallBack = document.MsgForm.strCallBack.value;

    // 메시지가 입력되어 있는지 확인합니다.
    if (varStrData == ""){
        alert("메시지를 입력해 주세요");
        document.MsgForm.strData.focus();
        return false;
    } else if (varStrData.length > 1500) {
        alert("문자는 1500byte를 넘을 수 없습니다.");
        document.MsgForm.strData.focus();
        return false;
    }

    // LMS 제목의 상태를 확인하고 변경합니다.
    if (document.getElementById("divLmsTitle").style.display != "none") {
        if(varStrSubject.length == 0) {
            document.MsgForm.strSubjectrData.value = "[제목없음]";
        } else if (varStrSubject.length > 30) {
            alert("LMS 제목은 30byte를 넘을 수 없습니다.");
            document.MsgForm.strSubjectrData.focus();
            return false;
        }
    }

    // 받는 사람의 전화번호가 들어가 있는지 확인합니다.
    if (document.MsgForm.strDest.length < 2){
        alert("수신자 번호를 입력해 주세요");
        document.MsgForm.addCallNum.focus();
        return false;
    }

    // 보내는 사람의 전화번호가 들어가 있는지 확인합니다.
    if (varStrCallBack  == null || varStrCallBack == ""){
        alert("회신번호를 입력하세요");
        document.MsgForm.strCallBack.focus();
        return false;
    }

    // 보내는 사람의 전화번호가 숫자로만 이루어 졌는지 확인합니다.
    if (!isInteger(varStrCallBack, 0)){
        alert('회신번호는 숫자만 가능합니다.');
        document.MsgForm.strCallBack.focus();
        return false;
    }

    // 예약 년도가 정확한지 확인합니다.
    if (document.MsgForm.chkSendFlag[1].checked && document.MsgForm.R_YEAR.value.length!=4){
        alert("년도가 정확하지 않습니다.");
        document.MsgForm.R_YEAR.focus();
        return false;
    }

    // 예약 월이 정확한지 확인합니다.
    if (document.MsgForm.chkSendFlag[1].checked && document.MsgForm.R_MONTH.value.length!=2){
        alert("월이 정확하지 않습니다.");
        document.MsgForm.R_MONTH.focus();
        return false;
    }

    // 에약 일이 정확한지 확인합니다.
    if (document.MsgForm.chkSendFlag[1].checked && document.MsgForm.R_DAY.value.length!=2){
        alert("일이 정확하지 않습니다.");
        document.MsgForm.R_DAY.focus();
        return false;
    }

    document.MsgForm.strTelList.value = "";
    for (k=1; k < document.MsgForm.strDest.length; k++) { 
        document.MsgForm.strTelList.value += document.MsgForm.strDest.options[k].value;
    }
}

/**
 * 폼 초기화 함수.
 */
function reset() {
    document.MsgForm.reset();
}

