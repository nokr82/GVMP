<?php
/******************************************************************************
 *
 *	 SYSTEM NAME		: 간편결제
 *	 PROGRAM NAME		: ssp_main.php
 *	 MAKER				:
 *	 MAKE DATE			: 2017.12.06
 *	 PROGRAM CONTENTS	: 신용카드 간편결제 호출 샘플 파일 신용카드
 *
 ************************** 변 경 이 력 *****************************************
 * 번호	작업자		작업일				변경내용
 *	1	  sajang	2017.12.06			신용카드 간편결제 호출 샘플 파일 신용카드
 *	2	  yehwang	2019.10.21			cardInterest, moid 생성규칙 제거
 *******************************************************************************/
?>
<?php
$DEV_SSP_ACTION_URL = "https://tpay.smilepay.co.kr/ssp/reqPay.jsp";	//개발
$PRD_SSP_ACTION_URL = "https://pay.smilepay.co.kr/ssp/reqPay.jsp";	//운영

//전문생성일시
$ediDate = date("YmdHis");
//상점서명키 (꼭 해당 상점키로 바꿔주세요)
$merchantKey = "xpwmZmU7KqQE0c25w3fC+gri3irgQrsyZm0BIHZMT43YTkuIjlkiVNR5EZe/zDNSQvgzwyWTKISYx1f6/5o9Kg==";
//상품금액
$goodsAmt = "50000";
// 상점ID
$mid = "gsisuper3m";
//암호화데이타
$encryptData = base64_encode(md5($ediDate.$MID.$goodsAmt.$merchantKey));
//서비스 URL
$actionUrl = $DEV_SSP_ACTION_URL;//개발

//상점IP 설정
$mallIP = "10.0.0.1";
//고객IP 설정
$userIP = $_SERVER['REMOTE_ADDR'];
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

    <title>간편결제 테스트</title>

    <script type="text/javascript">

        var chkWin=[];
        var successYn = "X";
        var resultCd = "";
        var resultMsg = "";
        var payFormNm = "";

        function openSspay()
        {
            try
            {
                successYn = "X";
                chkWin=[];

                childwin = window.open('','payWindow', 'width=390, height=510, resizable=no, scrollbars=no, status=0, titlebar=0, toolbar=0, left=435, top=250');
                childwin.moveTo(screen.availWidth/2 - 400/2,screen.availHeight/2 - 600/2);

                chkWin.push(childwin);
                setTimeout("closeWindowCheck()", 500);

                payFormNm = document.tranMgr;

                if (payFormNm.canHaveHTML) {
                    document.charset = payFormNm.acceptCharset;
                }

                payFormNm.target = 'payWindow';
                payFormNm.action = '<?php echo $actionUrl ?>';
                payFormNm.submit();
            }
            catch(e)
            {
                alert('간편결제 서비스가 실행중입니다.');
                childwin.focus();
                return;
            }
        }

        function closeWindowCheck()
        {
            try
            {
                if(!chkWin[0].closed)
                {
                    setTimeout("closeWindowCheck()", 500);
                }
                else
                {
                    if(successYn == "Y" && resultCd =="0000" )
                    {
                    }
                    else if(successYn == "X")
                    {
                        successYn = "M";
                        setTimeout("closeWindowCheck()", 300);
                    }
                    else if(successYn == "M")
                    {
                        alert("간편결제서비스를 종료하여습니다. 상점 페이지로 이동합니다.");
                        //document.tranMgr.action = "";//이동할 페이지 설정
                        //document.tranMgr.submit();
                    }
                    else
                    {
                        alert("결과 코드:"+ resultCd +"\n"+ resultMsg);
                    }
                }
            }
            catch (e)
            {
            }
        }

    </script>
</head>
<!-- <body oncontextmenu='return false' ondragstart='' onselectstart='' style="overflow: scroll"> -->
<body ondragstart='' onselectstart='' style="overflow: scroll">

