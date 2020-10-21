<?php
/******************************************************************************
 *
 *    SYSTEM NAME            : 결제요청페이지
 *    PROGRAM NAME        : mainPay.php
 *    MAKER                : sajang
 *    MAKE DATE            : 2017.12.06
 *    PROGRAM CONTENTS    : 결제요청페이지
 *
 ************************** 변 경 이 력 *****************************************
 * 번호    작업자        작업일                변경내용
 *    1    스마트로    2017.12.06        결제요청페이지
 *    2    황예은    2019.10.21        cardInterest, moid 생성규칙 제거
 *    3    황예은    2019.10.24        Kakao, Toss 추가
 *    4    황예은    2019.11.28        escrowCD 추가
 *******************************************************************************/
?>
<?php
$server_ip = "10.0.0.1"; // $_SERVER['SERVER_ADDR'];	// 서버 IP 가져오기
$VbankExpDate = date("Ymd", strtotime($day . "1 day"));
$ediDate = date("YmdHis"); // 전문생성일시
$Moid = "Moid";

// 상점서명키 (꼭 해당 상점키로 바꿔주세요)
$merchantKey = "nja3kmcUhyD8hQIS51wxFYCYXEWP530/qWB1/LjLPGNch9KeOPtnWYgLghLBUFOUShq4g3uYTMwL9f8164XLOw==";
$MID = "SMTPAY002m";        // MID

$goodsAmt = "1004";        // 상품금액

