<?php
/******************************************************************************
 *
 *	SYSTEM NAME			: 결제요청페이지
 *	PROGRAM NAME		: mainMobilePay.php
 *	MAKER				: sajang
 *	MAKE DATE			: 2017.12.27
 *	PROGRAM CONTENTS	: 결제요청페이지
 *
 ************************** 변 경 이 력 *****************************************
 * 번호	작업자		작업일				변경내용
 *	1	스마트로	2017.12.27		결제요청페이지
 *	2	황예은  	2019.10.21		cardInterest, moid 생성규칙 제거
 *	3	황예은  	2019.10.24		Kakao, Toss 추가
 *	4	황예은  	2019.11.28		EscrowCD 추가
 *******************************************************************************/
?>
<?php
$server_ip = $_SERVER['SERVER_ADDR'];	// 서버 IP 가져오기
$VbankExpDate = date("Ymd", strtotime($day."1 day"));
$ediDate = date("YmdHis"); // 전문생성일시
$Moid = date("YmdHis");

// 상점서명키 (꼭 해당 상점키로 바꿔주세요)
$merchantKey = "cixvpJN9KDaqc/6F8yYy1+bk+jiLSJVs2lw3IqAOrFbDIDHSWrWRCWhew0Gt0S3xZDvqvn37dgQvOf+nzh0mgQ==";
$MID = "m2oway015m";		// MID
$goodsAmt = "1004";		// 상품금액

$encryptData = base64_encode(md5($ediDate.$MID.$goodsAmt.$merchantKey));

$DivideInfo = "{'DivideInfo':[{'Amt':'502','MID':'SMTSUB002m','GoodsName':'상품1'},{'Amt':'502','MID':'SMTSUB003m','GoodsName':'상품2'}]}";
/*
DivideInfo (서브몰 정보) 설정 예시 -  JSON Object 형식으로 설정
{
    'DivideInfo':
    [
        {'Amt':'502','MID':'SMTSUB002m','GoodsName':'상품1'}, // 서브몰 1
        {'Amt':'502','MID':'SMTSUB003m','GoodsName':'상품2'} 	// 서브몰 2
    ]
}
*/

