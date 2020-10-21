<?php
    include_once('./inc/title.php');
    include_once('./dbConn.php');
    include_once('./_common.php');
    include_once('../shop/shop.head.php');
    include_once('iamport.php');
?>


</head>

<style>
    #success {margin:100px 0}
    #success h2{font-size:28px; text-align: center}
    #suc {margin-top:100px}
    #success div{text-align:center; line-height:39px; }
    #success div strong {font-size:20px}
    #success div img {width:50px}
    #success button{padding:10px 20px; border:2px solid #f19c9c; background:#fff; border-radius:5px; color:#f19c9c; font-size:16px; transition:.3s; margin:80px 0 100px 0}
    #success button:hover {background:#f19c9c; color:#fff}
</style>

<div id="success">
	<h2>결제 정보</h2>
	<div id="suc">
		
		
                
<?php
    if( $_GET['imp_success'] == "false" ) {
        header("Location:/myOffice/index.php"); // 모바일에서 사용자가 결제창 닫았다면 mysoop 페이지로 리다이렉트 시키는 코드
    }

    if( $_GET['m_payment'] == "Y" ) {
        $iamport = new Iamport('3473082324679181', '6eYEkASG0CZ4thoHH5pFdiP5UNqzjo0pDZ4XAXEAzEF3W3KD99imr8smVTVfiWFVWKTwHx7gcMj8Q4MZ');
        #1. imp_uid 로 주문정보 찾기(아임포트에서 생성된 거래고유번호)
        $result = $iamport->findByImpUID($_GET['imp_uid']); //IamportResult 를 반환(success, data, error)

        if ( $result->success ) {
            $payment_data = $result->data;

            // 결제 정보 변수 세팅
            $pay_method = $payment_data->pay_method; // 결제 수단 [	card(신용카드), trans(실시간계좌이체), vbank(가상계좌)  ]
            $paid_amount = $payment_data->amount; // 결제 금액
            $pg_tid = $payment_data->pg_tid; // PG사 거래 고유번호
            $vbank_num = $payment_data->vbank_num; // 가상계좌 계좌번호
            $vbank_name = $payment_data->vbank_name; // 가상계좌 은행명
            $vbank_holder = $payment_data->vbank_holder; // 가상계좌 예금주
            $vbank_date = date("Y-m-d", $payment_data->vbank_date); // 가상계좌 입금 기한
        } else {
                error_log($result->error['code']);
                error_log($result->error['message']);
        }
    } else {
        // 결제 정보 변수 세팅
        $pay_method = $_GET['pay_method']; // 결제 수단 [	card(신용카드), trans(실시간계좌이체), vbank(가상계좌)  ]
        $paid_amount = $_GET['paid_amount']; // 결제 금액
        $pg_tid = $_GET['pg_tid']; // PG사 거래 고유번호
        $vbank_num = $_GET['vbank_num']; // 가상계좌 계좌번호
        $vbank_name = $_GET['vbank_name']; // 가상계좌 은행명
        $vbank_holder = $_GET['vbank_holder']; // 가상계좌 예금주
        $vbank_date = $_GET['vbank_date']; // 가상계좌 입금 기한
    }


    if( $pay_method != "vbank" ) { // 결제 수단에 따른 타이틀 결정. 결제수단이 가상계좌가 아니면 결제가 성공한 것임.
        echo "<img src=\"images/success.png\"> <strong>결제가 성공적으로 완료되었습니다.</strong><br>";
    } else {
        echo "<strong>입금을 완료하시면 결제가 완료됩니다.</strong><br>";
    }
    
    if( $pay_method == "card" ) { // 카드 결제일 때
        
        echo "결제 금액 : " . number_format($paid_amount) . "원<br>";
        
    } else if( $pay_method == "trans" ) { // 계좌이체 결제일 때
        
        echo "결제 금액 : " . number_format($paid_amount) . "원<br>";
        
    } else if( $pay_method == "vbank" ) { // 가상계좌 결제일 때
        
        echo "결제 금액 : " . number_format($paid_amount) . "원<br>";
        echo "PG사 거래 고유번호 : " . $pg_tid . "<br>";
        echo "가상계좌 계좌번호 : " . $vbank_num . "<br>";
        echo "가상계좌 은행명 : " . $vbank_name . "<br>";
        echo "가상계좌 예금주 : " . $vbank_holder . "<br>";
        echo "가상계좌 입금 기한 : " . $vbank_date . "<br>";
        
    }
?>
                
                
                
                
                
		<div>
                    <a href="/myOffice/index.php"><button>이전 페이지</button></a>
		</div>
	</div>
</div>

<?php
    include_once('../shop/shop.tail.php');
?>