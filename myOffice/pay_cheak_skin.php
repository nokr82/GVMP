<link rel="shortcut icon" href="/img/vmp_logo.ico"/>
<link rel="stylesheet" href="../font/nanumsquare.css"/>
<link rel="stylesheet" href="css/animate.css"/>
<script src="./js/wow.min.js"/></script>
<?php
include_once('./_common.php');
include_once('./dbConn.php');

if ($member['settle_yn'] != 'Y'){//권한설정 0622
    alert('접근권한이 없습니다.');
    exit();
}


$row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'"));
$row2 = mysql_fetch_array(mysql_query("select * from memberRRNTBL where mb_id = '{$member['mb_id']}'"));
$idCertificateRow = mysql_fetch_array(mysql_query("select * from idCertificateTBL where mb_id = '{$member["mb_id"]}'"));

$bankName = $row['bankName']; // 은행명
$accountHolder = $row['accountHolder']; // 예금주
$accountNumber = $row['accountNumber']; // 계좌번호
$name = $idCertificateRow["name"]; // 수당신청 이름
$RRN_F = $idCertificateRow["RRN1"]; // 주민번호 앞
$RRN_B = $idCertificateRow["RRN2"]; // 주민번호 뒤

$date1 = date("Y-m-01");
$date2 = date("Y-m-02");
$row3 = mysql_fetch_array(mysql_query("select * from retentionPointTBL where mb_id = '{$member['mb_id']}' and date >= '{$date1}' and date < '{$date2}'"));

if ($row3['VMP'] == "") {
    $row3['VMP'] = "0";
}
echo "<input type=\"hidden\" id=\"possibleVMC\" value=\"{$row['VMC']}\"><input type=\"hidden\" id=\"possibleVMP\" value=\"{$row3['VMP']}\">";

?>




<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet" href="./css/pay_cheak_skin.css" />
<script  src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="js/pay_cheak_skin.js"></script>



