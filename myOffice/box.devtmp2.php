<?php
ini_set('memory_limit','-1');
ini_set('max_execution_time', 300);
set_time_limit(300);
//include_once('./inc/title.php');
include_once('./dbConn.php');
include_once('./_common2.php');
include_once('./getMemberInfo.php');
include_once('./OrganizationChartInfo.php');
include_once('./ArraySearch.php');

if( $member['mb_id'] == "admin" ) {
	$member['mb_id'] = "00000001";
}

$mbid = (isset($_GET['mb_id'])&&$_GET['mb_id'])?$_GET['mb_id']:$member["mb_id"];


$NOWDATA = strtotime(date("Y-m-d"));
$MEMBER = getAllMemeber();
$TREE = jsonMemberTreeDetail($mbid);
$DATAHTML = "";



$query = "mid='".$mbid."'";
$Result = ArraySearch($TREE, $query,1,'direct');

//echo "<pre>";
//print_r($Result);
//echo "</pre>";
$start = get_time();
$data = jsonPrintTreeDetail($Result);
$end = get_time();
$time = $end - $start;
echo "<font style='display:none;'>시간:" . $time . 's</font>';


function get_time()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}



if($mbid!="00000001"){
	$data = str_replace("<ul><ul>","",$data);
	$data = '<ul id="organisation">'.$data;
}

?>
<link rel="shortcut icon" href="/img/vmp_logo.ico" />
</head>

<style>
	#lodingall{position: absolute;top: 45%;left: 50%;transform: translate(-50%,-50%); }
	#vmplogoimg{position: absolute;top: 38%;left: 50%;transform: translate(-50%,-50%); width:150px;}
	#lodingimg{position: absolute;left: 50%;top:100px !important;transform: translate(-50%,-50%); width:150px;}
	#lodingbody{position: absolute;top:0px;left:0px;width:100vw;height:100%;background:#FFFFFF;z-index:999997}
</style>



<!-- div id="lodingbody">
    <div id="lodingall">
		<ul>
			<li><img src="images/VMP logo.png" alt="로고" id="vmplogoimg"></li>
			<li><img src="images/loding.gif" alt="로딩이미지" id="lodingimg"></li>
		</ul>
    </div>
</div -->

<div class="DebugSize" style="position:fixed;left:0px;top:0px;"></div>

<!-- header -->
<link rel="stylesheet" href="./css/jquery-ui.min.css">
<link rel="stylesheet" href="./css/modal.css">
<link rel="stylesheet" href="./css/neungChart.css">
<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<link rel="stylesheet" href="./css/box_new.css">

<script src="./js/jquery-3.4.1.min.js"></script>
<script src="./js/jquery-ui.min.js"></script>
<script src="./js/jquery.ui.touch-punch.min.js"></script>
<script src="./js/jquery.mousewheel.js"></script>
<script src="./js/modal.js"></script> 
<script>
var compteur = 10;
var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ? true : false;
(function($) {
    $.fn.drags = function(opt) {

        opt = $.extend({handle:"",cursor:"move"}, opt);

        if(opt.handle === "") {
            var $el = this;
        } else {
            var $el = this.find(opt.handle);
        }

        return $el.css('cursor', opt.cursor).on("mousedown", function(e) {
            if(opt.handle === "") {
                var $drag = $(this).addClass('draggable');
            } else {
                var $drag = $(this).addClass('active-handle').parent().addClass('draggable');
            }
            var z_idx = $drag.css('z-index'),
                drg_h = $drag.outerHeight(),
                drg_w = $drag.outerWidth(),
                pos_y = $drag.offset().top + drg_h - e.pageY,
                pos_x = $drag.offset().left + drg_w - e.pageX;
            $drag.css('z-index', 1000).parents().on("mousemove", function(e) {
                $('.draggable').css({
                	"transform":`translate(${e.pageX + pos_x - drg_w}px, ${e.pageY + pos_y - drg_h}px)`
                	/*
                    top:e.pageY + pos_y - drg_h,
                    left:e.pageX + pos_x - drg_w
                    */
                }).on("mouseup", function() {
                    $(this).removeClass('draggable').css('z-index', z_idx);
                });
            });
            e.preventDefault(); // disable selection
        }).on("mouseup", function() {
            if(opt.handle === "") {
                $(this).removeClass('draggable');
            } else {
                $(this).removeClass('active-handle').parent().removeClass('draggable');
            }
        });

    }
})(jQuery);
window.onload=function(){
	if(isMobile) {
	//
	}else{  //pc일때
        MOBILEBOOL = false;
		$("#neungOrgChart").drags();
		$('#neungOrgChart').mousewheel(function(event, deltaY) {
			$('body').on('scroll touchmove mousewheel', function(e) {
				return false;
			});
			//var fullwidth = $("#organisation").width();
//			var leftPosition = fullwidth / 2;
//			var leftPosition = $('.li_table').css("left").replace("px","");

			if(deltaY > 0){
				if(compteur < 20){
					compteur=compteur+0.2;
	//				leftPosition = parseInt(leftPosition) - ((compteur*3)*0.8);
				}
			}

			if(deltaY < 0){
				if(compteur > 2){
					compteur=compteur-0.2;
	//				leftPosition = parseInt(leftPosition) + ((compteur*3)*0.8);
				}
			}

			var scale = compteur/10;
			var scaleWidth = 100 * scale;
			$('#neungOrgChart').css({
				'transform':'scale('+scale+','+scale+')',
				'-webkit-transform':'scale('+scale+','+scale+')',
				'-moz-transform':'scale('+scale+','+scale+')',
				'-o-transform':'scale('+scale+','+scale+')',
				'-ms-transform':'scale('+scale+','+scale+')',
				'transform-origin': 'left top',
			});
			$("body").css("width",scaleWidth+"%");
//			$('.li_table').css("left",leftPosition+"px");
		});
	}
	$("#lodingbody").hide();
}

