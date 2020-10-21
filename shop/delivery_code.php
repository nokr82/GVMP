<?php
//작성자 홍동우 택배사코드 따기


function delivery_code($name)
{
    $s_name = trim($name);
    switch ($s_name){
        case '건영택배':
            $code = '18';
            break;
        case '경동택배':
            $code = '23';
            break;
        case '홈픽택배':
            $code = '54';
            break;
        case '대신택배':
            $code = '22';
            break;
        case '농협택배':
            $code = '53';
            break;
        case '굿투럭':
            $code = '40';
            break;
        case '로젠택배':
            $code = '06';
            break;
        case '롯데택배':
            $code = '08';
            break;
        case '애니트랙':
            $code = '43';
            break;
        case '우체국택배':
            $code = '01';
            break;
        case '건영택배':
            $code = '18';
            break;
        case '일양로지스':
            $code = '11';
            break;
        case '천일택배':
            $code = '17';
            break;
        case '한덱스':
            $code = '20';
            break;
        case '한의사랑택배':
            $code = '16';
            break;
        case '한진택배':
            $code = '05';
            break;
        case 'CJ대한통운':
            $code = '04';
            break;
        case 'KGB택배':
            $code = '56';
            break;
        case '하이택배':
            $code = '58';
            break;
        case '합동택배':
            $code = '32';
            break;
        case 'CU편의점택배':
            $code = '46';
            break;
        case 'KGL네트웍스':
            $code = '30';
            break;
        case 'FLF퍼레버택배':
            $code = '64';
            break;
        case '호남택배':
            $code = '45';
            break;
        case 'CVSnet 편의점택배':
            $code = '24';
            break;
        case 'SLX':
            $code = '44';
            break;
        default:
            $code = '';
            break;

    }

    return $code;
}






?>