<title>정산신청</title>
</head>
<body>
<?php
include_once('../shop/shop.head.php');
?>
<div class="pay_math_all">
    <h1 class="pay_no1">정산 신청</h1>

    <div class="certify_idcard_wrap">
        <div class="step_alert">

            <?php
            if ($idCertificateRow["ok"] == "") {
                ?>
                <div class="step_1">
                    <span class="wow flip">미등록</span>
                    <p class="wow fadeInDown">신분증을 등록해주세요.</p>
                </div>
                <?php
            } else if ($idCertificateRow["ok"] == "W") {
                ?>
                <div class="step_2">
                    <span class="wow flip">승인대기</span>
                    <p class="wow fadeInDown">승인 요청 중 입니다.</p>
                </div>
                <?php
            } else if ($idCertificateRow["ok"] == "Y") {
                ?>
                <div class="step_3">
                    <span class="wow flip">승인완료</span>
                    <p class="wow fadeInDown">승인이 완료되었습니다.</p>
                </div>
                <?php
            } else if ($idCertificateRow["ok"] == "N") {
                ?>
                <div class="step_4">
                    <span class="wow flip">승인반려</span>
                    <p class="wow fadeInDown"><?= $idCertificateRow["comm"] ?></p>
                </div>
                <?php
            }
            ?>


        </div>
        <form method="POST" enctype="multipart/form-data" onSubmit="return formCheck()">
            <input type="hidden" name="mb_id" value="<?= $member["mb_id"] ?>"/>
            <div class="idcard_content wow fadeInDown">
                <?php
                if ($idCertificateRow["ok"] == "Y") {//승인 완료 시, (저장용량 초과로 인한 액박 이미지 보완)
                ?>
                <div class="reset_cnxl_btn_area" onclick="reset_idcard()">
                    <span class="reset_cnxl_btn">신분증 재인증 취소하기</span>
                </div>
                <div class="complete_imgbox">
                    <img class="complete_img" src="./images/complete_idcard.png"
                         title="승인 완료되었습니다. 다시 승인 받으시려면 새로 등록해주세요."/>
                    <?php
                    } else {//승인 완료 아닐 때
                    ?>
                    <div class="img_container">

                        <img id="idCertificateImage" src="<?php if ($idCertificateRow["image"] != "") {
                            echo "/up/idCertificate/" . $idCertificateRow["image"];
                        } else {
                            echo "./images/img_certify_idcard_3.png";
                        } ?>" alt="신분증 사진"/>
                        <?php
                        }
                        ?>
                    </div>
                    <?php
                    if ($idCertificateRow["ok"] == "Y") {//승인 완료 시
                        ?>
                        <div class="reset_btn_area" onclick="reset_idcard('on')">
                            <span class="reset_btn">신분증 재인증 시작하기</span>
                        </div>

                        <?php
                    }
                    ?>
                    <div class="txt_container img_file_up">
                        <p class="text_info caution">신분증 사본은 내용이 식별 가능하게 촬영해주세요.</p>
                        <label for="img_file">첨부파일 등록</label>
                        <input type="file" name="img_file" id="img_file" accept="image/*">
                    </div>
                </div>


                <div class="idcard_info wow fadeInDown">

                    <ul>
                        <li>
                            <input type="text" name="mb_name" class="input_text" id="mb_name"
                                   value="<?= $idCertificateRow["name"] ?>" style="ime-mode:active"
                                   placeholder="이름"
                                   required>
                        </li>
                        <li class="clearfix">
                            <input type="text" id="mb_unum_f" name="mb_unum_f"
                                   class="input_text_left input_text"
                                   value="<?= $idCertificateRow["RRN1"] ?>" placeholder="주민번호 앞자리" maxlength="6"
                                   onKeyDown="return onlyNumber(event)" required>
                            <p class="unum_m">-</p>
                            <input type="text" id="mb_unum_b" name="mb_unum_b"
                                   class="input_text_right input_text"
                                   value="<?= $idCertificateRow["RRN2"] ?>" placeholder="주민번호 뒷자리" maxlength="7"
                                   onKeyDown="return onlyNumber(event)" required>
                        </li>
                        <li>
                            <p class="text_info caution">내국인, 외국인 여부를 선택해주세요.</p>
                            <input type="radio" name="resident_type" id="local"
                                   value="내국인" <?php if ($idCertificateRow["DnF"] == "D") {
                                echo "checked='true'";
                            } ?> required>
                            <label for="local">내국인</label>
                            <input type="radio" name="resident_type" id="foreigner"
                                   value="외국인" <?php if ($idCertificateRow["DnF"] == "F") {
                                echo "checked='true'";
                            } ?>>
                            <label for="foreigner">외국인</label>
                        </li>
                    </ul>
                    <input type="submit" class="pay_btn" value="승인요청">
                </div>
            </div>
        </form>
    </div>


    <form action="applyForSettlement.php" method="POST" id="form_2">
        <div class="form_pay form01">
            <div class="form_pay_cover">
                <p>관리자의 승인 후 정산을 신청하실 수 있습니다.</p>
            </div>
            <p class="text_info caution">회원명과 예금주는 동일해야 하며, 입력한 주민번호로 소득이 반영되므로 정확하게 입력 해 주셔야 하고, 틀릴 경우 정산이 되지 않을 수
                있습니다.</p>
            <h2 class="pay_no2 pay_1">개인 정보 입력</h2>
            <input type="text" id="name" name="name" class="input_text" readonly placeholder="이름" value="<?= $name ?>">
            <div class="usernum clearfix">
                <input type="text" id="unum_f" name="unum_f" readonly class="input_text_left input_text"
                       value="<?= $RRN_F ?>" placeholder="주민번호 앞자리" maxlength="6" onKeyDown="return onlyNumber(event)"
                       onKeyUp="removeChar(event)">
                <p class="unum_m">-</p>
                <input type="text" id="unum_b" name="unum_b" readonly class="input_text_right input_text"
                       value="<?= $RRN_B ?>" placeholder="주민번호 뒷자리" maxlength="7" onKeyDown="return onlyNumber(event)"
                       onKeyUp="removeChar(event)">

            </div>
            <div class="theme_text tt_1">
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;이름과 주민번호를 잘 못
                    기입할 시 정산 처리가 늦어질 수 있습니다.</p>
            </div>

            <h2 class="pay_no2 pay_2">계좌 정보 입력</h2>
            <input type="text" id="bank_name" name="bank_name" readonly class="input_text" placeholder="은행명"
                   value="<?= $bankName ?>">
            <input type="text" id="bank_user" name="bank_user" readonly class="input_text" placeholder="예금주"
                   value="<?= $accountHolder ?>">
            <input type="text" id="bank_num" name="bank_num" readonly class="input_text" placeholder="계좌번호(숫자만입력)"
                   value="<?= $accountNumber ?>" onKeyDown="return onlyNumber(event)" onKeyUp="removeChar(event)">

            <div class="theme_text tt_2">
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;계좌 예금주와 신청인 이름이
                    다를 경우 정산 처리가 늦어질 수 있습니다.</p>
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;회원가입시 입력한 계좌 정보로
                    정산이 진행되며 계좌정보 변경은 마이오피스에서 진행할 수 있습니다.</p>
            </div>
            <button type="button" id="next1" class="pay_btn">다음</button>
        </div>

        <div class="form_pay form02">
            <h2 class="pay_no2 pay_3">신청 종류 선택</h2>
            <div>
                <label for="point_c"><input type="radio" id="point_c" name="point_type" class="point_type radio_css"
                                            value="C포인트"><span class="point_c">C 포인트 신청</span></label>
                <label for="point_c_fales"><input type="radio" id="point_c_fales" name="point_type"
                                                  class="point_type radio_css" value="C포인트 신청 취소"><span
                            class="point_c_fales">C 포인트 신청 취소</span></label>
            </div>
            <!--p포인트 신청 잠시 주석처리-->
            <!--<div style="display: none;">-->
            <div>
                <label style="display: none;" for="point_p"><input style="display: none;" type="radio" id="point_p"
                                                                   name="point_type" class="point_type radio_css"
                                                                   value="P포인트"><span
                            class="point_p">P 포인트 신청</span></label>
                <!-- 191209 주석처리

