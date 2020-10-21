<?php
    include_once('./inc/title.php');
    include_once('./dbConn.php');
    include_once('./_common.php');
    include_once('./getMemberInfo.php');
    include_once('./OrganizationChartInfo.php');
?>


<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
<link rel="stylesheet" href="./css/orgChart.css">
<link rel="stylesheet" href="./css/chart.css">
<link rel="stylesheet" href="./css/modal.css">
<link rel="stylesheet" href="./css/box_new.css">

</head>
<!-- header -->

<?php
    include_once('../shop/shop.head.php');
?>
<script type="text/javascript">
<!--

function detailMember(MID,RECOM)
{
	$.ajax({
		url:'./memDetail.php',
		type:'post',
		data:{ "mbid": MID, "recom": RECOM },
        dataType: "json", 
		success:function(data){
			if(data.code=="01"){

			}else{
				$("#rankImg").attr("src",data.rankImg);
				$("#deName").html("이름: "+data.name);
				$("#deID").html("ID : "+data.mbid);
				$("#deJoin").html("가입 : "+data.joindate);
				$("#deRenew").html("리뉴얼 : "+data.renewal);
				$("#dePeople").html("인원 : "+data.people);
				$("#deRecom").html("추천 : "+data.recom);
				$("#myModal").modal('show');
			}
		}
	});
}

//-->
</script>
        
<input type="hidden" id="loginID" value="<?=$member['mb_id']?>">
<div id="people_tree">
    <div class="pt_wrap">
        <div class="pt_head_box clearfix">
            <h2>관리형</h2>
            <a class="pt_small_btn">
                <img src="images/btn-small.png" alt="최소화" />
            </a>
        </div>        
        <div class="pt_btn_box clearfix">
            <!--pt_serch_box div 생성-->
            <div class="pt_serch_box">
                <select id="pt_select" name="pt_name">
                    <option value="이름">이름</option>
                    <option value="아이디">아이디</option>
                </select>


                <input type="text" placeholder="회원검색" id="searchInput" onkeydown="Enter_Check();"/>
                <button type="button" onclick="searchClick();" id="searchclick"><i class="fa fa-search" aria-hidden="true"></i></button>

            </div>
             <!--pt_print_box div 생성-->
			<div class="pt_print_box">
                <a href="box_1.php" class="pt_btn pt_btn_mytree"><i class="fas fa-user"></i>나의 조직도</a>
                <a href="#" class="pt_btn pt_btn_print"><i class="fas fa-print"></i>조직도 인쇄</a>
            </div>            
            <div class="pt_movebox">
                <a href="sales_1.php" class="pt_btn">직접추천정보</a>
                <a href="tree.php" class="pt_btn">리스트형</a>
                <a href="allowance_1.php" class="pt_btn">수당체계</a>
            </div>          
        </div>        
        <div class="pt_btn_box clearfix" style="width:auto;padding:0px;">
			<div class="search_info" style="top:0px;">
				<table id="search_info_tbl">
					<thead>
						<tr>
							<th>이름</th>
							<th>추천인</th>
							<th>후원인</th>
							<th>직급</th>
							<th>리뉴얼</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
        </div>


		<div class="pt_img_box">
            <img src="images/pt-image.png" alt="현재상황설명이미지" />
        </div>  
        <a href="" class="pt_big_btn">
            <img src="images/btn-big.png" alt="최대화" />
        </a>
    </div>
<div>
</div>
<div class="zoomBtn_box">
<button class="zoomBtn" alt="plus"><i class="fa fa-plus"></i></button>
<button class="zoomBtn" alt="minus"><i class="fa fa-minus"></i></button>
</div>

<?
if(isset($_GET['mb_id'])&&$_GET['mb_id']){
	$MBID = $_GET['mb_id'];
}else{
	if($member['mb_id']=="admin"){
		$MBID['mb_id']="00000001";
	}
}
$TREEM;
$MEMBER;
$TMPARRAY="";


$NOWDATA = strtotime(date("Y-m-d"));
$MEMBER = getAllMemeber();
$TREE = memberTreeDetail($MBID);
$result = parseTreeDetail($TREE);
printTreeDetail($result);

?>

    <div class="pt_tree_box" style="overflow-x:scroll">
        <div id="chart-container" style="z-index:1">
		</div>
    </div>
</div>       

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/jquery.ui.touch-punch.min.js"></script>
<script src="js/jquery.print.js"></script>

<script src="js/jquery.mousewheel.js"></script>
<script src="https://hammerjs.github.io/dist/hammer.min.js"></script>

<script src="js/zoom-marker.js"></script>

<script src="js/jquery.mockjax.min.js"></script>
<script src="js/jquery.orgchart.js"></script> 
<script src="js/orgChart.dev.js"></script> 
<script src="js/modal.js"></script> 
<script>

