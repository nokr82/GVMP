<?php
/******************************************************************************
 *
 *	@ SYSTEM NAME		: SmilePay Url Call 방식 결제 취소 테스트 페이지
 *	@ PROGRAM NAME		: CancelPayUrlCallSample.php
 *	@ MAKER				: sajang
 *	@ MAKE DATE			: 2018.03.19
 *	@ PROGRAM CONTENTS	: SmilePay Url Call 방식 결제 취소 테스트 페이지
 *						  운영 MID/상점키는 스마트로 영업담당자 통하여
 *						  받으실 수 있습니다.
 ************************** 변 경 이 력 *****************************************
 * 번호	작업자		작업일				변경내용
 *	1	sajang		2018.03.19	SmilePay Url Call 방식 결제 취소 테스트 페이지
 *******************************************************************************/
?>
<?php

$MERCHANT_KEY = "0/4GFsSd7ERVRGX9WHOzJ96GyeMTwvIaKSWUCKmN3fDklNRGw3CualCFoMPZaS99YiFGOuwtzTkrLo4bR4V+Ow==";// MID(SMTPAY001m)의 상점키 설정 - 결제 요청한 상점ID의 상점키를 입력한다.

$DEV_CANCEL_ACTION_URL = "https://tpay.smilepay.co.kr/cancel/payCancelNVProcess.jsp";//개발
$PRD_CANCEL_ACTION_URL = "https://pay.smilepay.co.kr/cancel/payCancelNVProcess.jsp";//운영

$SUCCESS_CANCEL = "2001";//취소 성공 코드
$SUCCESS_REFUND = "2211";//환불 성공 코드(계좌이체, 가상계좌)
$CHAR_SET = "EUC-KR";

$result = "";
$rTemp = "";

//TID + 상점키 + 취소 금액 + 취소타입
$hashData = $_REQUEST['TID'].$MERCHANT_KEY.$_REQUEST['CancelAmt'].$_REQUEST['PartialCancelCode'];
$DivideInfo = $_REQUEST['DivideInfo']; //7.서브몰 정보

if(!empty($DivideInfo)) // Base64 인코딩(utf-8 인코딩)
{
    $temp = str_replace("&#39;", "\"", $DivideInfo);
    $euckrString = iconv("euc-kr", "utf-8", $temp);
    $b64Enc = base64_encode($euckrString);

    $DivideInfo = $b64Enc;
}

//취소 요청 데이터 설정
$cancelRequest = array(
    'TID'=>$_REQUEST['TID'], 													//1.취소할 거래 TID [필수]
    'CancelAmt'=>$_REQUEST['CancelAmt'], 										//2.취소 금액	[필수]
    'Cancelpw'=>$_REQUEST['Cancelpw'], 											//3.취소 패스워드	[필수]
    'CancelMSG'=>urlencode(iconv("utf-8", "euc-kr", $_REQUEST['CancelMSG'])), 	//4.취소 사유 메세지 (euc-kr urlencoding)
    'PartialCancelCode'=>$_REQUEST['PartialCancelCode'], 						//5.전체취소 0, 부분취소 1 [전체취소 Default]
    'DivideInfo'=>$DivideInfo,													//6.서브몰 정보
    'hashData'=>base64_encode(md5($hashData)) 									//7.HASH 설정 [필수]
);

//http 통신
$rTemp = sendByPost($cancelRequest, $DEV_CANCEL_ACTION_URL);
$rTemp = trim($rTemp); // 공백 제거

$result = parseMessage($rTemp, '&', '=');

$PayMethod = $result['PayMethod'];
$PayName = urldecode($result['PayName']);
$MID = $result['MID'];
$TID = $result['TID'];
$CancelAmt = $result['CancelAmt'];
$CancelMSG = urldecode($result['CancelMSG']);
$ResultCode = $result['ResultCode'];
$ResultMsg = urldecode($result['ResultMsg']);
$CancelDate = $result['CancelDate'];
$CancelTime = $result['CancelTime'];
$CancelNum = $result['CancelNum'];
$Moid = $result['Moid'];
$PTID = "";