function detailMemberModal(MID,RECOM)
{
	$.ajax({
		url:'./memDetail.php',
		type:'post',
		data:{ "mbid": MID, "recom": RECOM },
        dataType: "json", 
		success:function(data){
			if(data.code=="01"){

			}else{
				var IMG;
				if(data.rankImg){
					$("#rankImg").attr("src",data.rankImg);
					IMG = data.rankImg;
				}else{
					$("#rankImg").attr("src","./images/space.png");
					IMG = "./images/space.png";
				}
				$("#deName").html("이름: "+data.name);
				$("#deID").html("ID : "+data.mbid);
				$("#deJoin").html("가입 : "+data.joindate);
				$("#deRenew").html("리뉴얼 : "+data.renewal);
				$("#dePeople").html("인원 : "+data.people);
				$("#deRecom").html("추천 : "+data.recom);
//
				var HTML = $("#myModal").html();
				var params = "?rankImg="+IMG+"&deName="+data.name+"&deID="+data.mbid+"&deJoin="+data.joindate+"&deRenew="+data.renewal+"&dePeople="+data.people+"&deRecom="+data.recom;
				var WINPOP = window.open("./detailpop.php"+params, 'importwindow', 'width=460, height=578, top=0, left=0, toolbar=0,location=false,resize=no, scrollbars=yes');
				$(WINPOP).focus();
				$(WINPOP.document.body).html(HTML);
			}
		}
	});
}


</script>
<input type="hidden" id="loginID" value="<?=$member['mb_id']?>">
<?
echo "<div id=\"neungOrgChart\">";
echo "<div class=\"li_table\">";
echo preg_replace("/<ul>/", "<ul id=\"organisation\">", $data, 1);
echo "</div>";
echo "</div>";
?>


<script>
    var x,y;
    var test = $('#neungOrgChart');
    test.click(function (event) {
        x = event.pageX;
        y = event.pageY; 
     //   alert('x좌표:' +x + ', y좌표:' + y);
 })
$('#myModal').on('shown.bs.modal', function () {
    $('meta').remove();
    $('head').append('<meta name="viewport" content="width=device-width, user-scalable=no">');
    $(this).css({'touch-action':'none'});
});
$('#myModal').on('hidden.bs.modal', function () {
    $('meta').remove();
    $('head').append('<meta name="viewport" content="user-scalable=yes">');
    $(this).css({'touch-action':'auto'});   
   
    $('body').scrollLeft(x - 100);
    $('body').scrollTop(y - 50);
   
});

</script>

<input type="hidden" id="infoTemp">
      
<link rel="stylesheet" href="./css/box.css">
<div id="spot" style="visibility: hidden;">
					
<?php
    $renewalTitle = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
    $row11 = mysql_fetch_array($renewalTitle);

    $row11['VMC'] = number_format($row11['VMC']);
    $row11['VMR'] = number_format($row11['VMR']);
    $row11['VMP'] = number_format($row11['VMP']);
