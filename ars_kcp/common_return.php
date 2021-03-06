<?
    /* ============================================================================== */
    /* =   PAGE : 공통 통보 페이지                                                  = */
    /* = -------------------------------------------------------------------------- = */
    /* =   Copyright (c)  2006   KCP Inc.   All Rights Reserverd.                   = */
    /* ============================================================================== */
?>
<?
    /* ============================================================================== */
    /* =   01. 공통 통보 페이지 설명(필독!!)                                        = */
    /* = -------------------------------------------------------------------------- = */
    /* =   모모캐쉬 서비스의 경우, 모모패스, 모모캐쉬								= */
    /* =   KCP 를 통해 별도로 통보 받을 수 있습니다. 이러한 통보 데이터를 받기      = */
    /* =   위해 가맹점측은 결과를 전송받는 페이지를 마련해 놓아야 합니다.           = */
    /* =   현재의 페이지를 업체에 맞게 수정하신 후, KCP 관리자 페이지에 등록해      = */
    /* =   주시기 바랍니다. 등록 방법은 연동 매뉴얼을 참고하시기 바랍니다.          = */
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   02. 공통 통보 데이터 받기                                                = */
    /* = -------------------------------------------------------------------------- = */
    $site_cd      = $_POST[  "site_cd"      ] ;		// 사이트 코드
    $tno          = $_POST[  "tno"          ] ;		// KCP 거래번호
    $order_id     = $_POST[  "order_id"     ] ;		// 주문번호
    $tx_cd        = $_POST[  "tx_cd"        ] ;		// 업무처리 구분 코드
    $tx_tm        = $_POST[  "tx_tm"        ] ;		// 업무처리 완료 시간
    /* = -------------------------------------------------------------------------- = */
	$res_cd			=  "";							// 응답코드			
    $res_msg		=  "";							// 응답메세지											
    $noti_type		=  "";							// 승인/인증 노티 구분
    $cert_type		=  "";							// 인증 노티 상세 구분 코드
    $cert_no		=  "";							// 인증 번호 
    $trade_ymd		=  "";							// 노티 처리일자   
    $trade_hms		=  "";							// 노티 처리시각
    $phone_no		=  "";							// 휴대폰 번호
    $sale_01_yn		=  "";							// 점유인증 과금대상 구분
    $sale_02_yn		=  "";							// 본인인증 과금대상 구분
    $app_time		=  "";							// 승인시각
    $momo_amt		=  "";							// 실 승인 금액
	$result			=  "";
	
    /* = -------------------------------------------------------------------------- = */
    /* =   01-1.모모캐쉬															= */
    /* = -------------------------------------------------------------------------- = */
    if ( $tx_cd == "TX11" )
    {
 		$res_cd			=  $_POST[  "res_cd"        ] ;           
		$res_msg		=  $_POST[  "res_msg"		] ;		
		$noti_type		=  $_POST[  "noti_type"		] ; 		
		$cert_no		=  $_POST[  "cert_no"		] ; 	
		$order_id		=  $_POST[  "order_id"		] ;     	
		$trade_ymd		=  $_POST[  "trade_ymd"		] ;     	
		$trade_hms		=  $_POST[  "trade_hms"		] ;     	
		$phone_no		=  $_POST[  "phone_no"		] ;     	
		$app_time		=  $_POST[  "app_time"		] ;     	
		$tno			=  $_POST[  "tno"			] ; 	
		$momo_amt		=  $_POST[  "momo_amt"		] ;     	
		                                                    	
		$result = "0000";                                   	
			
    }

    /* = -------------------------------------------------------------------------- = */
    /* =   01-2. 모모패스															= */
    /* = -------------------------------------------------------------------------- = */
    else if ( $tx_cd =="TX12" )
    {
		$res_cd			=  $_POST[  "res_cd"		] ;       
		$res_msg		=  $_POST[  "res_msg"		] ;		  
		$noti_type		=  $_POST[  "noti_type"		] ; 		
		$cert_type		=  $_POST[  "cert_type"		] ;       
		$cert_no		=  $_POST[  "cert_no"		] ;       
		$order_id		=  $_POST[  "order_id"		] ;       
		$trade_ymd		=  $_POST[  "trade_ymd"		] ;       
		$trade_hms		=  $_POST[  "trade_hms"		] ;       
		$phone_no		=  $_POST[  "phone_no"		] ;       
		if ( $cert_type == "CT01" )                           
		{                                                     
			$sale_01_yn		=  $_POST[  "sale_01_yn"	] ;   
		}                                                     
		else if ( $cert_type == "CT02" )                      
		{                                                     
			$sale_02_yn		=  $_POST[  "sale_02_yn"	] ;   
		}                                                     
		$result = "0000";   


    }
	else
	{
		$result = "1234";
	}
   
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   03. 공통 통보 결과를 업체 자체적으로 DB 처리 작업하시는 부분입니다.      = */
    /* = -------------------------------------------------------------------------- = */
    /* =   통보 결과를 DB 작업 하는 과정에서 정상적으로 통보된 건에 대해 DB 작업을  = */
    /* =   실패하여 DB update 가 완료되지 않은 경우, 결과를 재통보 받을 수 있는     = */
    /* =   프로세스가 구성되어 있습니다. 소스에서 result 라는 Form 값을 생성 하신   = */
    /* =   후, DB 작업이 성공 한 경우, result 의 값을 "0000" 로 세팅해 주시고,      = */
    /* =   DB 작업이 실패 한 경우, result 의 값을 "0000" 이외의 값으로 세팅해 주시  = */
    /* =   기 바랍니다. result 값이 "0000" 이 아닌 경우에는 재통보를 받게 됩니다.   = */
    /* = -------------------------------------------------------------------------- = */

    /* = -------------------------------------------------------------------------- = */
    /* =   01-1.모모캐쉬	   													    = */
    /* = -------------------------------------------------------------------------- = */
    if ( $tx_cd == "TX11" )
    {
    }

    /* = -------------------------------------------------------------------------- = */
    /* =   01-2. 모모패스															= */
    /* = -------------------------------------------------------------------------- = */
    else if ( $tx_cd == "TX12" )
    {
    }

    

    /* ============================================================================== */
    /* =   04. result 값 세팅 하기                                                  = */
    /* ============================================================================== */
?>
<html><body><form><input type="hidden" name="result" value="<?=$result?>"></form></body></html>