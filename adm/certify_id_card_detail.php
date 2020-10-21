<?php
$sub_menu = "200950";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');


$g5['title'] = '신분증 인증 시스템';
include_once (G5_ADMIN_PATH.'/admin.head.php');


$colspan = 5;


$idCertificateRow = mysql_fetch_array(mysql_query("select * from idCertificateTBL where no = {$_GET["no"]}"));
?>
<!--신분증 인증 시스템 시작-->
<div class="certify_id_card_wrap ">
    <div class="nav_tab">
        <ul>
            <li>
                <a href="<?=$_SERVER["HTTP_REFERER"]?>">목록</a>
            </li>
        </ul>
    </div>
    <!--신분증 인증 시스템 상세 시작-->
    <div class="v_content">
        <h3>고객정보</h3>
        <div class="v_info">
            <dl>
                <dt>등록날짜</dt>
                <dd><?=$idCertificateRow["datetime"]?></dd>
                <dt>이름</dt>
                <dd><?=$idCertificateRow["name"]?></dd>
                <dt>주민등록번호</dt>
                <dd><?=$idCertificateRow["RRN1"]?>-<?=$idCertificateRow["RRN2"]?></dd>
                <dt>아이디</dt>
                <dd><?=$idCertificateRow["mb_id"]?></dd>
                <dt>내/외국인 여부</dt>
                <dd><?php if($idCertificateRow["DnF"]=="D") {echo "내국인";} else if($idCertificateRow["DnF"]=="F") {echo "외국인";} ?></dd>
            </dl>
        </div>
        <div class="v_detail">
            <h4>신분증</h4>
            <div class="certify_id_card_img">
                <img src="/up/idCertificate/<?=$idCertificateRow["image"]?>" alt="신분증"/>
            </div>
        </div>  
    </div>
    <div class="btn_box" style="top:161px;">
        <a href="#none" class="adm_btn btn_seal" onclick="ad_YN(<?=$_GET["no"]?>, 'Y');">승인완료</a>
        <a href="#none" class="adm_btn btn_reject" onclick="ad_YN(<?=$_GET["no"]?>, 'N');">승인거절</a>
    </div>
    <!--신분증 인증 시스템 상세 끝-->

  
</div>
<!--신분증 인증 시스템 끝-->



<?php
include_once ('../myOffice/v_ad_alert.php');
?>
<?php
include_once ('../myOffice/alert.php');
?>


<script>



// 승인완료 또는 거절을 처리 요청하는 함수
function ad_YN( no, ok ) {
    var com = "";
    var rejectResult; 
    if( ok == "Y" ) {
        com = "승인";
        msgAlert('confirm', com+' 하시겠습니까?',function(){ alertOff(); GO(no, ok, "" ) }, function(){ alertOff();});
    } else if( ok == "N" ) {
        com = "거절";
        msgAlert('confirm', com+' 하시겠습니까?',function(){ alertOff(); rejectResult = prompt('승인 거절사유를 입력해주세요.',''); if(rejectResult!=null) {GO( no, ok, rejectResult )} }, function(){ alertOff();});
    }
    


    function GO( no, ok, comment ) {
        $.ajax({
            url:'certify_id_card_detail_back.php',
            type:'POST',
            data: "no="+no+"&ok="+ok+"&comment="+comment,
            async: false,
            success:function(result){
                if( result == "false" ) {
                    alertMsg('error','에러가 발생했습니다.');
                } else {
                    window.location.href = '<?=$_SERVER["HTTP_REFERER"]?>';
                }
           }
        });
    }
}

</script>
