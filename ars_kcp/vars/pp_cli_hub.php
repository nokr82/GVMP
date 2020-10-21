<?
    /* ============================================================================== */
    /* = �� ��û �� ���ó�� ������ ��                                              = */
    /* ------------------------------------------------------------------------------ */
    /* = Copyright(c) 2012 KCP Inc. All Rights Reserved.                            = */
    /* ============================================================================== */
?>

<?
    /* ============================================================================== */
    /* = ���̺귯�� �� ����Ʈ ���� include                                          = */
    /* = -------------------------------------------------------------------------- = */

    require "./pp_cli_hub_lib.php";
    require "/ars_kcp/cfg/site_conf_inc.php" ;

    /* ============================================================================== */


    /* ============================================================================== */
    /* =   01. ���� ��û ���� ����                                                  = */
    /* = -------------------------------------------------------------------------- = */
	$pay_method       = $_POST[ "pay_method"       ];       // ���� ���
    $ordr_idxx        = $_POST[ "ordr_idxx"        ];       // �ֹ� ��ȣ
    $phon_mny         = $_POST[ "phon_mny"         ];       // ���� �ݾ�
    /* = -------------------------------------------------------------------------- = */
    $good_name        = $_POST[ "good_name"        ];       // ��ǰ ����
	$buyr_name        = $_POST[ "buyr_name"        ];       // �ֹ��� �̸�
    $soc_no           = $_POST[ "soc_no"           ];       // �ֹε�Ϲ�ȣ
    /* = -------------------------------------------------------------------------- = */
    $req_tx           = $_POST[ "req_tx"           ];       // ��û ����
    $quota            = $_POST[ "quota"            ];       // �Һΰ��� ��
	/* = -------------------------------------------------------------------------- = */
    $comm_id          = $_POST[ "comm_id"          ];       // �̵���Ż��ڵ�
    $phon_no          = $_POST[ "phon_no"          ];       // ��ȭ��ȣ
	$call_no          = $_POST[ "call_no"          ];       // ���� ��ȭ��ȣ
    $expr_dt          = $_POST[ "expr_dt"          ];       // ���� ��ȿ�Ⱓ
    /* = -------------------------------------------------------------------------- = */
    $tx_cd            = "";                                 // Ʈ����� �ڵ�
    /* = -------------------------------------------------------------------------- = */
	$res_cd           = "";                                 // ����ڵ�
    $res_msg          = "";                                 // ����޽���
	$ars_trade_no     = "";                                 // �����ŷ���ȣ
    $app_time         = "";                                 // ó���ð�
	/* = -------------------------------------------------------------------------- = */
    $card_no          = "";                                 // ī���ȣ
	$card_expiry      = "";                                 // ī����ȿ�Ⱓ
    $card_quota       = "";                                 // ī���Һΰ���
	/* = -------------------------------------------------------------------------- = */
    $ars_tx_key       = "";                                 // ARS�ֹ���ȣ(ARS�ŷ�Ű)
	$ordr_nm          = "";                                 // ��û�� �̸�
    $site_nm          = "";                                 // ��û ����Ʈ��
    /* = -------------------------------------------------------------------------- = */
    $cert_flg         = $_POST[ "cert_flg"          ];      // ���� ������ ����
    $sig_flg          = "";                                 // ȣ��ȯ ����
    $vnum_no          = "";                                 // ARS ������û ��ȭ��ȣ
    /* = -------------------------------------------------------------------------- = */
	$ars_trade_no     = $_POST[ "ars_trade_no"      ];      // ARS ��� �ŷ���ȣ
    /* ============================================================================== */

    /* ============================================================================== */
    /* =   02. �ν��Ͻ� ���� �� �ʱ�ȭ                                              = */
    /* = -------------------------------------------------------------------------- = */
    $c_PayPlus  = new C_PAYPLUS_CLI;
    $c_PayPlus->mf_clear();
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   03. ó�� ��û ���� ����, ����                                            = */
    /* = -------------------------------------------------------------------------- = */

    /* = -------------------------------------------------------------------------- = */
    /* =   03-1. ���� ��û                                                          = */
    /* = -------------------------------------------------------------------------- = */
    // ��ü ȯ�� ����
    $cust_ip    = getenv( "REMOTE_ADDR" );


    // �ŷ� ��� ��û ��
    if ( $req_tx == "pay"  )
    {
        $tx_cd = "00100700";
        

        // ���� ����
        $common_data_set = "";
    
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "amount"   , $phon_mny    );
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "cust_ip"  , $cust_ip  );

		$c_PayPlus->mf_add_payx_data( "common", $common_data_set );

        // �ֹ� ����

        $c_PayPlus->mf_set_ordr_data( "ordr_idxx",  $ordr_idxx );  // �ֹ� ��ȣ
        $c_PayPlus->mf_set_ordr_data( "good_name",  $good_name );  // ��ǰ ����
        $c_PayPlus->mf_set_ordr_data( "buyr_name",  $buyr_name );  // �ֹ��� �̸�

        // ��û ����
     	$phon_data_set  = "";

        $phon_data_set .= $c_PayPlus->mf_set_data_us( "phon_mny", $phon_mny );  // ��û�ݾ�
        $phon_data_set .= $c_PayPlus->mf_set_data_us( "phon_no",  $phon_no  );  // ��û ��ȭ��ȣ
        $phon_data_set .= $c_PayPlus->mf_set_data_us( "comm_id",  $comm_id  );  // �̵���Ż� �ڵ�

        if (!expr_dt == "")
		{
			$phon_data_set .= $c_PayPlus->mf_set_data_us( "expr_dt",  $expr_dt  );  // ���� ��ȿ�Ⱓ
		}

		// ��û���ܿ� ���� ����

		if ($pay_method == "VARS")
		{
			$phon_data_set .= $c_PayPlus->mf_set_data_us( "phon_txtype",  "11600000"  );  // �������� ����
            $phon_data_set .= $c_PayPlus->mf_set_data_us( "cert_flg",  $cert_flg      );  // ����, ������ ����
		}

		$c_PayPlus->mf_add_payx_data( "phon",  $phon_data_set );

	}

    // �ŷ� ��� ��� ��û ��
	if ( $pay_method == "STRC")
	{
		$tx_cd = "00100700";

		// ���� ����
        $common_data_set = "";
    
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "amount"   , "0"       );
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "cust_ip"  , $cust_ip  );

		$c_PayPlus->mf_add_payx_data( "common", $common_data_set );


        // ��û ���ܿ� ���� ����Ÿ�� ����

		$phon_data_set .= $c_PayPlus->mf_set_data_us( "phon_txtype",  "13200000"      );  // �������� ����
        $phon_data_set .= $c_PayPlus->mf_set_data_us( "ars_trade_no",  $ars_trade_no  );  // ARS ��� �ŷ���ȣ

		$c_PayPlus->mf_add_payx_data( "phon",  $phon_data_set );
	}

	// ������ ����ڿ��� SMS �߼� ��

	if ( $pay_method == "OSMS" )
	{
		$tx_cd = "00100700";

		// ���� ����
        $common_data_set = "";
    
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "amount"   , "0"       );
    	$common_data_set .= $c_PayPlus->mf_set_data_us( "cust_ip"  , $cust_ip  );

		$c_PayPlus->mf_add_payx_data( "common", $common_data_set );

        // ��û ���ܿ� ���� ����Ÿ�� ����

		$phon_data_set .= $c_PayPlus->mf_set_data_us( "phon_txtype",  "13300000"      );  // �������� ����
        $phon_data_set .= $c_PayPlus->mf_set_data_us( "ars_trade_no",  $ars_trade_no  );  // ARS ��� �ŷ���ȣ

		$c_PayPlus->mf_add_payx_data( "phon",  $phon_data_set );
	}




    /* ============================================================================== */

	/* = -------------------------------------------------------------------------- = */
    /* =   03-2. ���/���� ��û                                                     = */
    /* = -------------------------------------------------------------------------- = */
        else if ( $req_tx == "mod" )
        {
            $mod_type = $_POST[ "mod_type" ];

            $tx_cd = "00200000";

            $c_PayPlus->mf_set_modx_data( "tno",      $_POST[ "tno" ]      );      // KCP ���ŷ� �ŷ���ȣ
            $c_PayPlus->mf_set_modx_data( "mod_type", $mod_type            );      // ���ŷ� ���� ��û ����
            $c_PayPlus->mf_set_modx_data( "mod_ip",   $cust_ip             );      // ���� ��û�� IP
            $c_PayPlus->mf_set_modx_data( "mod_desc", $_POST[ "mod_desc" ] );      // ���� ����
        }
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   03-3. ����                                                               = */
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
        $c_PayPlus->m_res_msg = "���� ����";
    }
    $res_cd  = $c_PayPlus->m_res_cd;                      // ��� �ڵ�
    $res_msg = $c_PayPlus->m_res_msg;                     // ��� �޽���
   /* ============================================================================== */


    /* ============================================================================== */
    /* =   04. ���� ��� ó��                                                       = */
    /* = -------------------------------------------------------------------------- = */
    if ( $req_tx ==  "pay"  )
    {
        if ( $res_cd == "0000"  )
        {
    /* = -------------------------------------------------------------------------- = */
    /* =   04-1. ��û ��� ����                                                     = */
    /* = -------------------------------------------------------------------------- = */
            $ars_trade_no  = $c_PayPlus->mf_get_res_data( "ars_trade_no"  );    // ARS ��Ϲ�ȣ
            $app_time      = $c_PayPlus->mf_get_res_data( "app_time"      );    // ��û �ð�
            $phon_mny      = $c_PayPlus->mf_get_res_data( "phon_mny"      );    // ��û �ݾ�
			$phon_no       = $c_PayPlus->mf_get_res_data( "phon_no"       );    // ��û ��ȭ or �ڵ��� ��ȣ
            $expr_dt       = $c_PayPlus->mf_get_res_data( "expr_dt"       );    // ���� ��ȿ�Ⱓ

            $ordr_idxx     = $c_PayPlus->mf_get_res_data( "ordr_idxx"     );    // ������ �ֹ���ȣ
			$good_name     = $c_PayPlus->mf_get_res_data( "good_name"     );    // ��ǰ��
            $ordr_nm       = $c_PayPlus->mf_get_res_data( "ordr_nm"       );    // ��û�� �̸�

            $site_nm       = $c_PayPlus->mf_get_res_data( "site_nm"       );    // ������ ����Ʈ ��

			$cert_flg      = $c_PayPlus->mf_get_res_data( "cert_flg"      );    // ���� or ������ ����
            $sig_flg       = $c_PayPlus->mf_get_res_data( "sig_flg"       );    // ȣ��ȯ ����
            $vnum_no       = $c_PayPlus->mf_get_res_data( "vnum_no"       );    // ARS ������û ��ȭ��ȣ

        }    // End of [res_cd = "0000"]

    /* = -------------------------------------------------------------------------- = */
    /* =   04-2. ���� ���и� ��ü ��ü������ DB ó�� �۾��Ͻô� �κ��Դϴ�.         = */
    /* = -------------------------------------------------------------------------- = */
        else
        {

        }
    }
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   05. �� ���� �� ��������� ȣ��                                           = */
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
    <input type="hidden" name="req_tx"        value="<?= $req_tx       ?>">     <!-- ��û ���� -->
	<input type="hidden" name="pay_method"    value="<?= $pay_method   ?>">     <!-- ��û�� ���� -->

    <input type="hidden" name="res_cd"        value="<?= $res_cd       ?>">     <!-- ��� �ڵ� -->
    <input type="hidden" name="res_msg"       value="<?= $res_msg      ?>">     <!-- ��� �޼��� -->

    <input type="hidden" name="ordr_idxx"     value="<?= $ordr_idxx    ?>">     <!-- �ֹ���ȣ -->
    <input type="hidden" name="phon_mny"      value="<?= $phon_mny     ?>">     <!-- ��û�ݾ� -->
    <input type="hidden" name="good_name"     value="<?= $good_name    ?>">     <!-- ��ǰ�� -->
    <input type="hidden" name="ordr_nm"       value="<?= $ordr_nm      ?>">     <!-- ��û�ڸ� -->

    <input type="hidden" name="ars_trade_no"  value="<?=$ars_trade_no  ?>">     <!-- KCP ARS ��Ϲ�ȣ -->
    <input type="hidden" name="phon_no"       value="<?=$phon_no       ?>">     <!-- ��ȭ��ȣ/�ڵ�����ȣ -->
    <input type="hidden" name="app_time"      value="<?=$app_time      ?>">     <!-- ��û�ð� -->
	<input type="hidden" name="expr_dt"       value="<?=$expr_dt       ?>">     <!-- ���� ��ȿ�Ⱓ -->

	<input type="hidden" name="site_nm"       value="<?=$site_nm       ?>">     <!-- ���� ����Ʈ�� -->

	<input type="hidden" name="cert_flg"      value="<?=$cert_flg      ?>">     <!-- ����/������ ���� -->
    <input type="hidden" name="sig_flg"       value="<?=$sig_flg       ?>">     <!-- ȣ��ȯ ���� -->
    <input type="hidden" name="vnum_no"       value="<?=$vnum_no       ?>">     <!-- ARS ������û ��ȭ��ȣ -->
</form>
</body>
</html>
