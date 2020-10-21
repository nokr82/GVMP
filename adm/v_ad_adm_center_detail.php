<?php
$sub_menu = "200930";
include_once('./_common.php');

$g5['title'] = '광고관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

$dbName = "";
if( $_GET["type"] == "image" ) {
    $dbName = "imageAdListTBL";
} else if($_GET["type"] == "text") {
    $dbName = "textAdListTBL";
}

$dbRow = mysql_fetch_array(mysql_query("select a.*, b.mb_name from {$dbName} as a left join g5_member as b on a.mb_id = b.mb_id where no = {$_GET["no"]}"));


?>


<div class="adm_v_ad_center">
    <div class="nav_tab">
        <ul>
            <li>
                <a href="<?=$_SERVER["HTTP_REFERER"]?>">목록</a>
            </li>
            <li>
                <a href="#none" onclick="ad_delete_func( '<?=$_GET["type"]?>', '<?=$dbRow["no"]?>' );">삭제</a>
            </li>
        </ul>
    </div>
    <div class="v_content">
        <h3>광고정보</h3>
        <div class="v_info">
            <dl>
                <dt>광고명</dt>
                <dd><?=$dbRow["adName"]?></dd>
                <dt>회사명</dt>
                <dd><?=$dbRow["companyName"]?></dd>
                <dt>하루예산</dt>
                <dd><?php echo number_format($dbRow["budget"]) ?>원</dd>
                <dt>기간</dt>
                <dd>
                    <em><?=$dbRow["frDate"]?></em>
                    <i>~</i>
                    <em><?=$dbRow["toDate"]?></em>
                </dd>
                <dt>등록날짜</dt>
                <dd><?=$dbRow["datetime"]?></dd>
                <dt>이름(아이디)</dt>
                <dd><?=$dbRow["mb_name"]?>(<?=$dbRow["mb_id"]?>)</dd>
                <dt>광고URL</dt>
                <dd><a target="_blank" href="<?=$dbRow["url"]?>"><?=$dbRow["url"]?></a></dd>
            </dl>
        </div>
<?php
    if( $_GET["type"] == "image" ) {
        ?>
            <div class="v_ad_cont_img">
                <ul>
                    <li>
                        <h4>PC</h4>
                        <div class="v_ad_pc_img">
        <!--                        <img src="../myOffice/images/img_vcenter_banner_pc.jpg" alt="PC 이미지"/>-->
                            <img src="/up/ad/image/<?=$dbRow["image1"]?>" alt="PC 이미지"/>
                        </div>
                    </li>
                    <li>
                        <h4>MOBILE</h4>
                        <div class="v_ad_mobile_img">
                            <!--<img src="../myOffice/images/img_vcenter_banner_mobile.jpg" alt="Mobile 이미지"/>-->
                            <img src="/up/ad/image/<?=$dbRow["image2"]?>" alt="Mobile 이미지"/>
                        </div>
                    </li>
                </ul>
            </div>  
        <?php
    } 
?>
        
<?php
    if( $_GET["type"] == "text" ) {
        ?>
            <div class="v_ad_cont_txt">
                <h4>광고 문구</h4>
                <p><?=$dbRow["text"]?></p>
            </div>  
        <?php
    } 
?>
        
        
        
        
    </div>
    <div class="btn_box">
        <a href="#none" class="adm_btn btn_seal" onclick="ad_YN('<?=$_GET["type"]?>', <?=$dbRow["no"]?>, 'Y');">승인완료</a>
        <a href="#none" class="adm_btn btn_reject" onclick="ad_YN('<?=$_GET["type"]?>', <?=$dbRow["no"]?>, 'N');">승인거절</a>
    </div>
</div>

<?php
include_once ('../myOffice/v_ad_alert.php');
?>
<?php
include_once ('../myOffice/alert.php');
?>
<script>
// 광고 삭제 처리하는 함수
function ad_delete_func( type, no ) {


    msgAlert('confirm','삭제 하시겠습니까?',function(){ alertOff(); GO( type, no ) }, function(){ alertOff();});


    function GO( type, no ) {
        $.ajax({
            url:'/myOffice/v_ad_delete_back.php',
            type:'POST',
            data: "type="+type+"&no="+no,
            async: false,
            success:function(result){
                if( result == "false" ) {
                    alertMsg('error','에러가 발생했습니다.');
                } else {
                    window.location.href = 'v_ad_adm_center.php';
                }
           }
        });
    }

}


// 승인 또는 거절을 처리하는 함수
function ad_YN( type, no, ok ) {
    var com = "";
    var rejectResult; 
    if( ok == "Y" ) {
        com = "승인";
        msgAlert('confirm', com+' 하시겠습니까?',function(){ alertOff(); GO( type, no, ok, "" ) }, function(){ alertOff();});
    } else if( ok == "N" ) {
        com = "거절";
        msgAlert('confirm', com+' 하시겠습니까?',function(){ alertOff(); rejectResult = prompt('승인 거절사유를 입력해주세요.',''); if(rejectResult!=null) {GO( type, no, ok, rejectResult )} }, function(){ alertOff();});
    }
    


    function GO( type, no, ok, comment ) {
        $.ajax({
            url:'v_ad_adm_center_detail_back.php',
            type:'POST',
            data: "type="+type+"&no="+no+"&ok="+ok+"&comment="+comment,
            async: false,
            success:function(result){
                if( result == "false" ) {
                    alertMsg('error','에러가 발생했습니다.');
                } else {
                    window.location.href = 'v_ad_adm_center.php';
                }
           }
        });
    }

}
</script>