<form name="tranMgr" method="post" action="">

    <table class="type">

        <thead>
        <tr>
            <th colspan="4">[간편결제]</th>
        </tr>
        <tr>
            <th scope="cols">항목명</th>
            <th scope="cols">파라미터</th>
            <th scope="cols">입력</th>
        </tr>
        </thead>
        <tr>
            <th scope="row">결제수단<span class="style9">(*)</span></th>
            <td>PayMethod</td>
            <td><input name="PayMethod" size="20" class="input"
                       value="CARD">신용카드</td>
        </tr>

        <tr>
            <th scope="row">회원사아이디<span class="style9">(*)</span></th>
            <td>MID</td>
            <td><input name="MID" size="20" class="input"
                       value="<?php echo $ mid  ?>"></td>
        </tr>

        <tr>
            <th scope="row">상품가격<span class="style9">(*)</span></th>
            <td>Amt</td>
            <td><input name="Amt" size="20" value="<?php echo $ goodsAmt  ?>"
                       class="input"></td>
        </tr>

        <tr>
            <th scope="row">결제자명</th>
            <td>BuyerName</td>
            <td><input name="BuyerName" class="input" size="20" value="홍길동">
            </td>
        </tr>

        <tr>
            <th scope="row">결제자이메일<span class="style9">(*)</span></th>
            <td>BuyerEmail</td>
            <td><input name="BuyerEmail" class="input" size="20"
                       value="user@email.net"></td>
        </tr>

        <tr>
            <th scope="row">보호자이메일</th>
            <td>ParentEmail</td>
            <td><input name="ParentEmail" class="input" size="20">
            </td>
        </tr>

        <tr>
            <th scope="row">현금영수증발급번호</th>
            <td>BuyerAuthNum</td>
            <td><input name="BuyerAuthNum" class="input" size="20"
                       maxlength="13">(-)없이 입력:주민번호,사업자번호</td>
        </tr>

        <tr>
            <th scope="row">결제자연락처</th>
            <td>BuyerTel</td>
            <td><input name="BuyerTel" class="input" size="20"
                       value="0212345678"></td>
        </tr>

        <tr>
            <th scope="row">상품명<span class="style9">(*)</span></th>
            <td>GoodsName</td>
            <td><input name="GoodsName" size="20" class="input"
                       value="테스트상품"></td>
        </tr>

        <tr>
            <th scope="row">상품갯수</th>
            <td>GoodsCnt</td>
            <td><input name="GoodsCnt" size="20" class="input" value="1">
            </td>
        </tr>

        <tr>
            <th scope="row">고객사회원ID(간편결제)<span class="style9">(*)</span></th>
            <td>MallUserID</td>
            <td><input name="MallUserID" class="input" size="20"
                       value="S100000"></td>
        </tr>

        <tr>
            <th scope="row">상점예비정보</th>
            <td>MallReserved</td>
            <td><input name="MallReserved" class="input" size="20"
                       value="MallReserved"></td>
        </tr>

        <tr>
            <th scope="row">주문번호<span class="style9">(*)</span></th>
            <td>Moid</td>
            <td><input name="Moid" size="20" class="input"
                       value="moid"></td>
        </tr>

        <tr>
            <th scope="row">카드사선택(코드)</th>
            <td>fn_cd</td>
            <td><input name="fn_cd" class="input" size="20" value="">
            </td>
        </tr>

        <tr>
            <th scope="row">할부개월</th>
            <td>CardQuota</td>
            <td><input name="CardQuota" class="input" size="20" value="">
            </td>
        </tr>

        <tr>
            <th scope="row">팝카드 체크</th>
            <td>PopCheck</td>
            <td><input name="PopCheck" class="input" size="20" value="N">Y:
                체크 N:미체크(기본값)</td>
        </tr>

        <tr>
            <th scope="row">결제결과전송 URL<span class="style9">(*)</span></th>
            <td>ReturnUrl</td>
            <td><input name="ReturnUrl" size="50" class="input"
                       value="https://tpay.smilepay.co.kr/pay_ssp_card_trans_result.jsp">
            </td>
        </tr>

        <tr>
            <th scope="row">결제결과 RETRY URL<span class="style9">(*)</span></th>
            <td>RetryURL</td>
            <td><input name="RetryURL" class="input" size="50" value="">
            </td>
        </tr>

        <tr>
            <th scope="row">암호화 파라미터</th>
            <td>EncodeParameters</td>
            <td><input name="EncodeParameters" class="input" size="50"
                       value=""></td>
        </tr>

        <tr>
            <th scope="row">위변조체크시간</th>
            <td>ediDate</td>
            <td><input name="ediDate" class="input" size="50"
                       value="<?php echo $ediDate ?>"></td>
        </tr>

        <tr>
            <th scope="row">암호화데이터</th>
            <td>EncryptData</td>
            <td><input name="EncryptData" class="input" size="50"
                       value="<?php echo $encryptData ?>"></td>
        </tr>

        <tr>
            <th scope="row">소켓사용여부</th>
            <td>SocketYN</td>
            <td><input name="SocketYN" class="input" size="50" value="N">
            </td>
        </tr>

        <tr>
            <th scope="row">결제결과창유무</th>
            <td>ResultYN</td>
            <td><input name="ResultYN" class="input" size="50" value="N">
            </td>
        </tr>

        <tr>
            <th scope="row">결제결과처리방식</th>
            <td>MallResultFWD</td>
            <td><input name="MallResultFWD" class="input" size="50"
                       value="N"></td>
        </tr>

        <tr>
            <th scope="row">간편결제몰ID<span class="style9">(*)</span></th>
            <td>SspMallID</td>
            <td><input name="SspMallID" class="input" size="20"
                       value="GSRETAIL0p"></td>
        </tr>

        <tr>
            <th scope="row">회원사 고객 IP<span class="style9">(*)</span></th>
            <td>UserIP</td>
            <td><input name="UserIP" class="input" size="20"
                       value="<?php echo $userIP ?>"></td>
        </tr>

        <tr>
            <th scope="row">MailIP<span class="style9">(*)</span></th>
            <td>MallIP</td>
            <td><input name="MallIP" class="input" size="20"
                       value="<?php echo $mallIP ?>"></td>
        </tr>

        <tr>
            <th scope="row">거래타입</th>
            <td>TransType</td>
            <td><input name="TransType" class="input" size="20" value="N">
            </td>
        </tr>

        <tr>
            <th scope="row">응답메시지 타입</th>
            <td>Language</td>
            <td><input name="Language" class="input" size="20" value="KR">
            </td>
        </tr>

        <tr>
            <th scope="row">인코딩타입</th>
            <td>EncodingType</td>
            <td><select name="EncodingType" class="input">
                    <option value="">[선택]</option>
                    <option value="euckr" selected>[EUC-KR]</option>
                    <option value="utf8">[UTF-8]</option>
                </select></td>
        </tr>

        <tr>
            <th scope="row">채널타입</th>
            <td>ChannelType</td>
            <td><input name="ChannelType" class="input" size="50"
                       value="WEB"></td>
        </tr>

        <tr>
            <td class="btnblue" colspan="4" onclick="openSspay();">결제하기</td>
        </tr>

    </table>

</form>


<%-- iframe --%>
<iframe src="./blank.html" name="payFrame" frameborder="no" width="0"
        height="0" scrolling="yes" align="center"></iframe>

</body>
</html>
