

$(document).ready(function(){
	/*utilBt*/
	var btn1 = document.querySelector('.event_btn1');
	var btn2 = document.querySelector('.event_btn2');
	var Hidden_element = document.querySelector('.hidden');
	var utilFlag = 0;
	var bodyW = parseInt($('body').css('width'));
	var $userImg = $('.user_img');

/*	if(bodyW > 800){
		btn1.addEventListener('click',function(){
			Hidden_element.style.display = 'none';
			btn1.style.display = 'none'
			btn2.style.display = 'block';
			utilFlag = 0;
		});
		btn2.addEventListener('click',function(){
			Hidden_element.style.display = 'block';
			btn2.style.display = 'none'
			btn1.style.display = 'block';
			utilFlag = 1;
		});
	}else{
		$userImg.on('click', function(){
			if(utilFlag == 0){
				Hidden_element.style.display = 'block';
				utilFlag = 1;
			}else{
				Hidden_element.style.display = 'none';
				utilFlag = 0;
			}
		})
	}
	*/
	$(window).bind("scroll", scrolledFixInner);
	function scrolledFixInner(){
		
		if(bodyW < 800){
			if(utilFlag == 1){
				Hidden_element.style.display = 'none';
				btn1.style.display = 'none'
				btn2.style.display = 'block';
				utilFlag = 0;
			}
		}
	}
});





/* 산하회원정보 - 트리형 */
$(document).ready(function(){
    $(".tree_more").click(function(){
      $(".tree_element2").toggle();
    });
    
    var state = 1;
    $(".tree_more").click(function(){
        if(state==1) {
            $(this).css({"transform":"rotate(180deg)"});
            state = -1;
        }
        else if(state==-1) {
            $(this).css({"transform":"rotate(0deg)"});
            state = 1;
        }
    });
});
    /* 날짜 검색 범위선택 */
    $(document).ready(function(){
  
    $("#start_year, #start_month, #end_year, #end_month").change(function() {
          
        var checkNum = 2;//3개월이면 2로 셋팅
          
        //선택된 값을 가져온다.
        var startYear = $("#start_year option:selected").val();
        var startMonth = $("#start_month option:selected").val();
        var endYear = $("#end_year option:selected").val();
        var endMonth = $("#end_month option:selected").val();
        //계산을 위해 명시적으로 형변환
        var startYearNum = Number(startYear);
        var startMonthNum = Number(startMonth);
        var endYearNum = Number(endYear);
        var endMonthNum = Number(endMonth);
          
        //일단 차이를 재서 위에서 정한 월이 넘어가는지 확인 
        var result = ((endYearNum*12)+endMonthNum) - ((startYearNum*12)+startMonthNum);
                  
        if(result>checkNum){
              
            alert("날짜 검색 범위는 "+(checkNum+1)+"개월 입니다.");
              
            if(endMonthNum<=checkNum){
                startYearNum = endYearNum-1;
                startMonthNum = 12-(checkNum-endMonthNum)
            }else{
                startYearNum = endYearNum;
                startMonthNum = endMonthNum-checkNum;
            }
              
            $("#start_year").val(startYearNum).attr("selected", "selected");
            $("#start_month").val(startMonthNum).attr("selected", "selected");
              
        };
          
    });
       
});

