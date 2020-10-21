<link rel="stylesheet" href="css/index.css">
<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<?php
include_once ('./inc/title.php');
include_once ('./_common.php');
include_once ('./dbConn.php');
include_once ('./dbConn2.php');



if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');



$result = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
$row = mysql_fetch_array($result);
if( !($row['accountType'] == 'VM') && !($row['mb_id'] == 'admin') ) {
    echo "<script> 
    alert('VM회원만 접근 권한이 있습니다.');
    document.location.href='/myOffice/index.php'; 
    </script>"; 
    exit();
}






$sql = "SELECT 
        b.mb_name,
        a.mb_id,
        a.rankAccount,
        b.mb_hp,
        c.recommenderName,
        c.recommenderID,
        c.sponsorName,
        c.sponsorID,
        c.sponsorTeam,
        b.renewal,
        a.date,
		(select orderNum from rankOrderBy where rankAccount = a.rankAccount) as rankAccountNum 
    FROM
        gyc5.rankCheck AS a
            INNER JOIN
        g5_member AS b ON a.mb_id = b.mb_id
            INNER JOIN
        genealogy AS c ON b.mb_id = c.mb_id
    WHERE
        b.accountType = 'VM' and a.rankAccount != 'VM'";
    


if( $_POST["rankUpselect"] == "이름" ) {
	$sql .= " and b.mb_name like '%{$_POST["rankUpname"]}%'";
} else if( $_POST["rankUpselect"] == "아이디" ) {
	$sql .= " and a.mb_id like '{$_POST["rankUpname"]}'";
}

if( $_POST["rankUprank"] != "" ) {
	$sql .= " and a.rankAccount = '{$_POST["rankUprank"]}'";
}

if( isset($_POST["fr_date"]) && $_POST["la_date"] ) {
	$sql .= " and a.date >= '{$_POST["fr_date"]} 00:00:00' and a.date <= '{$_POST["la_date"]} 23:59:59'";
}


$sql .= " order by rankAccountNum DESC";

if( isset($_POST["rankUpname"]) || isset($_POST["fr_date"]) || isset($_POST["la_date"]) ) {
    $rankUpListRe = mysql_query($sql);
}