if($SUCCESS_CANCEL == $ResultCode || $SUCCESS_REFUND == $ResultCode) {
    // 취소 및 환불 성공에 따른 가맹점 비지니스 로직 구현 필요
} else {
    // 취소 및 환불 실패에 따른 가맹점 비지니스 로직 구현 필요
}

?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET?>">
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
        <title>Insert title here</title>
    </head>
    <body ondragstart='' onselectstart='' style="overflow: scroll">

    <form name="tranMgr" method="post" action="" accept-charset="<?php echo $CHAR_SET?>">
        <table class="type">

            <thead>
            <tr>
                <th colspan="4" style="text-align:right;color: #000;">결제취소 테스트 페이지</th>
            </tr>
            </thead>

            <thead>
            <tr>
                <th scope="cols">항목명</th>
                <th scope="cols">파라미터</th>
                <th scope="cols" style="width:80px;">value</th>
            </tr>
            </thead>

            <tbody>

            <tr>
                <th scope="row">지불수단</th>
                <td>PayMethod</td>
                <td><?php echo $PayMethod?></td>
            </tr>
            <tr>
                <th scope="row">지불수단명</th>
                <td>PayName</td>
                <td><?php echo $PayName?></td>
            </tr>

            <tr>
                <th scope="row">상점ID</th>
                <td>MID</td>
                <td><?php echo $MID?></td>
            </tr>

            <tr>
                <th scope="row">원거래번호</th>
                <td>TID</td>
                <td><?php echo $TID?></td>
            </tr>

            <tr>
                <th scope="row">취소금액</th>
                <td>CancelAmt</td>
                <td><?php echo $CancelAmt?></td>
            </tr>

            <tr>
                <th scope="row">취소메세지</th>
                <td>CancelMSG</td>
                <td><?php echo $CancelMSG?></td>
            </tr>

            <tr>
                <th scope="row">결과코드</th>
                <td>ResultCode</td>
                <td><?php echo $ResultCode?></td>
            </tr>

            <tr>
                <th scope="row">결과메시지</th>
                <td>ResultMsg</td>
                <td><?php echo $ResultMsg?></td>
            </tr>

            <tr>
                <th scope="row">취소일자</th>
                <td>CancelDate</td>
                <td><?php echo $CancelDate?></td>
            </tr>

            <tr>
                <th scope="row">취소시간</th>
                <td>CancelTime</td>
                <td><?php echo $CancelTime?></td>
            </tr>

            <tr>
                <th scope="row">취소번호</th>
                <td>CancelNum</td>
                <td><?php echo $CancelNum?></td>
            </tr>

            <tr>
                <th scope="row">주문번호</th>
                <td>Moid</td>
                <td><?php echo $Moid?></td>
            </tr>

            <tr>
                <th scope="row">부분취소 TID</th>
                <td>PTID</td>
                <td><?php echo $PTID?></td>
            </tr>

            </tbody>

        </table>
    </form>

    </body>
    </html>
<?php

/**
 * 응답메세지 파싱
 * @param plainText
 * @param delim
 * @param delim2
 * @return
 */
function parseMessage($plainText, $delim, $delim2)
{
    $tokened_array = explode($delim, $plainText);
    $temp = "";
    $retData = [];

    for ($i = 0; $i < count($tokened_array); $i++) {
        $temp = $tokened_array[$i];
        if ('' != $temp) {
            $temp_array = explode($delim2, $temp);
            $key = trim($temp_array[0]);
            $value = trim($temp_array[1]);
            $retData[$key] = $value;
        }
    }
    return $retData;
}

/**
 * @description Make HTTP-GET call
 * @param       $url
 * @param       array $params
 * @return      HTTP-Response body or an empty string if the request fails or is empty
 */
function sendByGet(array $params, $url) {
    $query = http_build_query($params);
    $ch    = curl_init($url.'?'.$query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
/**
 * @description Make HTTP-POST call
 * @param       $url
 * @param       array $params
 * @return      HTTP-Response body or an empty string if the request fails or is empty
 */
function sendByPost(array $params, $url) {
    $query = http_build_query($params);

    $ch    = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

?>