<label for="point_p_fales"><input type="radio" id="point_p_fales" name="point_type" class="point_type radio_css" value="P포인트 신청 취소"><span class="point_p_fales">P 포인트 신청 취소</span></label>

-->
            </div>
            <div class="theme_text tt_3">
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;1일~15일 정산 신청시
                    25일 입금됩니다.</p>
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;보유하고 있는 C포인트 전액
                    신청 가능합니다.</p>
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;소득세 3.3% 원천공제 후
                    지급되며 10원 단위는 절삭 처리됩니다.</p>
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;정산 신청한 금액을 변경을
                    원하실 경우 정산 신청을 취소 후 재 신청 바랍니다.</p>
            </div>
            <div class="theme_text tt_4">
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;1일~15일 정산 신청이
                    가능하며 신청시 25일 입금됩니다.</p>
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;전월 말일 24:00까지
                    적립된 P포인트만 정산 신청할 수 있습니다.</p>
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;소득세 3.3% 원천공제 후
                    지급되며 10원 단위는 절삭 처리됩니다.</p>
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;정산 신청한 금액 변경을
                    원하실 경우 정산 신청을 취소 후 재 신청 바랍니다.</p>
            </div>
            <div class="theme_text tt_5">
                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;1일~15일 신청한 내역 정산
                    취소는 1일~15일에만 가능합니다.</p>
<!--                <p class="text_info"><img src="/myOffice/images/exclamation-mark.png" alt="info"/>&nbsp;16일~말일 신청한 내역 정산
                    취소는 16일~말일에만 가능합니다.</p>-->
            </div>
            <input id="ck_point" type="hidden">
        </div>
        <div class="form_pay form03">
            <h2 class="pay_no2 pay_4">신청 금액 입력</h2>

            <div class="day_pay clearfix"><p class="text_left">출금 신청 가능금액</p><input type="text" id="day_pay"
                                                                                    name="day_pay"
                                                                                    class="input_text input_text_right_2"
                                                                                    readonly>
                <p class="text_right">원</p></div>

            <button type="button" id="all_pay" class="pay_btn">전액 신청</button>

            <div class="math_pay clearfix"><input type="text" id="last_pay_in" name="last_pay_in"
                                                  class="input_text input_text_left_1" placeholder="신청금액입력"
                                                  onKeyDown="return onlyNumber(event)" onKeyUp="math(event)"
                                                  onBlur="payCheck()"></div>

            <div class="last_pay clearfix"><p class="text_left">입금예정금액</p><input type="text" id="last_pay"
                                                                                 name="last_pay"
                                                                                 class="input_text input_text_right_2 "
                                                                                 readonly>
                <p class="text_right">원</p></div>
        </div>

        <div class="form_pay form04">
            <button type="button" id="prev1" class="pay_btn">이전</button>
            <button type="submit" id="submit" class="pay_btn">완료</button>
        </div>
    </form>
