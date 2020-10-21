<?
    /* ============================================================================== */
    /* = ※ 요청 및 결과처리 페이지 ※                                              = */
    /* ------------------------------------------------------------------------------ */
    /* = Copyright(c) 2012 KCP Inc. All Rights Reserved.                            = */
    /* ============================================================================== */
?>

<?
    /* ============================================================================== */
    /* = 라이브러리 및 사이트 정보 include                                          = */
    /* = -------------------------------------------------------------------------- = */

    require "./pp_cli_hub_lib.php";
    require "/ars_kcp/cfg/site_conf_inc.php" ;

    /* ============================================================================== */


    /* ============================================================================== */
    /* =   01. 지불 요청 정보 설정                                                  = */
    /* = -------------------------------------------------------------------------- = */
	$pay_method       = $_POST[ "pay_method"       ];       // 결제 방법
    $ordr_idxx        = $_POST[ "ordr_idxx"        ];       // 주문 번호
    $phon_mny         = $_POST[ "phon_mny"         ];       // 결제 금액
    /* = -------------------------------------------------------------------------- = */
    $good_name        = $_POST[ "good_name"        ];       // 상품 정보
	$buyr_name        = $_POST[ "buyr_name"        ];       // 주문자 이름
    $soc_no           = $_POST[ "soc_no"           ];       // 주민등록번호
    /* = -------------------------------------------------------------------------- = */
    $req_tx           = $_POST[ "req_tx"           ];       // 요청 종류
    $quota            = $_POST[ "quota"            ];       // 할부개월 수
	/* = -------------------------------------------------------------------------- = */
    $comm_id          = $_POST[ "comm_id"          ];       // 이동통신사코드
    $phon_no          = $_POST[ "phon_no"          ];       // 전화번호
	$call_no          = $_POST[ "call_no"          ];       // 상담원 전화번호
    $expr_dt          = $_POST[ "expr_dt"          ];       // 결제 유효기간
    /* = -------------------------------------------------------------------------- = */
    $tx_cd            = "";                                 // 트랜잭션 코드
    /* = -------------------------------------------------------------------------- = */
	$res_cd           = "";                                 // 결과코드
    $res_msg          = "";                                 // 결과메시지
	$ars_trade_no     = "";                                 // 결제거래번호
    $app_time         = "";                                 // 처리시간
	/* = -------------------------------------------------------------------------- = */
    $card_no          = "";                                 // 카드번호
	$card_expiry      = "";                                 // 카드유효기간
    $card_quota       = "";                                 // 카드할부개월
	/* = -------------------------------------------------------------------------- = */
    $ars_tx_key       = "";                                 // ARS주문번호(ARS거래키)
	$ordr_nm          = "";                                 // 요청자 이름
    $site_nm          = "";                                 // 요청 사이트명
    /* = -------------------------------------------------------------------------- = */
    $cert_flg         = $_POST[ "cert_flg"          ];      // 인증 비인증 구분
    $sig_flg          = "";                                 // 호전환 구분
    $vnum_no          = "";                                 // ARS 결제요청 전화번호
    /* = -------------------------------------------------------------------------- = */
	$ars_trade_no     = $_POST[ "ars_trade_no"      ];      // ARS 등록 거래번호
    /* ============================================================================== */

    /* ============================================================================== */
    /* =   02. 인스턴스 생성 및 초기화                                              = */
    /* = -------------------------------------------------------------------------- = */
    $c_PayPlus  = new C_PAYPLUS_CLI;
    $c_PayPlus->mf_clear();
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   03. 처리 요청 정보 설정, 실행                                            = */
    /* = -------------------------------------------------------------------------- = */

    /* = -------------------------------------------------------------------------- = */
    /* =   03-1. 승인 요청                                                          = */
    /* = -------------------------------------------------------------------------- = */
    // 업체 환경 정보
    $cust_ip    = getenv( "REMOTE_ADDR" );


    // 거래 등록 요청 시
    if ( $req_tx == "pay"  )
    {
        $tx_cd = "00100700";
        

        // 공통 정보
        $common_data_set = "";
    
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "amount"   , $phon_mny    );
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "cust_ip"  , $cust_ip  );

		$c_PayPlus->mf_add_payx_data( "common", $common_data_set );

        // 주문 정보

        $c_PayPlus->mf_set_ordr_data( "ordr_idxx",  $ordr_idxx );  // 주문 번호
        $c_PayPlus->mf_set_ordr_data( "good_name",  $good_name );  // 상품 정보
        $c_PayPlus->mf_set_ordr_data( "buyr_name",  $buyr_name );  // 주문자 이름

        // 요청 정보
     	$phon_data_set  = "";

        $phon_data_set .= $c_PayPlus->mf_set_data_us( "phon_mny", $phon_mny );  // 요청금액
        $phon_data_set .= $c_PayPlus->mf_set_data_us( "phon_no",  $phon_no  );  // 요청 전화번호
        $phon_data_set .= $c_PayPlus->mf_set_data_us( "comm_id",  $comm_id  );  // 이동통신사 코드

        if (!expr_dt == "")
		{
			$phon_data_set .= $c_PayPlus->mf_set_data_us( "expr_dt",  $expr_dt  );  // 결제 유효기간
		}

		// 요청수단에 따른 구분

		if ($pay_method == "VARS")
		{
			$phon_data_set .= $c_PayPlus->mf_set_data_us( "phon_txtype",  "11600000"  );  // 결제수단 설정
            $phon_data_set .= $c_PayPlus->mf_set_data_us( "cert_flg",  $cert_flg      );  // 인증, 비인증 설정
		}

		$c_PayPlus->mf_add_payx_data( "phon",  $phon_data_set );

	}

    // 거래 등록 취소 요청 시
	if ( $pay_method == "STRC")
	{
		$tx_cd = "00100700";

		// 공통 정보
        $common_data_set = "";
    
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "amount"   , "0"       );
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "cust_ip"  , $cust_ip  );

		$c_PayPlus->mf_add_payx_data( "common", $common_data_set );


        // 요청 수단에 따른 변경타입 설정

		$phon_data_set .= $c_PayPlus->mf_set_data_us( "phon_txtype",  "13200000"      );  // 결제수단 설정
        $phon_data_set .= $c_PayPlus->mf_set_data_us( "ars_trade_no",  $ars_trade_no  );  // ARS 등록 거래번호

		$c_PayPlus->mf_add_payx_data( "phon",  $phon_data_set );
	}

	// 기존의 사용자에게 SMS 발송 시

	if ( $pay_method == "OSMS" )
	{
		$tx_cd = "00100700";

		// 공통 정보
        $common_data_set = "";
    
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "amount"   , "0"       );
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "cust_ip"  , $cust_ip  );

		$c_PayPlus->mf_add_payx_data( "common", $common_data_set );

        // 요청 수단에 따른 변경타입 설정

		$phon_data_set .= $c_PayPlus->mf_set_data_us( "phon_txtype",  "13300000"      );  // 결제수단 설정
        $phon_data_set .= $c_PayPlus->mf_set_data_us( "ars_trade_no",  $ars_trade_no  );  // ARS 등록 거래번호

		$c_PayPlus->mf_add_payx_data( "phon",  $phon_data_set );
	}




    /* ============================================================================== */

	/* = -------------------------------------------------------------------------- = */
    /* =   03-2. 취소/매입 요청                                                     = */
    /* = -------------------------------------------------------------------------- = */
        else if ( $req_tx == "mod" )
        {
            $mod_type = $_POST[ "mod_type" ];

            $tx_cd = "00200000";

            $c_PayPlus->mf_set_modx_data( "tno",      $_POST[ "tno" ]      );      // KCP 원거래 거래번호
            $c_PayPlus->mf_set_modx_data( "mod_type", $mod_type            );      // 원거래 변경 요청 종류
            $c_PayPlus->mf_set_modx_data( "mod_ip",   $cust_ip             );      // 변경 요청자 IP
            $c_PayPlus->mf_set_modx_data( "mod_desc", $_POST[ "mod_desc" ] );      // 변경 사유
        }
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   03-3. 실행                                                               = */
    /* ------------------------------------------------------------------------------ */
    if ( strlen($tx_cd) > 0 )
    {
        $c_PayPlus->mf_do_tx( "",                $g_conf_home_dir, $g_conf_site_cd,
                              $g_conf_site_key,  $tx_cd,           "",
                              $g_conf_gw_url,    $g_conf_gw_port,  "payplus_cli_slib",
                              $ordr_idxx,        $cust_ip,         "3",
                              "",                "0" );
    }
    else
    {
        $c_PayPlus->m_res_cd  = "9562";
        $c_PayPlus->m_res_msg = "연동 오류";
    }
    $res_cd  = $c_PayPlus->m_res_cd;                      // 결과 코드
    $res_msg = $c_PayPlus->m_res_msg;                     // 결과 메시지
   /* ============================================================================== */


    /* ============================================================================== */
    /* =   04. 승인 결과 처리                                                       = */
    /* = -------------------------------------------------------------------------- = */
    if ( $req_tx ==  "pay"  )
    {
        if ( $res_cd == "0000"  )
        {
    /* = -------------------------------------------------------------------------- = */
    /* =   04-1. 요청 결과 추출                                                     = */
    /* = -------------------------------------------------------------------------- = */
            $ars_trade_no  = $c_PayPlus->mf_get_res_data( "ars_trade_no"  );    // ARS 등록번호
            $app_time      = $c_PayPlus->mf_get_res_data( "app_time"      );    // 요청 시간
            $phon_mny      = $c_PayPlus->mf_get_res_data( "phon_mny"      );    // 요청 금액
			$phon_no       = $c_PayPlus->mf_get_res_data( "phon_no"       );    // 요청 전화 or 핸드폰 번호
            $expr_dt       = $c_PayPlus->mf_get_res_data( "expr_dt"       );    // 결제 유효기간

            $ordr_idxx     = $c_PayPlus->mf_get_res_data( "ordr_idxx"     );    // 가맹점 주문번호
			$good_name     = $c_PayPlus->mf_get_res_data( "good_name"     );    // 상품명
            $ordr_nm       = $c_PayPlus->mf_get_res_data( "ordr_nm"       );    // 요청자 이름

            $site_nm       = $c_PayPlus->mf_get_res_data( "site_nm"       );    // 가맹점 사이트 명

			$cert_flg      = $c_PayPlus->mf_get_res_data( "cert_flg"      );    // 인증 or 비인증 구분
            $sig_flg       = $c_PayPlus->mf_get_res_data( "sig_flg"       );    // 호전환 구분
            $vnum_no       = $c_PayPlus->mf_get_res_data( "vnum_no"       );    // ARS 결제요청 전화번호

        }    // End of [res_cd = "0000"]

    /* = -------------------------------------------------------------------------- = */
    /* =   04-2. 승인 실패를 업체 자체적으로 DB 처리 작업하시는 부분입니다.         = */
    /* = -------------------------------------------------------------------------- = */
        else
        {

        }
    }
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   05. 폼 구성 및 결과페이지 호출                                           = */
    /* ============================================================================== */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >

