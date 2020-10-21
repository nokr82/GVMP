<?php
	include './inc/title.php';
	include_once('./_common.php');
	
	if($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
	    header('Location: /bbs/login.php');
?>
<link rel="stylesheet" href="./css/box.css">
</head>
<body>
<!-- header -->
<?php
	include './inc/head.php';
?>
<!-- 컨텐츠 -->
    <section class="boxSection">
        <h3>산하 회원정보 - 박스형<a href="tree.php"><img src="images/tree_btn.png" /></a></h3>
        <div class="boxSearBox">
        	<a href="box.php"><img src="images/my_member_bt.gif" /></a>
            <a id="boxPrinBt" href="#" onclick="pageprint()" ><img src="images/print_bt.gif" /></a><span class="boxSearBoxHiddenSpan"><br /></span>
            <form>
                <button><i class="fa fa-search" aria-hidden="true"></i></button><!--
                --><input type="text" placeholder="회원검색" /><!--
                --><div id="select_box">
                        <label for="nameNick">이름</label>
                        <select id="nameNick">
                            <option selected="selected">이름</option>
                            <option>회원아이디</option>
                        </select>
                    </div>
                    <div class="clr"></div>
            </form>
            <div class="clr">
            </div>
        </div><!--boxSearBox-->
        <div id="printArea" class="boxContentW">
        <div class="boxContent">
        	<div class="boxDepth0">
            	<div class="boxLeveTop boxNone">
                	<a href="#">
                    	<div class="img"><img src="images/box_company.png" alt="" /></div>
                        <h5>VMP</h5>
                        <p>
                        	이사 - 6명<br />
                        	차장 - 12명<br />
                            부장 - 38명<br />
                        	과장 - 73명<br />
                        	팀장 - 120명<br />
                            대리 - 221명<br />
                            사원 - 338명
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div>
            </div><!--boxDepth0-->
            <div class="boxDepth1">
            	 <div class="boxLevel1 boxRight" >
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div><!--
                --><div class="boxLevel2 boxLeft" >
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img3.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div>
            </div><!--boxDepth1-->
            <div class="boxDepth2">
            	<div class="boxLevel1 boxRight">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img4.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img5.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div>
            </div><!--boxDepth2-->
            <div class="boxDepth3">
            	<div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div>
            </div><!--boxDepth3-->
        	<div class="boxDepth4">
            	<div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel2 boxRight">
                   <a href="#">
                    	<div class="img"><img src="images/box_sample_img6.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div><!--
                --><div class="boxLevel1 boxLeft">
                    <a href="#">
                    	<div class="img"><img src="images/box_sample_img2.png" alt="" /></div>
                        <h5>홍길동[팀장]</h5>
                        <p>
                            가입 - 2015.02.22<br />
                            리뉴얼 - 2016.02.24<br />
                            인원 - 120/123/123<br />
                            추천 - 김철수<br />
                        </p>
                    </a>
                    <div class="boxLine"></div>
                </div>
            </div><!--boxDepth4-->
           
        </div><!--boxContent-->
        </div><!--boxContentW-->
    </section>
    <script type="text/javascript">
	var initBody;
	function beforePrint(){
		initBody = document.body.innerHTML;
		document.body.innerHTML = printArea.innerHTML;
	}
	function afterPrint(){
		document.body.innerHTML = initBody;
	}
	function pageprint(){
		window.onbeforeprint = beforePrint;
		window.onafterprint = afterPrint;
		window.print();
	}
	
	
	
	$(document).ready(function() {
		var totalTile = 16;
		var bodyW = parseInt($('body').css('width'));
		var boxContentWidth = totalTile * 170;
		var $boxContent = $('.boxContent');
		$boxContent.css('width', boxContentWidth);
		if(bodyW > 800){
			var tt = parseInt($boxContent.css('width')) / 3;
		}else{
			var tt = parseInt($boxContent.css('width')) / 2.3;
		}
		$(".boxContentW").scrollLeft(tt);
		
		
		/*셀렉트박스*/
		var select = $("select#nameNick");
    
		select.change(function(){
			var select_name = $(this).children("option:selected").text();
			$(this).siblings("label").text(select_name);
		});
    });
	</script>
    <script src="js/main.js"></script>
</body>
</html>
