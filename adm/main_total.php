<?php
$sub_menu = "200900";
include_once('./_common.php');


auth_check($auth[$sub_menu], 'r');

$g5['title'] = '매출/지출';
include_once('./admin.head.php');


include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
   menu_check($sub_menu,$member['mb_id']);
   
}

?>
	
<link rel="stylesheet" href="./css/main_total.css">
<script src="echarts.min.js"></script>

<div class="chart_info">
    <span>년도 조회</span>
        <select name="year_select" id="year_select">
            <option value="2018">2018년</option>
            <option value="2019">2019년</option>
            <option value="2020" selected="selected">2020년</option>
            <option value="2021">2021년</option>
            <option value="2022">2022년</option>
            <option value="2023">2023년</option>
            <option value="2024">2024년</option>
            <option value="2025">2025년</option>
            <option value="2027">2026년</option>
            <option value="2027">2027년</option>
            <option value="2028">2028년</option>
        </select>
    <button id="year_post">조회</button>
</div>
<div class="chart_content">
    <div class="total_tbl tbl_head01">
        <table>
            <thead>
                <tr>
                    <th colspan="2"><b class="total_year"></b>년 총 합계</th>
                </tr>    
            </thead>
            <tbody>
                <tr>
                    <td class="total_tit">총 매출</td>
                    <td><b class="total_main"></b>원</td>
                </tr>
                <tr>
                    <td class="total_tit">총 지출</td>
                    <td><b class="total_main2"></b>원</td>
                </tr>
            </tbody>
            
        </table>
    </div>
    <div id="main"></div>
    <div class="line"></div>
    <div id="main2"></div> 
    
<?php
include_once('./loadingMsg.php');
?>
</div>
  
<script type="text/javascript">
// based on prepared DOM, initialize echarts instance
var myChart = echarts.init(document.getElementById('main'));
var myChart2 = echarts.init(document.getElementById('main2'));

