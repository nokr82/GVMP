<?php
	include_once( $_SERVER["DOCUMENT_ROOT"] . '/myOffice/dbConn.php');


    /* ============================================================================== */
    /* =   PAGE : 공통 통보 PAGE                                                    = */
    /* = -------------------------------------------------------------------------- = */
    /* =   Copyright (c)  2012   KCP Inc.   All Rights Reserverd.                   = */
    /* ============================================================================== */
?>
<?php
    /* ============================================================================== */
    /* =   01. 공통 통보 페이지 설명(필독!!)                                        = */
    /* = -------------------------------------------------------------------------- = */
    /* =   모바일PG, ARS결제요청에 대한 결과를 통보받을 페이지입니다.               = */
    /* =   결제완료 건에 대한 결과 통보를 받으시려면, 가맹점에서는 현재의 페이지를  = */
    /* =   업체에 맞게 수정하신 후, KCP 관리자페이지에 현재 파일의 URL을 등록해     = */
    /* =   주셔야 연동이 완료됩니다. 연동 방법은 연동매뉴얼을 참고하시기 바랍니다.  = */
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   02. 공통 통보 데이터 받기                                                = */
    /* = -------------------------------------------------------------------------- = */
    $site_cd      = $_POST [ "site_cd"  ];                 // 사이트 코드
    $tno          = $_POST [ "tno"      ];                 // KCP 거래번호
    $order_no     = $_POST [ "order_no" ];                 // 주문번호
    $tx_cd        = $_POST [ "tx_cd"    ];                 // 업무처리 구분 코드
    $tx_tm        = $_POST [ "tx_tm"    ];                 // 업무처리 완료 시간
    /* = -------------------------------------------------------------------------- = */
    $res_cd       = "";                                    // 결과코드
    $res_msg      = "";                                    // 결과메세지
    /* = -------------------------------------------------------------------------- = */
    $ars_tx_key   = "";                                    // ARS결제 시퀀스 번호
    $phon_mny     = "";                                    // 결제 금액
    $phon_no      = "";                                    // 휴대폰/유선전화 번호
    $order_nm     = "";                                    // 주문자명
    /* = -------------------------------------------------------------------------- = */
    $card_no      = "";                                    // 카드번호
    $card_cd      = "";                                    // 카드발급사 코드
    $card_name    = "";                                    // 카드발급사 명
    $acqu_cd      = "";                                    // 매입사 코드
    $acqu_name    = "";                                    // 매입사 명
    $app_no       = "";                                    // 승인번호
    $bizx_numb    = "";                                    // 가맹점번호
    $noinf        = "";                                    // 무이자 구분 플래그
    $card_quota   = "";                                    // 할부개월 수
    /* = -------------------------------------------------------------------------- = */

    /* = -------------------------------------------------------------------------- = */
    /* =   02-1. 모바일PG/ARS 결제 통보 데이터 받기                                 = */
    /* = -------------------------------------------------------------------------- = */
    if ( $tx_cd == "TX09" )
    {
        $res_cd     = $_POST[ "res_cd"     ];                // 결과코드
        $res_msg    = $_POST[ "res_msg"    ];                // 결과메세지
        $ars_tx_key = $_POST[ "ars_tx_key" ];                // ARS결제 시퀀스 번호
        $phon_mny   = $_POST[ "phon_mny"   ];                // 결제 금액
        $phon_no    = $_POST[ "phon_no"    ];                // 휴대폰/유선전화 번호
        $order_nm   = $_POST[ "order_nm"   ];                // 주문자명
        $card_no    = $_POST[ "card_no"    ];                // 카드번호
        $card_cd    = $_POST[ "card_cd"    ];                // 카드발급사 코드
        $card_name  = $_POST[ "card_name"  ];                // 카드발급사 명
        $acqu_cd    = $_POST[ "acqu_cd"    ];                // 매입사 코드
        $acqu_name  = $_POST[ "acqu_name"  ];                // 매입사 명
        $app_no     = $_POST[ "app_no"     ];                // 승인번호
        $bizx_numb  = $_POST[ "bizx_numb"  ];                // 가맹점번호
        $noinf      = $_POST[ "noinf"      ];                // 무이자 구분 플래그
        $card_quota = $_POST[ "card_quota" ];                // 할부개월 수
    }

    /* = -------------------------------------------------------------------------- = */
    /* =   02-2. 모바일PG/ARS 결제취소 통보 데이터 받기                             = */
    /* = -------------------------------------------------------------------------- = */
    else if ( $tx_cd == "TX10" )
    {
        $res_cd     = $_POST[ "res_cd"     ];                // 결과코드
        $res_msg    = $_POST[ "res_msg"    ];                // 결과메세지
        $ars_tx_key = $_POST[ "ars_tx_key" ];                // ARS결제 시퀀스 번호
        $phon_mny   = $_POST[ "phon_mny"   ];                // 결제 금액
        $phon_no    = $_POST[ "phon_no"    ];                // 휴대폰/유선전화 번호
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
    /* =   (재통보는 최초 1회 포함 만 24시간동안 최대 10회입니다.)                  = */
    /* = -------------------------------------------------------------------------- = */

    /* = -------------------------------------------------------------------------- = */
    /* =   03-1. 모바일PG/ARS 결제 통보 데이터 DB 처리 작업 부분                    = */
    /* = -------------------------------------------------------------------------- = */
    if ( $tx_cd == "TX09" )
    {
		$strTok =explode(':' , $order_no);

		mysql_query("update g5_member set VMP = VMP + {$phon_mny} where mb_id = '{$strTok[1]}'") or die("ERROR");
		mysql_query("insert into dayPoint set mb_id = '{$strTok[1]}', VMP = {$phon_mny}, date=curdate(), way = 'arsPay;{$strTok[2]}'") or die("ERROR");

    }

    /* = -------------------------------------------------------------------------- = */
    /* =   03-2. 모바일PG/ARS 결제취소 통보 데이터 DB 처리 작업 부분                = */
    /* = -------------------------------------------------------------------------- = */
    else if ( $tx_cd == "TX10" )
    {
    }

    /* ============================================================================== */


    /* ============================================================================== */
    /* =   04. result 값 세팅 하기                                                  = */
    /* ============================================================================== */
?>
<html><body><form><input type="hidden" name="result" value="0000"></form></body></html>