?>
		<div class="pop_tit">VM 회원비 결제</div>
		<div id="renewal_pop">
			<div class="pop_vmc pop">
				<div>
					<p>VMC</p>
					<span><?=$row11['VMC']?></span>
				</div>
				<input type="number" name="pop_vmc" id="pop_vmc" placeholder="VMC 차감" onkeyup="PointOnchange(this);">
			</div>
			<div class="pop_vmr pop">
				<div>
					<p>VMR</p>
					<span><?=$row11['VMR']?></span>
				</div>
				<input type="number" name="pop_vmr" id="pop_vmr" placeholder="VMR 차감" onkeyup="PointOnchange(this);">
			</div>
			<div class="pop_vmp pop">
				<div>
					<p>VMP</p>
					<span><?=$row11['VMP']?></span>
				</div>
				<input type="number" name="pop_vmp" id="pop_vmp" placeholder="VMP 차감" onkeyup="PointOnchange(this);">
			</div>
			<div class="vm_sum">
				<div>
					<p>합 계 :</p>
					<span id="value">0</span>
				</div>
			</div>
			<div class="summary">
			<?php
			$renewal = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
			$row2 = mysql_fetch_array($renewal);
			
			if ($row2['accountType'] == "CU" && $row2['renewal'] != null && $timestamp <= $now_temp) {
				
				echo "<div><p>결제할 금액 : </p><span id=\"sum_count\">{$money}</span></div>";
			} else {
				echo "<div><p>결제할 금액 : </p><span id=\"sum_count\">2080000</span></div>";
			}                     
			?>                     
			</div>
			<div id="renewal_submit" style="cursor: pointer;" onclick="submitCheck();">결제하기</div>
			<a href="javascript:void(0);" onclick="$('#spot').css('visibility', 'hidden'); return false;"><div id="renewal_cancel" style="cursor: pointer;">취소</div></a>
		</div>
	</div>
</body>
<script type="text/javascript">
<!--

function PointOnchange(val){

	var popvmc = $('input[name=pop_vmc]').val().replace(/[^0-9]/g,"");
	var popvmr = $('input[name=pop_vmr]').val().replace(/[^0-9]/g,"");
	var popvmp = $('input[name=pop_vmp]').val().replace(/[^0-9]/g,"");

	var popsum = $('.vm_sum').find('span');
	var popsummary = $('.summary').find('span');

	var selectInput = $(val).attr('name');
	var selectInputVal = $(val).val();


	if(Number(selectInputVal) > Number($('.'+selectInput).find('span').text().replace(/[^0-9]/g,""))){
		alert('잔여포인트 보다 차감액이 큽니다!');
		$(val).val($('.'+selectInput).find('span').text());
		PointOnchange();
		return;
	}

	popsum.text(Number(popvmc)+Number(popvmr)+Number(popvmp));
	popsummary.text(2080000-Number(popsum.text()));

	$('input[name=pay]').val(2080000-Number(popsum.text()));
	$('input[name=VMC]').val(popvmc);
	$('input[name=VMR]').val(popvmr);
	$('input[name=VMP]').val(popvmp);
	$('input[name=totalpoint]').val(Number(popsum.text()));
}




function submitCheck(){
	var popsum = $('.vm_sum').find('span');
	var popsummary = $('.summary').find('span');

	if(Number(popsummary.text()) < 0){
		alert("포인트가 결제할 금액보다 큽니다.");
		return false;                    
	}else if(Number(popsummary.text()) > 0){
		alert('※ 포인트가 부족합니다.');
	}else if(Number(popsummary.text()) == 0) {

		if( $("#pop_vmc").val() == "" )
			$("#pop_vmc").val("0");
		if( $("#pop_vmr").val() == "" )
			$("#pop_vmr").val("0");
		if( $("#pop_vmp").val() == "" )
			$("#pop_vmp").val("0");


		window.open( $("#infoTemp").val() + "&vmc=" + $("#pop_vmc").val() + "&vmr=" + $("#pop_vmr").val() + "&vmp=" + $("#pop_vmp").val() + "&loginID=" + $('#loginID').val(), 'small','width=450,height=605,scrollbars=yes,menubar=no,location=no' );

		$("#pop_vmc").val("");
		$("#pop_vmr").val("");
		$("#pop_vmp").val("");

		$("#spot").css("visibility", "hidden");
	}
}   


//-->
</script>
<style>
    #lodingallMain{position: absolute;top: 45%;left: 50%;transform: translate(-50%,-50%); }
    #vmplogoimg{position: absolute;top: 38%;left: 50%;transform: translate(-50%,-50%); width:150px;}
    #lodingimg{position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%); width:150px;}
    
</style>

<div id="lodingallMain" style="z-index:99997;background: #FFFFFF;width:100%;height:100%;display:none">
	<img src="images/VMP logo.png" alt="로고" id="vmplogoimg">
	<img src="images/loding.gif" alt="로딩이미지" id="lodingimg">
</div>
</html>
<?php
    include_once('../shop/shop.tail.php');
?>
