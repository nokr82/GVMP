 <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"
  />
<link rel="stylesheet" href="./css/jquery-ui.min.css">
<link rel="stylesheet" href="./css/modal.css">
<link rel="stylesheet" href="./css/neungChart.css">
<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<link rel="stylesheet" href="./css/box_new.css">
<style>
body {padding:20px;}
  .wrap {display:flex;justify-content:space-around;margin-bottom:5px}
  .item {position:relative;width:150px;margin-bottom:50px;height:40px;border:1px solid;flex-shrink:0;margin-right:5px;text-align:center;box-sizing:border-box;line-height:34px;background-color: #edc951;}
  .item:after {content:" ";position:absolute;left:50%;top:39px;width:2px;height:16px;border-left:1px solid;} 
  .line_tree {position:absolute;left:50%;width:310px;height:34px;margin-top:-35px;border:1px solid;border-bottom:0;}  
  .lv1  .line_tree {display:none;}
  .item:nth-child(even)  .line_tree {display:none;}  
.item.sad {background:#75dcf6;}
.item.angry {background:#e56a77;}
.fa-stack {position:absolute;top:-10px;left:-10px;width:30px;}
.fa-stack i {font-size:30px;}

.fa-circle {color:#edc951}
.item.sad .fa-circle {color:#75dcf6}
.item.angry .fa-circle {color:#e56a77}

.fa-emotion:before {content: "\f581";}
.item.sad .fa-emotion:before {content: "\f5b4";}
.item.angry .fa-emotion:before {content: "\f5c8";}
.noitem {background:#c9c9c9;}
.noitem:before {display:none;}
   #lodingall{position: absolute;top: 45%;left: 50%;transform: translate(-50%,-50%); }
	#vmplogoimg{position: absolute;top: 38%;left: 50%;transform: translate(-50%,-50%); width:150px;}
	#lodingimg{position: absolute;left: 50%;top:100px !important;transform: translate(-50%,-50%); width:150px;}
	#lodingbody{position: absolute;top:0px;left:0px;width:100vw;height:100%;background:#FFFFFF;z-index:999997}
   </style>

<div id="lodingbody">
    <div id="lodingall">
		<ul>
			<li><img src="images/VMP logo.png" alt="로고" id="vmplogoimg"></li>
			<li><img src="images/loding.gif" alt="로딩이미지" id="lodingimg"></li>
		</ul>
    </div>
</div>


<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
set_time_limit(0);
//include_once('./inc/title.php');
include_once('./dbConn.php');
include_once('./_common2.php');
 $clickIdx = "00000182";
 $lvCount = 3;
 $deCount = 1;
mysql_query("CALL sp_tree_org( '".$clickIdx."',".$lvCount.")");
echo "<div class='wrap_all'>";

for($i=1;$i<=$lvCount;$i++){
	if($i!=1){
		$deCount= pow(2,$i-1);
	}
	$sql = "SELECT *
			FROM genealogy_tree_org
			WHERE rootid = '".$clickIdx."'  AND lv = ".$i."
			ORDER BY rootid
			, lv
			, seq;
		";
	$res = mysql_query($sql);
	$arrRow = array();
	$seqArr = array();
	
	while ($row = mysql_fetch_array($res)) {
		$arrRow[] = $row;
	}
//	P($sql);

	echo "<div class='wrap lv".$i."'>";
		for($j=1;$j<=$deCount;$j++){
			if($arrRow){
				foreach($arrRow as $key=>$val){
					if($j == 1){
						array_push($seqArr, $val['seq']);
					}/*
					if($j == $val['seq']){
						echo "<div class='item ' data-idx='".$val['mb_id']."'  data-pIdx = '".$val['sponsorID']."'>(".$val['lv'].")".$val['mb_name']."->".$val['mb_id']."</div>";
					}else if(!in_array($j,$seqArr) && $midx == $val['mb_id']){*/
					if($j == $val['seq']){
						echo "<div class='item ' data-idx='".$val['mb_id']."'  data-pIdx = '".$val['sponsorID']."' onclick='detailMemberModal()'><span  class='fa-stack fa-2x'><i class='fas fa-circle fa-stack-2x'></i><i class='far fa-emotion fa-stack-2x'></i></span>".$val['mb_name']."<span class='line_tree'></span></div>";
					}else if(!in_array($j,$seqArr) && $midx == $val['mb_id']){
						echo "<div class='item noitem' data-idx='N".$midx."'  data-pIdx = '".$val['sponsorID']."'>없음<span class='line_tree'></span></div>";

					}
				}
				$midx = $val['mb_id'];
			}
		}
	echo "</div>";
}
echo "</div>";
?>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>


<script type="text/javascript">
   $(function(){
   	var idx = $('.wrap').length;
   	var lastItemlength = $('.LV' + idx + ' .item').length;
   	var lastWidth = lastItemlength * 155;
    $('.wrap').width(lastWidth);
    for (var i = 2; i <= idx; i++) {
    	 $('.LV' + i + ' .line_tree').css('width', $('.Lv' + i).children('.item:first-child').next().offset().left - $('.Lv' + i).children('.item:first-child').offset().left);
    }
    setTimeout(function(){$(window).scrollLeft((lastWidth/2)-1000)}, 500);
  });
</script>

<script src="./js/jquery-3.4.1.min.js"></script>
<script src="./js/jquery-ui.min.js"></script>
<script src="./js/jquery.ui.touch-punch.min.js"></script>
<script src="./js/jquery.mousewheel.js"></script>
<script src="./js/modal.js"></script> 
<input type="hidden" id="loginID" value="<?=$member['mb_id']?>">
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
		$(".wrap_all").drags();
		$('.wrap_all').mousewheel(function(event, deltaY) {
			
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
			$('.wrap_all').css({
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

<script>
    var x,y;
    var test = $('.wrap_all ');
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




<?php
include_once('../shop/shop.tail.php');
?>
