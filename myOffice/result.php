<?php
    include_once('dbConn.php');
    include_once('iamport.php');
    
//    $value = json_decode(file_get_contents('php://input'), true);
    

    
    $iamport = new Iamport('3473082324679181', '6eYEkASG0CZ4thoHH5pFdiP5UNqzjo0pDZ4XAXEAzEF3W3KD99imr8smVTVfiWFVWKTwHx7gcMj8Q4MZ');
    #1. imp_uid 로 주문정보 찾기(아임포트에서 생성된 거래고유번호)
    $result = $iamport->findByImpUID($_POST['imp_uid']); //IamportResult 를 반환(success, data, error)

    if ( $result->success ) {
            /**
            *   IamportPayment 를 가리킵니다. __get을 통해 API의 Payment Model의 값들을 모두 property처럼 접근할 수 있습니다.
            *   참고 : https://api.iamport.kr/#!/payments/getPaymentByImpUid 의 Response Model
            */
            $payment_data = $result->data;
            echo '## 결제정보 출력 ##';
            echo '가맹점 주문번호 : '    . $payment_data->merchant_uid;
            echo '결제상태 : '       . $payment_data->status;
            echo '결제금액 : '       . $payment_data->amount;
            echo '결제수단 : '       . $payment_data->pay_method;
            echo '결제된 카드사명 : '    . $payment_data->card_name;
            echo '결제 매출전표 링크 : '   . $payment_data->receipt_url;
            /**
            *   IMP.request_pay({
            *      custom_data : {my_key : value}
            *   });
            *   와 같이 custom_data를 결제 건에 대해서 지정하였을 때 정보를 추출할 수 있습니다.(서버에는 json encoded형태로 저장합니다)
            */
            echo 'Custom Data :'   . $payment_data->getCustomData('my_key');
            # 내부적으로 결제완료 처리하시기 위해서는 (1) 결제완료 여부 (2) 금액이 일치하는지 확인을 해주셔야 합니다.

            if ( $payment_data->status === 'paid' ) {
                    //TODO : 결제성공 처리

                $pay_method = $payment_data->pay_method;
                $amount = $payment_data->amount;
                $buyer_postcode = $payment_data->buyer_postcode; // 여기에 결제자 아이디가 담김
                mysql_query("update g5_member set VMP = VMP + {$amount} where mb_id = '{$buyer_postcode}'");
                mysql_query("insert into dayPoint set mb_id = '{$buyer_postcode}', VMC = 0, VMR = 0, VMP = {$amount}, date = NOW(), way = 'mb_point'");
            }
    } else {
            error_log($result->error['code']);
            error_log($result->error['message']);
    }
?>