$("#year_post").click(function() {

    loadMsg('on','데이터 수신중입니다.');
    var year_check = $('#year_select').val();
    var total_main;
    var total_main2;
        $.ajax({ 
           type: 'GET', 
           url: './main_totalback.php',
           data : {
               'year': year_check
           },
           success: function(data) { 
               loadMsg('off');
               var obj = JSON.parse(data);
        // specify chart configuration item and data
        var option = {
            title: {
                text: '매출'
            },
           tooltip : {
                trigger: 'axis',
                    axisPointer : {           
                        type : 'shadow'        
                    }
            },
            legend: {
                data:['VM 가입비','VM 리뉴얼비','간편회원가입권 구매','비즈니스 팩1 구매','비즈머니 구매','SM 가입비','SM 리뉴얼비','이달 총매출']
            },
            xAxis: {
                data: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"]
            },
            yAxis: [
                            {
                        type: 'value',
                        name: '금액',
                        //min: 0,
                        //max: 250,
                        //interval: 50,
                        axisLabel: {
                            formatter: '{value} 원'
                        }
                }
            ],
            series: [
                {
                    name: 'VM 가입비',
                    type: 'bar',
                   // stack: '총매출',
                    data: [Math.abs(obj[1][0]), Math.abs(obj[1][1]), Math.abs(obj[1][2]), Math.abs(obj[1][3]), Math.abs(obj[1][4]), Math.abs(obj[1][5]), Math.abs(obj[1][6]), Math.abs(obj[1][7]), Math.abs(obj[1][8]), Math.abs(obj[1][9]), Math.abs(obj[1][10]), Math.abs(obj[1][11])]
                },
                {
                    name: 'VM 리뉴얼비',
                    type: 'bar',
                    data: [Math.abs(obj[0][0]), Math.abs(obj[0][1]), Math.abs(obj[0][2]), Math.abs(obj[0][3]), Math.abs(obj[0][4]), Math.abs(obj[0][5]), Math.abs(obj[0][6]), Math.abs(obj[0][7]), Math.abs(obj[0][8]), Math.abs(obj[0][9]), Math.abs(obj[0][10]), Math.abs(obj[0][11])]
                },
                {
                    name: '간편회원가입권 구매',
                    type: 'bar',
                    data: [Math.abs(obj[9][0]), Math.abs(obj[9][1]), Math.abs(obj[9][2]), Math.abs(obj[9][3]), Math.abs(obj[9][4]), Math.abs(obj[9][5]), Math.abs(obj[9][6]), Math.abs(obj[9][7]), Math.abs(obj[9][8]), Math.abs(obj[9][9]), Math.abs(obj[9][10]), Math.abs(obj[9][11])]
                },
                {
                    name: '비즈니스 팩1 구매',
                    type: 'bar',
                    data: [Math.abs(obj[10][0]), Math.abs(obj[10][1]), Math.abs(obj[10][2]), Math.abs(obj[10][3]), Math.abs(obj[10][4]), Math.abs(obj[10][5]), Math.abs(obj[10][6]), Math.abs(obj[10][7]), Math.abs(obj[10][8]), Math.abs(obj[10][9]), Math.abs(obj[10][10]), Math.abs(obj[10][11])]
                },
                {
                    name: '비즈머니 구매',
                    type: 'bar',
                    data: [Math.abs(obj[11][0]), Math.abs(obj[11][1]), Math.abs(obj[11][2]), Math.abs(obj[11][3]), Math.abs(obj[11][4]), Math.abs(obj[11][5]), Math.abs(obj[11][6]), Math.abs(obj[11][7]), Math.abs(obj[11][8]), Math.abs(obj[11][9]), Math.abs(obj[11][10]), Math.abs(obj[11][11])]
                },
                {
                    name: '중고장터 수수료',
                    type: 'bar',
                    data: [Math.abs(obj[12][0]), Math.abs(obj[12][1]), Math.abs(obj[12][2]), Math.abs(obj[12][3]), Math.abs(obj[12][4]), Math.abs(obj[12][5]), Math.abs(obj[12][6]), Math.abs(obj[12][7]), Math.abs(obj[12][8]), Math.abs(obj[12][9]), Math.abs(obj[12][10]), Math.abs(obj[12][11])]
                },
                {
                    name: 'SM 가입비',
                    type: 'bar',
                    data: [Math.abs(obj[2][0]), Math.abs(obj[2][1]), Math.abs(obj[2][2]), Math.abs(obj[2][3]), Math.abs(obj[2][4]), Math.abs(obj[2][5]), Math.abs(obj[2][6]), Math.abs(obj[2][7]), Math.abs(obj[2][8]), Math.abs(obj[2][9]), Math.abs(obj[2][10]), Math.abs(obj[2][11])]
                },
                {
                    name: 'SM 리뉴얼비',
                    type: 'bar',
                    data: [Math.abs(obj[3][0]), Math.abs(obj[3][1]), Math.abs(obj[3][2]), Math.abs(obj[3][3]), Math.abs(obj[3][4]), Math.abs(obj[3][5]), Math.abs(obj[3][6]), Math.abs(obj[3][7]), Math.abs(obj[3][8]), Math.abs(obj[3][9]), Math.abs(obj[3][10]), Math.abs(obj[3][11])]
                },
                {
                    name: '이달 총매출',
                    type: 'bar',
                    data:  (function(){ 
                                var result = [];
                                for(var i = 0; i < 12; i++){
                                    result.push( Math.abs(obj[1][i]) + Math.abs(obj[0][i]) + Math.abs(obj[9][i]) + Math.abs(obj[10][i]) + Math.abs(obj[11][i]) + Math.abs(obj[12][i]) + Math.abs(obj[2][i]) + Math.abs(obj[3][i]) )
                                };
                                total_main = result;
                                return result;
                            })()

                }
            ]
        };

// use configuration item and data specified to show chart
        myChart.setOption(option);
        //
///////////////////////////////두번째 차트
        // specify chart configuration item and data
        var option2 = {
            title: {
                text: '지출'
            },
           tooltip : {
                trigger: 'axis',
                    axisPointer : {           
                        type : 'shadow'        
                    }
            },
            legend: {
                data:['데일리수당으로 지급 된 C포인트','리뉴얼시 2만원 캐시백 된 수당','SM 가입으로 커미션 된 수당','SM 리뉴얼로 커미션 된 수당','공유 보너스','이달 총지출']
            },
            xAxis: {
                data: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"]
            },
            yAxis: [
                            {
                        type: 'value',
                        name: '금액',
                        //min: 0,
                        //max: 250,
                        //interval: 50,
                        axisLabel: {
                            formatter: '{value} 원'
                        }
                }
            ],
            series: [
                {
                    name: '데일리수당으로 지급 된 C포인트',
                    type: 'bar',
                    data: [ Math.abs(obj[4][0]), Math.abs(obj[4][1]), Math.abs(obj[4][2]), Math.abs(obj[4][3]), Math.abs(obj[4][4]), Math.abs(obj[4][5]) , Math.abs(obj[4][6]) , Math.abs(obj[4][7]) , Math.abs(obj[4][8]) , Math.abs(obj[4][9]) , Math.abs(obj[4][10]) , Math.abs(obj[4][11]) ]
                },
                {
                    name: '리뉴얼시 2만원 캐시백 된 수당',
                    type: 'bar',
                    data: [ Math.abs(obj[5][0]), Math.abs(obj[5][1]), Math.abs(obj[5][2]), Math.abs(obj[5][3]), Math.abs(obj[5][4]), Math.abs(obj[5][5]) , Math.abs(obj[5][6]) , Math.abs(obj[5][7]) , Math.abs(obj[5][8]) , Math.abs(obj[5][9]) , Math.abs(obj[5][10]) , Math.abs(obj[5][11]) ]
                },
                {
                    name: 'SM 가입으로 커미션 된 수당',
                    type: 'bar',
                    data: [ Math.abs(obj[6][0]), Math.abs(obj[6][1]), Math.abs(obj[6][2]), Math.abs(obj[6][3]), Math.abs(obj[6][4]), Math.abs(obj[6][5]) , Math.abs(obj[6][6]) , Math.abs(obj[6][7]) , Math.abs(obj[6][8]) , Math.abs(obj[6][9]) , Math.abs(obj[6][10]) , Math.abs(obj[6][11]) ]
                },
                {
                    name: 'SM 리뉴얼로 커미션 된 수당',
                    type: 'bar',
                    data: [ Math.abs(obj[7][0]), Math.abs(obj[7][1]), Math.abs(obj[7][2]), Math.abs(obj[7][3]), Math.abs(obj[7][4]), Math.abs(obj[7][5]) , Math.abs(obj[7][6]) , Math.abs(obj[7][7]) , Math.abs(obj[7][8]) , Math.abs(obj[7][9]) , Math.abs(obj[7][10]) , Math.abs(obj[7][11]) ]
                },
                {
                    name: '공유 보너스',
                    type: 'bar',
                    data: [ Math.abs(obj[8][0]), Math.abs(obj[8][1]), Math.abs(obj[8][2]), Math.abs(obj[8][3]), Math.abs(obj[8][4]), Math.abs(obj[8][5]) , Math.abs(obj[8][6]) , Math.abs(obj[8][7]) , Math.abs(obj[8][8]) , Math.abs(obj[8][9]) , Math.abs(obj[8][10]) , Math.abs(obj[8][11]) ]
                },
                {
                    name: '이달 총지출',
                    type: 'bar',
                    data:  (function(){ 
                                var result = [];
                                for(var i = 0; i < 12; i++){
                                    result.push( Math.abs(obj[4][i]) + Math.abs(obj[5][i]) + Math.abs(obj[6][i]) + Math.abs(obj[7][i]) + Math.abs(obj[8][i]) )
                                };
                                total_main2 = result;
                                return result;
                            })()

                }
               
            ]
        };
        tableShow(total_main, total_main2);
        // use configuration item and data specified to show chart
        myChart2.setOption(option2);
    
   
                } 
            });	
$('.line').fadeIn(2000);


//년도 기준 총 매출 테이블 보여주기
function tableShow(obj, obj2){
    var totalObj = obj[0]+obj[1]+obj[2]+obj[3]+obj[4]+obj[5]+obj[6]+obj[7]+obj[8]+obj[9]+obj[10]+obj[11]
    var totalObj2 = obj2[0]+obj2[1]+obj2[2]+obj2[3]+obj2[4]+obj2[5]+obj2[6]+obj2[7]+obj2[8]+obj2[9]+obj2[10]+obj2[11]

    var totalYear = $('#year_select option:selected').val();
    $('.total_tbl').css('display','block');
    $('.total_year').text(totalYear);
    $('.total_main').text(comma(totalObj));
    $('.total_main2').text(comma(totalObj2));
    
    
}



});
         
//콤마찍기
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}
        
    </script>
<?php
include_once('./admin.tail.php');
?>