<head>
<script type="text/javascript">
    function goResult()
    {
        document.pay_info.submit();
    }
</script>
</head>

<body onload="goResult()">
<form name="pay_info" method="post" action="./result.php">
    <input type="hidden" name="req_tx"        value="<?= $req_tx       ?>">     <!-- 요청 구분 -->
	<input type="hidden" name="pay_method"    value="<?= $pay_method   ?>">     <!-- 요청한 수단 -->

    <input type="hidden" name="res_cd"        value="<?= $res_cd       ?>">     <!-- 결과 코드 -->
    <input type="hidden" name="res_msg"       value="<?= $res_msg      ?>">     <!-- 결과 메세지 -->

    <input type="hidden" name="ordr_idxx"     value="<?= $ordr_idxx    ?>">     <!-- 주문번호 -->
    <input type="hidden" name="phon_mny"      value="<?= $phon_mny     ?>">     <!-- 요청금액 -->
    <input type="hidden" name="good_name"     value="<?= $good_name    ?>">     <!-- 상품명 -->
    <input type="hidden" name="ordr_nm"       value="<?= $ordr_nm      ?>">     <!-- 요청자명 -->

    <input type="hidden" name="ars_trade_no"  value="<?=$ars_trade_no  ?>">     <!-- KCP ARS 등록번호 -->
    <input type="hidden" name="phon_no"       value="<?=$phon_no       ?>">     <!-- 전화번호/핸드폰번호 -->
    <input type="hidden" name="app_time"      value="<?=$app_time      ?>">     <!-- 요청시간 -->
	<input type="hidden" name="expr_dt"       value="<?=$expr_dt       ?>">     <!-- 결제 유효기간 -->

	<input type="hidden" name="site_nm"       value="<?=$site_nm       ?>">     <!-- 결제 사이트명 -->

	<input type="hidden" name="cert_flg"      value="<?=$cert_flg      ?>">     <!-- 인증/비인증 구분 -->
    <input type="hidden" name="sig_flg"       value="<?=$sig_flg       ?>">     <!-- 호전환 구분 -->
    <input type="hidden" name="vnum_no"       value="<?=$vnum_no       ?>">     <!-- ARS 결제요청 전화번호 -->
</form>
</body>
</html>