$returnUrl = "http://gvmp.company/myOffice/SmartroPay2/returnMobilePay.php"; // 결제결과를 수신할 가맹점 returnURL 설정
$retryUrl = "https://tspay.smilepay.co.kr/payTest/informMobile.jsp"; // 가맹점 retryURL 설정
$stopUrl = "https://tspay.smilepay.co.kr/payTest/stopUrl.jsp"; // 결제중지 URL
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
        <!--
        table.type {
            border-collapse: collapse;
            text-align: left;
            line-height: 1.5;
        }
        table.type thead th {
            padding: 8px;
            font-weight: bold;
            vertical-align: top;ㄹ
        color: #369;
            border-bottom: 3px solid #036;
            text-align:center;
        }
        table.type tbody th {
            width: 150px;
            padding: 8px;
            font-weight: bold;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
            background: #f3f6f7;
            text-align:center;
        }
        table.type td {
            width: 250px;
            padding: 8px;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
            text-align:center;
        }

        .selectbox {
            position: relative;
            width: auto;
            border: 1px solid #999;
            z-index: 1;
            padding: 4px;
            color: #369;
        }

        input {
            width: 100%;
            padding: 6px 10px;
            margin: 2px 0;
            box-sizing: border-box;
        }

        .btnblue {
            background-color: #6DA2D9;
            box-shadow: 0 0 0 1px #6698cb inset,
            0 0 0 2px rgba(255,255,255,0.15) inset,
            0 4px 0 0 rgba(110, 164, 219, .7),
            0 4px 0 1px rgba(0,0,0,.4),
            0 4px 4px 1px rgba(0,0,0,0.5);
        }

        -->
    </style>

    <title>스마트로::인터넷결제</title>
    <script>
        /**
         * URL 숨기기
         */
        window.addEventListener('load', function(){
            setTimeout(scrollTo, 0, 0, 1);
        }, false);

        /**
         * 연동페이지
         * 1.상점별 결제수단 파악해서 쿠키에 넣어줄지 결정
         */
        function goPay() {
            var formNm = document.tranMgr;

            // 메일주소 검증
            if(formNm.BuyerEmail.value == '') {
                alert("구매자 메일주소를 입력해 주세요.");
                return;
            } else {
                if(!EmailCheck(formNm.BuyerEmail.value)) {
                    alert("구매자메일주소가 형식에 맞지 않습니다.");
                    return;
                }
            }

            if(formNm.ParentEmail.value != '') {
                if(!EmailCheck(formNm.ParentEmail.value)) {
                    alert("보호자메일주소가 형식에 맞지 않습니다.");
                    return;
                }
            }

            // 주문번호 특수문자 체크
            if(isSpecial(formNm.Moid.value)) {
                alert("주문번호에는 특수문자가 허용되지 않습니다.");
                return;
            }

            formNm.action = './mainMobilePayConfirm.php';
            formNm.submit();
        }

        /****************************************************************************************
         * 특수 문자 체크
         ****************************************************************************************/
        function isSpecial(checkStr) {
            var checkOK = "~`':;{}[]<>,.!@#$%^&*()_+|\\/?";

            for (var i = 0;  i < checkStr.length;  i++)	{
                ch = checkStr.charAt(i);
                for (var j = 0;  j < checkOK.length;  j++) {
                    if (ch == checkOK.charAt(j)) {return true; break;}
                }
            }
            return false;
        }

        /****************************************************************************************
         * Email Check
         ****************************************************************************************/
        function EmailCheck(arg_v) {
            var	vValue = "";

            if(arg_v.indexOf("@") < 0) return false;

            for(var i = 0; i < arg_v.length; i++) {
                vValue = arg_v.charAt(i);

                if (AlphaCheck(vValue) == false  && NumberCheck(vValue) == false && EmailSpecialCheck(vValue) == false )
                    return false;
            }
            return true;
        }

        /****************************************************************************************
         * 영문 판별
         ****************************************************************************************/
        function AlphaCheck(arg_v) {
            var alphaStr = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

            if ( alphaStr.indexOf(arg_v) < 0 )
                return false;
            else
                return true;
        }

        /****************************************************************************************
         * 숫자 판별
         ****************************************************************************************/
        function NumberCheck(arg_v) {
            var numStr = "0123456789";

            if ( numStr.indexOf(arg_v) < 0 )
                return false;
            else
                return true;
        }

        /****************************************************************************************
         * Email 특수 문자 체크
         ****************************************************************************************/
        function EmailSpecialCheck(arg_v) {
            var SpecialStr = "_-@.";

            if ( SpecialStr.indexOf(arg_v) < 0 )
                return false;
            else
                return true;
        }

    </script>

