<?php

function parsing($val) {
    $strTok = explode(';', $val);

    $re;
    switch ($strTok[0]) {
        case "membershipReward" :
            $re = "플레이콕 회원권구매 리워드({$strTok[1]})";
            break;
        case "sponsReward" :
            $re = "플레이콕 후원 리워드({$strTok[1]})";
            break;
        case "autoVMC" :
            $re = "직급 수당";
            break;
        case "admin" :
            $re = "관리자가 적립";
            break;
        case "admin2" :
            if ($strTok[1] != '') {
                $re = "관리자가 적립({$strTok[1]})";
                break;
            } else {
                $re = "관리자가 적립";
                break;
            }
        case "orderListAdmin" :
            $re = "관리자가 적립";
            break;
        case "orderListAdmin2" :
            $re = "관리자가 적립";
            break;
        case "autoRenewal" :
            $re = "리뉴얼을 통해 적립";
            break;
        case "Renewal" :
            $re = "리뉴얼을 통해 적립";
            break;
        case "calculate" :
            $re = "정산 신청";
            break;
        case "calculateCan" :
            $re = "정산 신청 취소";
            break;
        case "mb_point" :
            $re = "포인트 충전 금액";
            break;
        case "virtual_point" :
            $re = "가상계좌 포인트 충전 금액";
            break;
        case "transfer" :
            $re = $strTok[1];
            break;
        case "businessPack" :
            $re = "비즈니스팩 결제 금액";
            break;
        case "smJoin" :
            $re = "SM 회원비";
            break;
        case "smJoinVM" :
            $re = "SM 캐시백({$strTok[1]})";
            break;
        case "smRenewal" :
            $re = "SM 회원비";
            break;
        case "smRenewalVM" :
            $re = "SM 캐시백({$strTok[1]})";
            break;
        case "smAutoRenewal" :
            $re = "SM 회원비";
            break;
        case "smAutoRenewalVM" :
            $re = "SM 캐시백({$strTok[1]})";
            break;
        case "admin3" :
            $re = "관리자가 적립";
            break;
        case "adminBusinessPack" :
            $re = "비즈니스팩 결제 금액";
            break;
        case "autoPass" :
            $re = "리뉴얼 패스";
            break;
        case "dayPass" :
            $re = "PASS";
            break;
        case "shopPoint" :
            $re = "상품 구매";
            break;
        case "앱올" :
            $re = "애드팩 #5";
            break;
        case "vmJoin" :
            $re = "VM 가입비({$strTok[1]})";
            break;
        case "앱올커미션" :
            $re = "애드팩 #5 커미션({$strTok[1]})";
            break;
        case "애드팩 #6" :
            $re = "애드팩 #6";
            break;
        case "애드팩 #6 커미션" :
            $re = "애드팩 #6 커미션({$strTok[1]})";
            break;
        case "newRenewal" :
            $re = "VM가입";
            break;
        case "sharePoint" :
            $re = "공유 포인트({$strTok[1]})";
            break;
        case "businessPack2" :
            $re = "비즈니스 팩2 결제";
            break;
        case "carPoint" :
            $re = "차량 유지 지원비";
            break;
        case "vmgMove" :
            $re = "금고 자동 적립";
            break;
        case "autoVMM" :
            $re = "VMM 자동 적립";
            break;
        case "arsPay" :
            $re = "ARS 결제({$strTok[1]})";
            break;
        case "qrCodeTransfer_from" :
            $re = "QR코드 결제({$strTok[1]})";
            break;
        case "qrCodeTransfer_to" :
            $re = "QR코드 결제({$strTok[1]})";
            break;
        case "membershipBuy" :
            $re = "간편회원가입권 구매";
            break;
        case "bizMoneyChange" :
            $re = "비즈머니 전환";
            break;
        case "usedBuy" :
            $re = "중고장터 구매({$strTok[1]})";
            break;
        case "usedSale" :
            $re = "중고장터 판매({$strTok[1]})";
            break;
        case "usedRefund" :
            $re = "중고장터 환불({$strTok[1]})";
            break;
        case "billPoint" :
            $re = "벤더맵 영수증";
            break;
        case "attendPoint" :
            $re = "출석체크";
            break;
        case "attendPointBonus" :
            $re = "출석체크 보너스";
            break;
        case "shopPointCashBack" :
            $re = "상품구매 캐시백";
            break;
        case "앱올" :
            $re = "애드팩";
            break;
        case "애드씽크" :
            $re = "애드팩";
            break;
        case "나스" :
            $re = "애드팩";
            break;
        case "티엔케이" :
            $re = "애드팩";
            break;
        case "애드팝콘" :
            $re = "애드팩";
            break;
        case "애드팩 #6" :
            $re = "애드팩";
            break;
        case "앱올커미션" :
            $re = "애드팩 간접수익";
            break;
        case "애드씽크커미션" :
            $re = "애드팩 간접수익";
            break;
        case "나스커미션" :
            $re = "애드팩 간접수익";
            break;
        case "티엔케이커미션" :
            $re = "애드팩 간접수익";
            break;
        case "애드팝콘커미션" :
            $re = "애드팩 간접수익";
            break;
        case "애드팩 #6 커미션" :
            $re = "애드팩 간접수익";
            break;
        case "5STARBonus" :
            $re = "5 STAR 달성 보너스";
            break;
        case "forceRenewal" :
            $re = "관리자 강제 리뉴얼";
            break;
        case "usageFee" :
            $re = "전산사용료";
            BREAK;
    }
    return $re;
}

?>