if( $member["mb_id"] == "admin" ) {
	$member["mb_id"] = '00000001';
}
if( isset($_POST["rankUpname"]) || isset($_POST["fr_date"]) || isset($_POST["la_date"]) ) {
    $vmListArray = array();
    if (mysqli_multi_query($connection, "CALL SP_TREE('{$member["mb_id"]}');SELECT * FROM genealogy_tree WHERE rootid = '{$member["mb_id"]}'")) {
        mysqli_store_result($connection);
        mysqli_next_result($connection);
        do {
            /* store first result set */
            if ($result = mysqli_store_result($connection)) {
                while ($vmListRow = mysqli_fetch_array($result)) {
                    array_push($vmListArray, $vmListRow["mb_id"]);
                }
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($connection));
    } else {
        echo mysqli_error($connection);
    }

    
//    튜닝으로 인한 주석처리
//    $vmListRe = mysql_query("SELECT 
//            t.mb_id
//    FROM
//            (SELECT 
//                    c.mb_name AS mb_name,
//                            c.mb_id,
//                            c.sponsorID,
//                            c.sponsorName,
//                            c.recommenderID,
//                            c.recommenderName,
//                            v.level
//            FROM
//                    (SELECT 
//                    F_CATEGORY() AS mb_id, @level AS LEVEL
//            FROM
//                    (SELECT @start_with:='{$member["mb_id"]}', @id:=@start_with, @level:=0) vars
//            INNER JOIN genealogy
//            WHERE
//                    @id IS NOT NULL) v
//            INNER JOIN genealogy c ON v.mb_id = c.mb_id) t
//                    INNER JOIN
//            g5_member m ON t.mb_id = m.mb_id;");
//    }
//
//$vmListArray = array();
//while( $vmListRow = mysql_fetch_array($vmListRe) ) {
//	array_push($vmListArray, $vmListRow["mb_id"]);
}

$ALPHA = array("","A","B","C","D","E","F","G","H","I","J","K","L");

?>

</head>
<body onload="">
	<!-- header -->
<?php
include_once ('../shop/shop.head.php');
?>
<form name="rankupForm" id="rankupForm" method="post" action="" onsubmit="loading()">
<section class="history rankuplist"> 
    <div>
        <h3 class="history_title">승급자 리스트</h3>		
        <div class="find_list">
            <div>
                <select id="rankUpselect" name="rankUpselect">
                    <option value="">--검색--</option>
                    <option value="이름" <?php if( $_POST["rankUpselect"] == "이름" ) { echo 'selected="selected"';} ?>>이름</option>
                    <option value="아이디" <?php if( $_POST["rankUpselect"] == "아이디" ) { echo 'selected="selected"';} ?>>아이디</option>
                </select>
                <input type="text" name="rankUpname"  id="rankUpname" value="<?php if( $_POST["rankUpname"] != "" ) { echo $_POST["rankUpname"];} ?>">
                <select name="rankUprank" id="rankUprank">
                    <option value="">--직급--</option>
                    <option value="MASTER" <?php if( $_POST["rankUprank"] == "MASTER" ) { echo 'selected="selected"';} ?>>MASTER</option>
                    <option value="DOUBLE MASTER" <?php if( $_POST["rankUprank"] == "DOUBLE MASTER" ) { echo 'selected="selected"';} ?>>DOUBLE MASTER</option>
                    <option value="TRIPLE MASTER" <?php if( $_POST["rankUprank"] == "TRIPLE MASTER" ) { echo 'selected="selected"';} ?>>TRIPLE MASTER</option>
                    <option value="1 STAR" <?php if( $_POST["rankUprank"] == "1 STAR" ) { echo 'selected="selected"';} ?>>1 STAR</option>
                    <option value="2 STAR" <?php if( $_POST["rankUprank"] == "2 STAR" ) { echo 'selected="selected"';} ?>>2 STAR</option>
                    <option value="3 STAR" <?php if( $_POST["rankUprank"] == "3 STAR" ) { echo 'selected="selected"';} ?>>3 STAR</option>
                    <option value="4 STAR" <?php if( $_POST["rankUprank"] == "4 STAR" ) { echo 'selected="selected"';} ?>>4 STAR</option>
                    <option value="5 STAR" <?php if( $_POST["rankUprank"] == "5 STAR" ) { echo 'selected="selected"';} ?>>5 STAR</option>
                    <option value="AMBASSADOR" <?php if( $_POST["rankUprank"] == "AMBASSADOR" ) { echo 'selected="selected"';} ?>>AMBASSADOR</option>
                    <option value="DOUBLE AMBASSADOR" <?php if( $_POST["rankUprank"] == "DOUBLE AMBASSADOR" ) { echo 'selected="selected"';} ?>>DOUBLE AMBASSADOR</option>
                    <option value="TRIPLE AMBASSADOR" <?php if( $_POST["rankUprank"] == "TRIPLE AMBASSADOR" ) { echo 'selected="selected"';} ?>>TRIPLE AMBASSADOR</option>
                    <option value="CROWN AMBASSADOR" <?php if( $_POST["rankUprank"] == "CROWN AMBASSADOR" ) { echo 'selected="selected"';} ?>>CROWN AMBASSADOR</option>
                    <option value="ROYAL CROWN AMBASSADOR" <?php if( $_POST["rankUprank"] == "ROYAL CROWN AMBASSADOR" ) { echo 'selected="selected"';} ?>>ROYAL CROWN AMBASSADOR</option>
                </select>
            </div>
            <div>
                <i class="fa fa-calendar" aria-hidden="true"></i>
                <input type="text" name="fr_date" id="Datepicker1" class="frm_input" size="11" maxlength="10" value="<?php if( $_POST["fr_date"] != "" ) { echo $_POST["fr_date"];} ?>" autocomplete="off">
                ~
                <input type="text"  name="la_date" id="Datepicker2" class="frm_input" size="11" maxlength="10" value="<?php if( $_POST["la_date"] != "" ) { echo $_POST["la_date"];} ?>" autocomplete="off">
            </div>
            <div>
                <input type="submit" value="검색" class="btn_rankup_serch" id="rankUpBtn">
            </div>
        </div>
        <h3 class="history_title" id="searchCount"></h3>
    </div>
    <div class="historyTableW">                    		
            <table id="memberTable">
                    <thead>
                            <tr>
								<th>이름(아이디)</th>
								<th>직급</th>
								<th>연락처</th>
								<th>추천인 이름(아이디)</th>
								<th>후원인 이름(아이디)</th>
								<th>승급 일자</th>
                            </tr>
                    </thead>
                    <tbody id="testTEST">
                        
                        
<?php
    
    $countCheck = 0;
    while( $rankUpListRow = mysql_fetch_array($rankUpListRe) ) {
        if( in_array($rankUpListRow["mb_id"], $vmListArray) ) {
            $countCheck++;
?>
        <tr>
            <td><?=$rankUpListRow["mb_name"]?>(<?=$rankUpListRow["mb_id"]?>)</td>
            <td><span style="color:transparent"><?=$ALPHA[$rankUpListRow["rankAccountNum"]]?></span><?=$rankUpListRow["rankAccount"]?></td>
            <td><?=$rankUpListRow["mb_hp"]?></td>
            <td><?=$rankUpListRow["recommenderName"]?>(<?=$rankUpListRow["recommenderID"]?>)</td>
            <td><?=$rankUpListRow["sponsorName"]?>(<?=$rankUpListRow["sponsorID"]?>)의 <?=$rankUpListRow["sponsorTeam"]?>팀</td>            
            <td><?php echo date("Y-m-d", strtotime($rankUpListRow["date"])); ?></td>
        </tr>          
<?php
        }
    }
    
    echo "<script>$('#searchCount').text('총 ". number_format($countCheck) ."명');</script>";
?>
                        
                        
                        
                        
                        
                        

                    </tbody>
            </table>
    </div>    
</section>
</form>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
<script>
    
    function loading() {
        $('.loading_wrap').addClass('on');
    }
    
    
    $(function() {
        $( "#Datepicker1" ).datepicker({
        	changeMonth: true,
            changeYear: true,
            dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
            dayNamesMin: ['일','월', '화', '수', '목', '금', '토'],
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
            monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
            dateFormat: 'yy-mm-dd'
        });
        $( "#Datepicker2" ).datepicker({
        	changeMonth: true,
            changeYear: true,
            dayNames: ['일요일','월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
            dayNamesMin: ['일','월', '화', '수', '목', '금', '토'],
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
            monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
            dateFormat: 'yy-mm-dd'
        });
        
        
        $('#rankUpBtn').click(function(){
        var findId = $('#rankUpselect').val();
        var rankUpname = $('#rankUpname').val();
        var dateStart = $('#Datepicker1').val();
        var dateEnd = $('#Datepicker2').val();
        
            if ( findId != "" ) {
                if ( rankUpname == "" ) {
                    alert('아이디 또는 이름을 입력해주세요.');
                    return false;
                }
            }
            
            if ( rankUpname != "" ) {
                if ( findId == "" ) {
                    alert('검색하실 종류를 정해주세요. (ex 아이디, 이름)');
                    return false;
                }
            }
            
            if ( dateStart != "" ) {
                if ( dateEnd == "" ) {
                    alert('끝기간을 입력해주세요.');
                    return false;
                }
            }
            
            if ( dateEnd != "" ) {
                if ( dateStart == "" ) {
                    alert('시작기간을 입력해 주세요.');
                    return false;
                }
            }
        })
        
        
        $('.sort_updown').click(function(){
            $(this).toggleClass('on');
        })
        
    });
$(document).ready( function () {
    var table = $('#memberTable').DataTable({
		paginate: false,
		order: [[ 1, "desc" ]],
		searching: false,
		columnDefs: [{ "orderable": false, "targets": 0 },{ "orderable": false, "targets": 2 },{ "orderable": false, "targets": 3 },{ "orderable": false, "targets": 4 },{ "orderable": false, "targets": 5 }]
	});
});
</script>

  <!--로딩페이지-->
    <style>
        .loading_wrap.on {display: block}
        .loading_wrap.off {display: none}
        .loading_wrap {display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;z-index: 9999; background: rgba(255,255,255,0.7); text-align: center}
        .loading_wrap .loading_content {position: absolute; top: 50%; left: 50%; margin-top: -150px; margin-left: -200px; width: 400px; height: 300px;}
        .loading_wrap .loading_content .loading_img {margin: 0 auto}
        .loading_wrap.on .loading_content .loading_txt {font-family: 'Noto Sans KR'; font-size: 1rem; color: #333}



        @media (min-width: 320px) and (max-width: 480px){   
            .loading_wrap .loading_content {margin-left: -100px; width: 200px;}    
            .loading_wrap .loading_content .loading_img {width: 80%}
            .loading_wrap.on .loading_content .loading_txt {font-size: 1.2rem}
        }
    </style>

    <div class="loading_wrap">
        <div class="loading_content">
            <img class="loading_img" src="/myOffice/images/vmp_loading_icon.gif" alt="로딩메세지"/>
            <p class="loading_txt">회원을 검색중 입니다.</p>
        </div>
    </div>



</body>
</html>
<?php
include_once ('../shop/shop.tail.php');
?>
<script src="js/main.js"></script>
<script src="js/script.js"></script>