</head>
<body ondragstart='' onselectstart='' style="overflow: scroll">
<form name="tranMgr" method="post" action="">
    <table class="type">

        <thead>
        <tr>
            <th colspan="4">[결제 요청]</th>
        </tr>
        <tr>
            <th scope="cols">항목명</th>
            <th scope="cols">파라미터</th>
            <th scope="cols" style="width:80px;">필수여부</th>
            <th scope="cols" style="width:400px;">입력</th>
        </tr>
        </thead>

        <tbody>

        <tr>
            <th scope="row">지불수단</th>
            <td>PayMethod</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <select name="PayMethod" class="selectbox">
                    <option value="VBANK" selected>VBANK-[가상계좌]</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">카카오시 사용</th>
            <td>PayType</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="PayType" class="selectbox">
                    <option value="" selected>[선택]</option>
                    <option value="KPCD">KPCD-[신용카드]</option>
                    <option value="KPMY">KPMY-[머니]</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">상품갯수</th>
            <td>GoodsCnt</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="GoodsCnt" maxlength="2" value="1" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">거래 상품명</th>
            <td>GoodsName</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="GoodsName" maxlength="80" value="거래 상품명" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">거래 금액</th>
            <td>Amt</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="Amt" maxlength="12" value="1004" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">상품주문번호</th>
            <td>Moid</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="Moid" maxlength="64" value="<?php echo $Moid ?>" placeholder="특수문자 포함 불가"/>
            </td>
        </tr>

        <tr>
            <th scope="row">상점아이디</th>
            <td>MID</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="MID" maxlength="10" value="<?php echo $MID ?>" placeholder="계약시 부여된 Merchant ID"/>
            </td>
        </tr>

        <tr>
            <th scope="row">결제결과전송URL</th>
            <td>ReturnURL</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input name="ReturnURL" size="50" class="input" value="<?php echo $returnUrl ?>">
            </td>
        </tr>

        <tr>
            <th scope="row">현금영수증사용여부(KAKAO/TOSS)</th>
            <td>ReceiptType</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="ReceiptType" class="selectbox">
                    <option value="" selected>선택</option>
                    <option value="Y">Y-사용</option>
                    <option value="N">N-미사용</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">결제결과RetrURL</th>
            <td>RetryURL</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="RetryURL" maxlength="200" value="<?php echo $retryUrl ?>" placeholder="http 포함. ReturnURL을 통해서 결과를 못 받았을 경우 대비"/>
            </td>
        </tr>

        <tr>
            <th scope="row">결제중지 URL</th>
            <td>StopURL</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="StopURL" maxlength="200" value="<?php echo $stopUrl ?>" placeholder="http 포함. ReturnURL을 통해서 결과를 못 받았을 경우 대비"/>
            </td>
        </tr>

        <tr>
            <th scope="row">회원사고객ID</th>
            <td>mallUserID</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="mallUserID" maxlength="20" value="" placeholder="문화상품권, 간편결제 사용"/>
            </td>
        </tr>

        <tr>
            <th scope="row">구매자명</th>
            <td>BuyerName</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="BuyerName" maxlength="30" value="구매자명" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">구매자연락처</th>
            <td>BuyerTel</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="BuyerTel" maxlength="30" value="01099991111" placeholder="-없이"/>
            </td>
        </tr>

        <tr>
            <th scope="row">구매자메일주소</th>
            <td>BuyerEmail</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="BuyerEmail" maxlength="60" value="noname@smartro.co.kr" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">보호자메일주소</th>
            <td>ParentEmail</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="ParentEmail" maxlength="60" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">배송지주소</th>
            <td>BuyerAddr</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="BuyerAddr" maxlength="100" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">우편번호</th>
            <td>BuyerPostNo</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="BuyerPostNo" maxlength="6" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">회원사고객IP</th>
            <td>UserIP</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="UserIP" maxlength="20" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">상점서버IP</th>
            <td>MallIP</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="MallIP" maxlength="20" value="<?php echo $server_ip ?>" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">가상계좌입금만료일</th>
            <td>VbankExpDate</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="VbankExpDate" maxlength="8" value="<?php echo $VbankExpDate ?>" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">암호화데이타</th>
            <td>EncryptData</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="EncryptData" maxlength="40" value="<?php echo $encryptData ?>" placeholder="위/변조방지 HASH 데이타"/>
            </td>
        </tr>

        <tr>
            <th scope="row">상점결제결과처리방식</th>
            <td>MallResultFWD</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left;color: #a3a3f0;">
                <select name="MallResultFWD" class="selectbox">
                    <option value="" selected>선택</option>
                    <option value="Y">Y-[권장]상점결과URL즉시전송</option>
                    <option value="N">N-PG결제결과창 호출</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">결제타입</th>
            <td>TransType</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="TransType" maxlength="3" value="0" placeholder="0-일반" readonly="true" />
            </td>
        </tr>

        <tr>
            <th scope="row">소켓사용여부</th>
            <td>SocketYN</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SocketYN" maxlength="3" value="N" placeholder="" readonly="true"/>
            </td>
        </tr>

        <tr>
            <th scope="row">휴대폰결제타입</th>
            <td>GoodsCl</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="GoodsCl" class="selectbox">
                    <option value="" selected>선택</option>
                    <option value="0">0-컨텐츠</option>
                    <option value="1">1-실물</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">응답메시지 타입</th>
            <td>Language</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="Language" class="selectbox">
                    <option value="" selected>선택</option>
                    <option value="KR">한국어</option>
                    <option value="EN">영어</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">결제결과인코딩</th>
            <td>EncodingType</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="EncodingType" class="selectbox">
                    <option value="" selected>선택</option>
                    <option value="euckr">euc-kr charset 응답</option>
                    <option value="utf8">utf-8 charset 응답</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">결제 연동방식</th>
            <td>clientType</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="clientType" class="selectbox">
                    <option value="WEB" selected>모바일웹</option>
                    <option value="APP">앱</option>
                    <option value="HYB">하이브리드 앱</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">APP URL 스키마</th>
            <td>urlScheme</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="urlScheme" maxlength="200" value="" placeholder="가맹점 APP의 URL 스키마 명"/>
            </td>
        </tr>

        <tr>
            <th scope="row">용역제공기간</th>
            <td>OfferPeriod</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="OfferPeriod" maxlength="16" value="" placeholder="YYYYMMDDYYYYMMDD 제공기간이 없을시 공백처리"/>
            </td>
        </tr>

        <tr>
            <th scope="row">전문생성일시</th>
            <td>ediDate</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="ediDate" maxlength="14" value="<?php echo $ediDate ?>" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">TAX 코드</th>
            <td>TaxCD</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="TaxCD" maxlength="1" value="" placeholder="부가세 사용: 2 (스마트로 영업담당자와 협의 필요)"/>
            </td>
        </tr>

        <tr>
            <th scope="row">봉사료</th>
            <td>SvcAmt</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SvcAmt" maxlength="9" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">부가세</th>
            <td>Tax</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="Tax" maxlength="9" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">뱅크페이브릿지 방식구분</th>
            <td>IsBankPayBridge</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="IsBankPayBridge" maxlength="1" value="Y" placeholder="Y-[고정값]-뱅크페이 신규 브릿지 페이지 호출 방식" readonly="true" />
            </td>
        </tr>

        <tr>
            <th scope="row">결제카드사코드</th>
            <td>fn_cd</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="fn_cd" maxlength="3" value="" placeholder="카드사 선택 호출시 사용"/>
            </td>
        </tr>

        <tr>
            <th scope="row">할부개월</th>
            <td>CardQuota</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="CardQuota" maxlength="3" value="" placeholder="카드사 선택 호출시 사용. 00:일시, 02,…,12"/>
            </td>
        </tr>

        <tr>
            <th scope="row">카드사포인트사용</th>
            <td>CardPoint</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="CardPoint" class="selectbox">
                    <option value="" selected>NonUI 사용시 선택</option>
                    <option value="0">0-미사용</option>
                    <option value="1">1-사용</option>
                    <option value="2">2-사용, UI안보임</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">ISP 인증 후 결과 페이지 기존창 처리 여부</th>
            <td>IspWapUrl</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="IspWapUrl" class="selectbox">
                    <option value="" selected>선택</option>
                    <option value="Y">Y-[권장]기존결제창</option>
                    <option value="N">N-새창</option>
                </select>
            </td>
        </tr>

        <!-- 에스크로 전용 추가 파라미터

        <tr>
            <th scope="row">거래구분</th>
            <td>EscrowCD</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="EscrowCD" class="selectbox">
                    <option value="" selected>선택</option>
                    <option value="0">일반거래</option>
                    <option value="1">에스크로거래</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">택배사코드</th>
            <td>DeliveryCode</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DeliveryCode" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">송장번호</th>
            <td>DeliveryNumber</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DeliveryNumber" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">배송지주소1</th>
            <td>DeliveryAddr1</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DeliveryAddr1" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">배송지주소2</th>
            <td>DeliveryAddr2</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DeliveryAddr2" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">판매자주소1</th>
            <td>SellerAddr1</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerAddr1" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">판매자주소2</th>
            <td>SellerAddr2</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerAddr2" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">배송지우편번호</th>
            <td>DeliveryPost</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DeliveryPost" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">판매자우편번호</th>
            <td>SellerPost</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerPost" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">판매자전화번호</th>
            <td>SellerTel</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerTel" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">판매자아이디</th>
            <td>SellerId</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerId" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">판매자이름</th>
            <td>SellerName</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerName" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">수신자이름</th>
            <td>ReceiverName</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="ReceiverName" value=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">수신자번호</th>
            <td>ReceiverTel</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="ReceiverTel" value=""/>
            </td>
        </tr>

        -->

        <!-- 서브몰 정산 가맹점 전용 추가 파라미터 -->

        <tr>
            <th scope="row">상품정보</th>
            <td>Productinfo</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="Productinfo" maxlength="2000" value="padj" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">상점 모드</th>
            <td>DivideInfo</td>
            <td style="width:80px;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DivideInfo" maxlength="2000" value="<?php echo $DivideInfo ?>" placeholder="서브몰승인데이타"/>
            </td>
        </tr>

        <tr>
            <td class="btnblue" colspan="4" onclick="goPay();">결제하기</td>
        </tr>

        </tbody>

    </table>

    <input type="hidden" name="merchantKey" value="<?php echo $merchantKey ?>"/>

</form>

</body>
</html>