$encryptData = base64_encode(md5($ediDate . $MID . $goodsAmt . $merchantKey));
$returnUrl = "http://gvmp.company/myOffice/SmartroPay/returnPay.php"; // 결제결과를 수신할 가맹점 returnURL 설정
$retryUrl = "https://tpay.smilepay.co.kr/inform.jsp"; // 가맹점 retryURL 설정

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
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
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
            vertical-align: top;
            color: #369;
            border-bottom: 3px solid #036;
            text-align: center;
        }

        table.type tbody th {
            width: 150px;
            padding: 8px;
            font-weight: bold;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
            background: #f3f6f7;
            text-align: center;
        }

        table.type td {
            width: 250px;
            padding: 8px;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
            text-align: center;
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
            0 0 0 2px rgba(255, 255, 255, 0.15) inset,
            0 4px 0 0 rgba(110, 164, 219, .7),
            0 4px 0 1px rgba(0, 0, 0, .4),
            0 4px 4px 1px rgba(0, 0, 0, 0.5);
        }

        -->
    </style>

    <title>스마트로::인터넷결제</title>

    <script type="text/javascript">
        function goPay() {
            document.tranMgr.action = './mainPayConfirm.php';
            document.tranMgr.submit();
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
                    <option value="">[선택]</option>
                    <option value="CARD">CARD-[신용카드]</option>
                    <option value="BANK">BANK-[계좌이체]</option>
                    <option value="VBANK" selected>VBANK-[가상계좌]</option>
                    <option value="CELLPHONE">CELLPHONE-[휴대폰결제]</option>
                    <option value="CLGIFT">CLGIFT-[문화상품권]</option>
                    <option value="TOSS">TOSS-[토스]</option>
                    <option value="KAKAO">KAKAO-[카카오]</option>
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
            <th scope="row">맛동산</th>
            <td>GoodsName</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="GoodsName" maxlength="80" value="맛동산" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">거래 금액</th>
            <td>Amt</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="Amt" maxlength="12" value="<?php echo $goodsAmt ?>" placeholder=""/>
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
                <input type="text" name="MID" maxlength="10" value="<?php echo $MID ?>"
                       placeholder="계약시 부여된 Merchant ID"/>
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
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="RetryURL" maxlength="200" value="<?php echo $retryUrl ?>"
                       placeholder="http 포함. ReturnURL을 통해서 결과를 못 받았을 경우 대비"/>
            </td>
        </tr>

        <tr>
            <th scope="row">회원사고객ID</th>
            <td>mallUserID</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="mallUserID" maxlength="10" value="" placeholder="문화상품권, 간편결제 사용"/>
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
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="ParentEmail" maxlength="60" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">배송지주소</th>
            <td>BuyerAddr</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="BuyerAddr" maxlength="100" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">우편번호</th>
            <td>BuyerPostNo</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="BuyerPostNo" maxlength="6" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">회원사고객IP</th>
            <td>UserIP</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="UserIP" maxlength="20" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">상점서버IP</th>
            <td>MallIP</td>
            <td style="width:80px;color: #a3a3f0;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="MallIP" maxlength="20" value="<?php echo $server_ip ?>" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">가상계좌입금만료일</th>
            <td>VbankExpDate</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="VbankExpDate" maxlength="8" value="<?php echo $VbankExpDate ?>"
                       placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">암호화데이타</th>
            <td>EncryptData</td>
            <td style="width:80px;color: #a3a3f0;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="EncryptData" maxlength="40" value="<?php echo $encryptData ?>"
                       placeholder="위/변조방지 HASH 데이타"/>
            </td>
        </tr>

        <tr>
            <th scope="row">화면처리방식</th>
            <td>FORWARD</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <select name="FORWARD" class="selectbox">

                    <option value="Y" selected>Y-[권장]상점페이지 팝업호출</option>
                    <option value="N">N-결제모듈내부 팝업호출</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">상점결제결과처리</th>
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
                <input type="text" name="TransType" maxlength="2" value="0" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">결제결과인코딩</th>
            <td>EncodingType</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="EncodingType" class="selectbox">
                    <option value="">선택</option>
                    <option value="euckr">euc-kr charset 응답</option>
                    <option value="utf8" selected>utf-8 charset 응답</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">오픈타입</th>
            <td>OpenType</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="OpenType" class="selectbox">
                    <option value="KR" selected>KR-한글</option>
                    <option value="EN">EN-영어</option>
                    <option value="CN_S">CN_S-중국어(간자체)</option>
                    <option value="CN_T">CN_T-중국어(번자체)</option>
                    <option value="JP">JP-일본어</option>
                    <option value="FR">FR-프랑스어</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">휴대폰결제타입</th>
            <td>GoodsCl</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="GoodsCl" class="selectbox">
                    <option value="" selected>선택</option>
                    <option value="0">0-컨텐츠</option>
                    <option value="1">1-실물</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">용역제공기간</th>
            <td>OfferPeriod</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="OfferPeriod" maxlength="16" value=""
                       placeholder="YYYYMMDDYYYYMMDD 제공기간이 없을시 공백처리"/>
            </td>
        </tr>

        <tr>
            <th scope="row">카드사코드</th>
            <td>fn_cd</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="fn_cd" maxlength="3" value="" placeholder="카드사 선택 호출시 사용"/>
            </td>
        </tr>

        <tr>
            <th scope="row">할부개월</th>
            <td>CardQuota</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="CardQuota" maxlength="3" value="" placeholder="카드사 선택 호출시 사용. 00:일시, 02,…,12"/>
            </td>
        </tr>


        <tr>
            <th scope="row">소켓사용여부</th>
            <td>SocketYN</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SocketYN" maxlength="2" value="N" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">간편결제 몰아이디</th>
            <td>SspMallID</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SspMallID" maxlength="2" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">UI Color</th>
            <td>SkinColor</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="SkinColor" class="selectbox">
                    <option value="" selected>선택</option>
                    <option value="RED">RED</option>
                    <option value="VIOLET">VIOLET</option>
                    <option value="BLUE">BLUE</option>
                    <option value="GREEN">GREEN</option>
                    <option value="YELLOW">옐로우</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">TAX 코드</th>
            <td>TaxCD</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="TaxCD" maxlength="1" value="" placeholder="부가세 사용: 2 (스마트로 영업담당자와 협의 필요)"/>
            </td>
        </tr>

        <tr>
            <th scope="row">봉사료</th>
            <td>SvcAmt</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SvcAmt" maxlength="12" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">부가세</th>
            <td>Tax</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="Tax" maxlength="12" value="" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">카드사포인트사용</th>
            <td>CardPoint</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
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
            <th scope="row">전문생성일시</th>
            <td>ediDate</td>
            <td style="width:80px;">필수</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="ediDate" maxlength="14" value="<?php echo $ediDate ?>" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">결과응답URLEncoding</th>
            <td>UrlEncode</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <select name="UrlEncode" class="selectbox">
                    <option value="">선택</option>
                    <option value="Y">Y-사용</option>
                    <option value="N" selected>N-미사용</option>
                </select>
            </td>
        </tr>

        <!-- 에스크로 전용 결제요청 파라미터

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
            <th scope="row" style="font-size:9pt">에스크로 택배사 코드</th>
            <td>DeliveryCode</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DeliveryCode" maxlength="100" value="00" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 송장번호</th>
            <td>DeliveryNumber</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DeliveryNumber" maxlength="100" value="611517696999" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 배송지 주소1</th>
            <td>DeliveryAddr1</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DeliveryAddr1" maxlength="100" value="서울시 동작구 사당동" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 배송지 주소2</th>
            <td>DeliveryAddr2</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DeliveryAddr2" maxlength="100" value="123-1" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 배송지 우편번호</th>
            <td>DeliveryPost</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DeliveryPost" maxlength="100" value="987-123" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 배송받는이 이름</th>
            <td>ReceiverName</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="ReceiverName" maxlength="100" value="홍길동" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 배송받는이 전화번호</th>
            <td>ReceiverTel</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="ReceiverTel" maxlength="100" value="010-0000-0000" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 판매자 주소1</th>
            <td>SellerAddr1</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerAddr1" maxlength="100" value="서울시 동작구 낙성대동" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 판매자 주소2</th>
            <td>SellerAddr2</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerAddr2" maxlength="100" value="456-1" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 판매자 우편번호</th>
            <td>SellerPost</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerPost" maxlength="100" value="345-893" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 판매자 전화번호</th>
            <td>SellerTel</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerTel" maxlength="100" value="010-9999-9999" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 판매자 ID</th>
            <td>SellerId</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerId" maxlength="100" value="identification" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row" style="font-size:9pt">에스크로 판매자 이름</th>
            <td>SellerName</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="SellerName" maxlength="100" value="임꺽정" placeholder=""/>
            </td>
        </tr>
-->
        <!-- 서브몰 정산 가맹점 전용 추가 파라미터 -->

        <tr>
            <th scope="row">상품정보</th>
            <td>Productinfo</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="Productinfo" maxlength="2000" value="padj" placeholder=""/>
            </td>
        </tr>

        <tr>
            <th scope="row">상점 모드</th>
            <td>DivideInfo</td>
            <td style="width:80px;color: #a3a3f0;">선택</td>
            <td style="width:200px;text-align:left">
                <input type="text" name="DivideInfo" maxlength="2000" value="<?php echo $DivideInfo ?>"
                       placeholder="서브몰승인데이타"/>
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
