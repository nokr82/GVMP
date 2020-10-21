<html lang="ko">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js" ></script>
        <script type="text/javascript" src="https://service.iamport.kr/js/iamport.payment-1.1.5.js"></script>
        <link rel="stylesheet" href="./css/point_pop_1.css" />
        <!--달력테스트-->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script> 
        <link rel="shortcut icon" href="/img/vmp_logo.ico" />
	<title>vmp point</title>
</head>
<body>
    <input type="hidden" id="calendarType" value="">
    <input type="hidden" id="inputType" value="1">
    <div class="point_pop">
        <div class="header">
          
            <ul class="point_info clearfix">
                <li>입/출금 내역</li>
                <li>입금 내역</li>
                <li>출금 내역</li>                
            </ul>
            
    <script>
        function dbAjax() {
            $("#tbody").empty();

            var ornum = "calendarType=" + $("#calendarType").val() + "&inputType=" + $("#inputType").val() + "&mb_id=" + opener.document.getElementById( "userid" ).value + "&date=" + $("#actualDate").val();        
            $.ajax({
                url:'point_pop_back.php',
                type:'POST',
                data: ornum,
                success:function(result5){

                    var json = JSON.parse( result5 );
                    console.log(result5);
                    var content;
                    for( var i=0 ; i<json.length ; i++ ) {
                        content += "<tr><td>"+json[i][0].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+"원</td><td>"+json[i][1]+"</td></tr>";
                    }
                    $('#tbody').html( content );
                }
            });
        }
          $(document).ready(function() {
                $('#actualDate').val(opener.document.getElementById( "popUpVal" ).value);
                $('#calendarType').val(opener.document.getElementById( "calendarType" ).value);

                switch( $('#calendarType').val() ) {
                    case "1" :
                        $(".point_info").find("li").eq(0).addClass('on'); break;
                    case "2" :
                        $(".point_info").find("li").eq(1).addClass('on'); break;
                    case "3" :
                        $(".point_info").find("li").eq(2).addClass('on'); break;
                }

                dbAjax();
            });
    </script>
            
            <script type="text/javascript">
              
                       $('.point_info > li').click(function(){
                            $(this).addClass('on');
                            $(this).siblings('li').removeClass('on');
                            
                            var index = $('.point_info > li').index(this);
                            $("#calendarType").val( index+1 );
                            
                            dbAjax();
                        });
      
            </script>
            
            <h1 class="logo"><img src="http://gvmp.company/data/common/mobile_logo_img" alt="logo" /></h1>
        
            <div class="date clearfix">
                <div>
<!--                    <span class="prev_btn" onclick="prev_btnFunc()"></span>-->
                    <p class="date_txt clearfix"><input type="hidden" id="testDatepicker" /><input type="text" class="ui-datepicker-trigger" id="actualDate" readonly />

                    </p>
<!--                    <span class="next_btn"></span>-->
                   </div>
            </div>
                

                
        <script>
             function prev_btnFunc() {
                 var date = new Date( );
                 
                 
                
                 
                 var yesterYear = date.getFullYear();
                 var yesterMonth = date.getMonth();
                 var yesterDay = date.getDate();
                 
                if( yesterMonth < 10 ) 
                    yesterMonth = "0" + yesterMonth;
                if( yesterDay < 10 )
                    yesterDay = "0" + yesterDay;
                

             }
            
            $(function() {
                    $( "#testDatepicker" ).datepicker({
                        showOn: "both",
                        buttonImage: "calendar_point.png", 
                        buttonImageOnly: true,
                        changeMonth:true,
                        changeYear:true,
                        dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
                        dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'],
                        monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
                        monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                        dateFormat: 'yy-mm-dd',
                        altField:"#actualDate"
                    });
                });

        </script>
       
        
        <div class="contents">
            <ul class="point_num clearfix">
                <li class="on">VMC</li>
                <!--li>VMR</li-->
                <li>VMP</li>
                <li>VMM</li>
                <li>Biz</li>
                <li>금고</li>
            </ul>
          
            <div class="c_box">
                <div class="c_box1 c_com on">
                    <table>
                        <thead>
                            <tr>
                                <th>금액</th>
                                <th>내용</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
     <script type="text/javascript">
		   $('.contents ul li').click(function(){
				$(this).addClass('on');
				$(this).siblings('li').removeClass('on');
				var index = $('.contents ul li').index(this);
				
				$('.contents .c_box div').eq(index).addClass('on');
				$('.contents .c_box div').eq(index).siblings('div').removeClass('on');
				
				$("#inputType").val( index+1 );
				
				dbAjax();
			});

			$( "#testDatepicker" ).change(function(){
				dbAjax();
		   });
     </script>
    
     
</body>