$(function() {
	$("#organisation").orgChart({container: $("#chart-container"), interactive: false, fade: false, speed: 'fast'});
	$("#chart-container").draggable();

	var SC = 1;
	$(".zoomBtn").on("click",function(){
		var state = $(this).attr("alt");
		if(state=="plus"){
			SC = SC + 0.1;
		}else{
			SC = SC - 0.1;
		}
		if (SC <= 0){SC = 0;}
		$("#chart-container").css("transform","scale("+SC+")");
	});


	$(".pt_btn_mytree").on("click",function(){
		var MBID = ($("#loginID").val()=="admin")?"00000001":$("#loginID").val();
		location.href="?mb_id="+MBID;
	});
	$(".pt_btn_print").on("click",function(){

/*
		$('#chart-container').print({
			stylesheet : './css/box_new.css',
		});

                        //Use Global styles
                        globalStyles : false,
                        //Add link with attrbute media=print
                        mediaPrint : false,
                        //Custom stylesheet
                        stylesheet : "http://fonts.googleapis.com/css?family=Inconsolata",
                        //Print in a hidden iframe
                        iframe : false,
                        //Don't print this
                        noPrintSelector : ".avoid-this",
                        //Add this at top
                        prepend : "Hello World!!!&lt;br/&gt;",
                        //Add this on bottom
                        append : "&lt;span&gt;&lt;br/&gt;Buh Bye!&lt;/span&gt;",
                        //Log to console when printing is done via a deffered callback
                        deferred: $.Deferred().done(function() { console.log('Printing done', arguments); })
*/

		var restorepage = $('body').html();
		var printcontent = $('#chart-container').clone();
		var enteredtext = $('#text').val();
		$('body').empty().html(printcontent);
//		window.print();
//		$('body').html(restorepage);
//		$('#text').html(enteredtext);
	});
});


function searchClick() {
   if( $("#pt_select option:selected").val() == "아이디" ) {
	   var data = "searchID=" + $('#searchInput').val() + "&loginID=" + $('#loginID').val();

	   $.ajax({
		   url:'./memberIDSearch.php',
		   type:'POST',
		   data: data,
		   success:function(result){
			   if( result == "true" ) {
                   location.href='/myOffice/box_new_start.php?mb_id=' + $('#searchInput').val();
			   } else {
				   alert("검색할 수 없는 아이디입니다.");
			   }
		   }
	   });


   } else if( $("#pt_select option:selected").val() == "이름" ) {

	   var data = "searchName=" + $('#searchInput').val() + "&loginID=" + $('#loginID').val();
		if(!$('#searchInput').val()){
			alert("회원 이름을 넣어주세요");
			$("#searchInput").focus();
		}else{
			$("#lodingallMain").show();
			$.ajax({
			   url:'./memberNameSearch.php',
			   type:'POST',
			   data: data,
			   dataType :'json',
			   success:function(json){
					
					$("#lodingallMain").hide();
//				   var json = JSON.parse(result);

					var submenu = $(".search_info");
					if( submenu.is(":visible") ){
						$('#search_info_tbl > tbody:last > tr').remove();
					}

				   if( json.length == 0 ) {
					   alert("검색 결과가 없습니다."); exit();
				   }

				   var submenu = $(".search_info");
				   submenu.show();

					$('#search_info_tbl > tbody').empty();
				   for( var i=0 ; i<json.length ; i++ ) {
					   $('#search_info_tbl > tbody:last').append('<tr id=\"'+json[i][1]+'\" onclick=\"searchSelect(this.id);\"><td>'+json[i][0]+'</td><td>'+json[i][2]+'</td><td>'+json[i][5]+'</td><td>'+json[i][3]+'</td><td>'+json[i][4]+'</td></a></tr>');
				   }
			   }
			});
		}
   }
}
function Enter_Check(){ // 회원검색에서 엔터 키 입력하면 검색 진행하게 하는 함수
	if(event.keyCode == 13){
		 searchClick();
	}
}

function searchSelect(id) {
	location.href='/myOffice/box_new_start.php?mb_id=' + id;
}
</script>   
        
        
<script>
    $('.pt_small_btn').click( function(e){
       e.preventDefault();
       $('#hd_tnb').fadeOut();
       $('.pt_big_btn').fadeIn();
       $('.pt_head_box').fadeOut(); 
       $('.pt_btn_box').fadeOut();
       $('.pt_img_box').fadeOut();
       $('.copy').fadeOut();
    });
    
    $('.pt_big_btn').click( function(e){
       e.preventDefault();
       $('#hd_tnb').fadeIn();
       $('.pt_big_btn').fadeOut();
       $('.pt_head_box').fadeIn(); 
       $('.pt_btn_box').fadeIn();
       $('.pt_img_box').fadeIn();
       $('.copy').fadeIn();
    });
    
    
</script>        

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
          <div class="bn_rank">
              <img src="#" id="rankImg">
          </div>
          <ul class="clearfix">
              <li id="deName">이름: 김재환</li>
              <li id="deID">ID : 00004222</li>
              <li id="deJoin">가입 : 2019-01-13</li>
              <li id="deRenew">리뉴얼 : 2019-04-13</li>
              <li id="dePeople">인원 : 6/6/0</li>
              <li id="deRecom">추천 : 김재환</li>
          </ul>
      </div>

    </div>
  </div>
</div>



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
