<?php


    function parsing2( $val ) {
        $strTok =explode(';' , $val);
            
        $re;
        switch( $strTok[0] ) {
            case "billPoint" :
                $re = "벤더맵 영수증"; break;
            case "attendPoint" :
                $re = "출석체크"; break;
            case "attendPointBonus" :
                $re = "출석체크 보너스"; break;
            case "shopPointCashBack" :
                $re = "상품구매 캐시백"; break;
            case "앱올" :
                $re = "애드팩"; break;
            case "애드씽크" :
                $re = "애드팩"; break;
            case "나스" :
                $re = "애드팩"; break;
            case "티엔케이" :
                $re = "애드팩"; break;
            case "애드팝콘" :
                $re = "애드팩"; break;
            case "애드팩 #6" :
                $re = "애드팩"; break;
            case "앱올커미션" :
                $re = "애드팩 간접수익"; break;
            case "애드씽크커미션" :
                $re = "애드팩 간접수익"; break;
            case "나스커미션" :
                $re = "애드팩 간접수익"; break;
            case "티엔케이커미션" :
                $re = "애드팩 간접수익"; break;
            case "애드팝콘커미션" :
                $re = "애드팩 간접수익"; break;
            case "애드팩 #6 커미션" :
                $re = "애드팩 간접수익"; break;
        }
        return $re;
    }

?>