</div>


<?php
include_once('./alert.php');
?>

<script>
    // 애니메이션 효과
    new WOW().init();


    $('.img_container, .complete_imgbox').on('click', function () {
        $('#img_file').click();
    });





    //사진 미리보기
    function advReadURL(input, obj) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.img_container, .complete_imgbox').find("img").attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('.txt_container').find('input[type=file]').change(function () {
        advReadURL(this, $(this));
    });

    window.onload = function () {
        if ('<?=$idCertificateRow["ok"]?>' == 'Y'){
            reset_idcard();
        }
    };


    //승인 완료일 때 입력 막기
    function reset_idcard(onon) {
        if (onon) {

            $('.idcard_info').show().find('input').each(function () {
                $(this).prop('disabled', false);
            });
            $('.img_file_up').show().find('input').prop('disabled', false);

            $('.complete_imgbox').removeClass('off');

            $('.reset_btn_area').hide();
            $('.reset_cnxl_btn_area').show();

        } else if (!onon) {
            $('.idcard_info').hide();

            $('.img_file_up').hide();

            $('.complete_imgbox').addClass('off');
            $('.reset_cnxl_btn_area').hide();
            $('.reset_btn_area').show();


        }
    }


    // ie 일 때 Object fit 효과
    // css
    // .custom-object-fit {position: relative; background-size: cover;  background-position: center center;}
    // .custom-object-fit img {opacity: 0;}

    //
    //$(function(){
    //    var userAgent, ieReg, ie;
    //    userAgent = window.navigator.userAgent;
    //    ieReg = /msie|Trident.*rv[ :]*11\./gi;
    //    ie = ieReg.test(userAgent);
    //
    //    if(ie) {
    //    $(".img_container").each(function () {
    //        var $container = $(this),
    //            imgUrl = $container.find("img").prop("src");
    //        if (imgUrl) {
    //        $container.css("backgroundImage", 'url(' + imgUrl + ')').addClass("custom-object-fit");
    //        }
    //    });
    //    }
    //
    //});


    //주민등록번호 입력시 포커스 이동
    $('.idcard_info #mb_unum_f').on('keyup', function () {
        if ($(this).val().length == 6) {
            $('.idcard_info #mb_unum_b').focus();
        }
    });



    function formCheck() {
        if ($('#idCertificateImage').attr("src") == './images/img_certify_idcard_3.png') {
            alert('신분증 사본 파일을 등록해주세요');
            return false;
        }




        var form = $('form')[1];
        var data = new FormData(form);


        $.ajax({
            url        : 'idCertificate_back.php',
            enctype    : 'multipart/form-data',
            processData: false,
            contentType: false,
            type       : 'POST',
            data       : data,
            async      : false,
            success    : function (result) {
                if (result == "false") {
                    msgAlert('error', '에러가 발생했습니다.', function () {
                        alertOff();
                    });
                } else {
                    msgAlert('success', '처리가 완료 되었습니다.', function () {
                        alertOff();
                        window.location.reload();
                    });
                }
            }
        });

        return false;
    }


    //승인상태에 따라 입력 폼 보여주기
    $(document).ready(function () {
        if ($('.step_alert > div').hasClass('step_3')) {
            $('.form_pay_cover').css('display', 'none');

        } else {
            $('.form_pay_cover').css('display', 'block');
        }

        var stepHeight = Number($('.step_alert p').css('height').replace(/[^0-9]/g, ""));
        //console.log(stepHeight + 30);
        $('.certify_idcard_wrap').css('padding-top', stepHeight + 30 + 'px');
    });


</script>

<?php
include_once('../shop/shop.tail.php');
?>