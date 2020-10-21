<?php
include_once('./inc/title.php');
include_once('./dbConn.php');
include_once('./_common.php');
include_once('./getMemberInfo.php');
include_once('./OrganizationChartInfo.php');
?>


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="./css/myoffice_signUp.css">

    </head>
    <!-- header -->

<?php
include_once('../shop/shop.head.php');


$result11 = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
$row11 = mysql_fetch_array($result11);

$total = number_format($row11['VMC'] + $row11['VMP']);
$totalall = number_format( ($row11['VMC'] + $row11['VMP']) * 0.967);

$row11['VMC'] = number_format($row11['VMC']);
$row11['V'] = number_format($row11['V']);
$row11['VMP'] = number_format($row11['VMP']);

?>
    <div id="signUp">
        <form name="signupForm" action="myoffice_signUp_Update_test.php" method="post">
            <input type="hidden" id="mb_name" name="mb_name" value="<?=$member["mb_name"]?>">
            <input type="hidden" id="mb_id" name="mb_id" value="<?=$member["mb_id"]?>">
            <input type="hidden" id="totalprice" name="totalprice" value="1920000">
            <input type="hidden" name="H_reg_mb_sponsor" id="H_reg_mb_sponsor" value="">
            <input type="hidden" name="H_reg_mb_sponsorID" id="H_reg_mb_sponsorID" value="">
            <input type="hidden" name="H_radiobtn" id="H_radiobtn" value="">
            <div class="signup_wrap">
                <div class="sign_btn_box">
                    <a href="box_1.php" target="blank" class="go_mytree sign_btn"><i class="fas fa-user"></i>나의 관리형 보기</a>
                </div>
                <div class="sign_choice clearfix">
                    <h2>선택사항</h2>
                    <div>
                        <input type="radio" id="name_auto" name="nameChoice" value="auto" required>
                        <label for="name_auto">이름 자동 입력</label>
                    </div>
                    <div>
                        <input type="radio" id="name_self" name="nameChoice" value="self" required>
                        <label for="name_self">이름 직접 입력</label>
                    </div>


                    <div class="" style="float:right">
                        <input type="radio" id="memToalhalf" name="memTotal" value="half" required>
                        <label for="memToalhalf">6명</label>
                    </div>
                    <div style="float:right">
                        <input type="radio" id="memToalfull" name="memTotal" value="full" required checked>
                        <label for="memToalfull">12명</label>
                    </div>
                </div>
                <h2>이름</h2>
                <div class="sign_team_box clearfix">
                    <div class="sign_team1 sign_team">
                        <h3>1팀</h3>
                        <ul class="clearfix">
                            <li class="clearfix">
                                <h4>회원 1:</h4>
                                <input type="text" name="team1" id="team1" class="teamstyle" required>
                            </li>
                            <li class="clearfix">
                                <h4>회원 2:</h4>
                                <input type="text" name="team2" id="team2" class="teamstyle" required>
                            </li>
                            <li class="clearfix">
                                <h4>회원 3:</h4>
                                <input type="text" name="team3" id="team3" class="teamstyle" required>
                            </li>
                            <li class="clearfix">
                                <h4>회원 4:</h4>
                                <input type="text" name="team4" id="team4" class="teamstyle" required>
                            </li>
                            <li class="clearfix">
                                <h4>회원 5:</h4>
                                <input type="text" name="team5" id="team5" class="teamstyle" required>
                            </li>
                            <li class="clearfix">
                                <h4>회원 6:</h4>
                                <input type="text" name="team6" id="team6" class="teamstyle" required>
                            </li>
                        </ul>
                    </div>
                    <div class="sign_team2 sign_team">
                        <h3>2팀</h3>
                        <ul class="clearfix">
                            <li class="clearfix">
                                <h4>회원 7:</h4>
                                <input type="text" name="team7" id="team7" class="teamstyle" required>
                            </li>
                            <li class="clearfix">
                                <h4>회원 8:</h4>
                                <input type="text" name="team8" id="team8" class="teamstyle" required>
                            </li>
                            <li class="clearfix">
                                <h4>회원 9:</h4>
                                <input type="text" name="team9" id="team9" class="teamstyle" required>
                            </li>
                            <li class="clearfix">
                                <h4>회원 10:</h4>
                                <input type="text" name="team10" id="team10" class="teamstyle" required>
                            </li>
                            <li class="clearfix">
                                <h4>회원 11:</h4>
                                <input type="text" name="team11" id="team11" class="teamstyle" required>
                            </li>
                            <li class="clearfix">
                                <h4>회원 12:</h4>
                                <input type="text" name="team12" id="team12" class="teamstyle" required>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="sign_sponsor_box clearfix">
                    <div class="sign_sponser firstsopnser">
                        <h3>회원 1의 후원인</h3>
                        <div class="sign_sponser_content">
                            <ul class="clearfix">
                                <li class="clearfix">
                                    <h4>이름 :</h4>
                                    <input type="text" name="user_name1" id="user_name1" value="능이소프트_test3" required>
                                </li>
                                <li class="clearfix">
                                    <h4>회원번호 :</h4>
                                    <input type="text" name="user_num1" id="user_num1" value="00076961" maxlength="8" onkeydown="onlyNumber(this)" class="usernum" required>
                                </li>
                                <li class="clearfix">
                                    <h4>팀 :</h4>
                                    <div>
                                        <input type="radio" id="s1_team1" name="s1_teamChoice" value="1" class="team" required>
                                        <label for="s1_team1">1팀</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="s1_team2" name="s1_teamChoice" value="2" class="team" required>
                                        <label for="s1_team2">2팀</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="sign_sponser secondsopnser">
                        <h3>회원 7의 후원인</h3>
                        <div class="sign_sponser_content">
                            <ul class="clearfix">
                                <li class="clearfix">
                                    <h4>이름 :</h4>
                                    <input type="text" name="user_name2" id="user_name2" required>
                                </li>
                                <li class="clearfix">
                                    <h4>회원번호 :</h4>
                                    <input type="text" name="user_num2" id="user_num2" maxlength="8" onkeydown="onlyNumber(this)" class="usernum" required>
                                </li>
                                <li class="clearfix">
                                    <h4>팀 :</h4>
                                    <div>
                                        <input type="radio" id="s2_team1" name="s2_teamChoice" value="1" class="team" required>
                                        <label for="s2_team1">1팀</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="s2_team2" name="s2_teamChoice" value="2" class="team" required>
                                        <label for="s2_team2">2팀</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="sign_sponsor_box clearfix">
                    <div class="sign_sponser">
                        <h3>보유 포인트</h3>
                        <div class="sign_sponser_content">
                            <ul class="clearfix">
                                <li class="clearfix">
                                    <h4><span class="color_vmp">VMP :</span></h4>
                                    <input type="text" name="myvmp" id="myvmp" readonly value="<?=$row11['VMP']?>">
                                </li>
                                <li class="clearfix">
                                    <h4><span class="color_vmc">VMC :</span></h4>
                                    <input type="text" name="myvmc" id="myvmc" readonly value="<?=$row11['VMC']?>">
                                </li>
                                <li class="clearfix">
                                    <h4>합계 : </h4>
                                    <input type="text" name="mypoint_sum" id="mypoint_sum" readonly value="<?=$total?>">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="sign_sponser">
                        <h3>사용할 포인트</h3>
                        <div class="sign_sponser_content">
                            <ul class="clearfix">
                                <li class="clearfix">
                                    <h4><span class="color_vmp">VMP :</span></h4>
                                    <input type="text" name="payvmp" id="payvmp" class="usecoin" for="myvmp" onkeydown="onlyNumber(this)" value="0">
                                </li>
                                <li class="clearfix">
                                    <h4><span class="color_vmc">VMC :</span></h4>
                                    <input type="text" name="payvmc" id="payvmc" class="usecoin" for="myvmc" onkeydown="onlyNumber(this)" value="0">
                                </li>
                                <li class="clearfix">
                                    <h4>합계 : </h4>
                                    <input type="text" name="paypoint_sum" id="paypoint_sum" readonly value="0">
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="sum_point">결제 금액 : <span id="sellprice">1,920,000</span></p>
                <p class="submit_info"></p>
                <input type="submit" id="signSave" name="signSave" class="sign_btn" value="결제하기">
            </div>
        </form>
    </div>

    <script type="text/javascript">

        var teamAll = $('.teamstyle');
        $('#name_auto').on("click",function () {
            teamAll.prop('readonly', true);
            teamAll.addClass('in');
            $.each($(".teamstyle"),function(e,value){
                $(this).val($("#mb_name").val()+Number(e+1));
            });

        });
        $("#name_self").on("click",function(){
            teamAll.prop('readonly', false);
            teamAll.removeClass('in');
            $.each($(".teamstyle"),function(e,value){
                $(this).val("");
            });
        });


        $("#memToalfull").on("click",function(){
            $.each($(".teamstyle"),function(e,value){
                if(e>5){
                    $(this).val("");
                    $(this).prop('readonly', false);
                    $(this).prop('disabled', false);
                }
            });
            $(".sign_team2").show();
            $(".sign_team2 input").prop('required', true);
            $(".sign_team1").css("width","");
            $(".firstsopnser").css("width","");
            $(".secondsopnser input").prop('disabled', false);
            $(".secondsopnser input").prop('required', true);
            $(".secondsopnser").show();
            $("#sellprice").text(comma("1920000"));
        });

        $("#memToalhalf").on("click",function(){
            $.each($(".teamstyle"),function(e,value){
                if(e>5){
                    $(this).val("");
                    $(this).prop('readonly', true);
                    $(this).prop('disabled', true);
                }
            });
            $(".sign_team2").hide();
            $(".sign_team2 input").prop('required', false);
            $(".sign_team1").css("width","100%");
            $(".firstsopnser").css("width","100%");
            $(".secondsopnser input[type='text']").val("");
            $(".secondsopnser input").prop('disabled', true);
            $(".secondsopnser input").prop('required', false);
            $(".secondsopnser input").prop('required', false);
            $(".secondsopnser").hide();
            $("#sellprice").text(comma("960000"));
        });




        $(".usernum").on("blur",function(){
            var num = $(this).attr("id").replace(/[^0-9]/g,"");
            var nameObj = $("#user_name"+num);
            var numObj = $(this);
            if(!$(nameObj).val()){
                alert("회원"+num+"의 후원인 이름을 넣어 주세요");
                $(nameObj).focus();
            }else{
                var data = "reg_mb_sponsor=" + $(nameObj).val() + "&reg_mb_sponsor_no=" + $(this).val();
                $.ajaxSetup({ cache: false });
                $.ajax({
                    url:'/bbs/register_sponsorValue_checking.php',
                    type:'get',
                    data: encodeURI(data),
                    success:function(result){
                        if( result == "false" ) {
                            alert("입력하신 후원인 정보가 잘 못 됐습니다.");
                            $(nameObj).val("");
                            $(numObj).val("");
                            $(nameObj).focus();
                        }
                    }
                });
            }
        });


        $(".team").on("click",function(){
            var num = $(this).attr("name").replace(/[^0-9]/g,"");
            var tnum;
            var val = $(this).val();

            var nameObj = $("#user_name"+num);
            var numObj = $("#user_num"+num);
            var thisObj = $("input:radio[name='s"+num+"_teamChoice']");

            if(num==1) {
                tnum = 2;
            } else {
                tnum = 1;
            }

            var tnameObj = $("#user_name"+tnum);
            var tnumObj = $("#user_num"+tnum);
            var tObj = $("input:radio[name='s"+tnum+"_teamChoice']");

            if( $(nameObj).val()== $(tnameObj).val() && $(numObj).val() == $(tnumObj).val() && $("input:radio[name='s"+num+"_teamChoice']:checked").val() == $("input:radio[name='s"+tnum+"_teamChoice']:checked").val()){
                alert("같은 후원인 팀으로 배치될 수 없습니다.\n팀 선택을 다시 해 주시기 바랍니다." );
                $(nameObj).val("");
                $(numObj).val("");
                $(thisObj).prop("checked", false);
            }else{
                var data = "reg_mb_sponsor=" + $(nameObj).val() + "&reg_mb_sponsor_no=" + $(numObj).val() + "&reg_mb_sponser_team=" + val;
                $.ajax({
                    url:'/bbs/register_sponsorTeam_checking.php',
                    type:'get',
                    data: data,
                    success:function(result){
                        if( result == "false" ) {
                            alert("선택하신 후원인 팀으로 배치될 수 없습니다.\n팀 선택을 다시 해 주시기 바랍니다.");
                            $(thisObj).prop("checked", false);
                        }
                    }
                });
            }

        });


        function comma(num){
            var len, point, str;

            num = num + "";
            point = num.length % 3 ;
            len = num.length;

            str = num.substring(0, point);
            while (point < len) {
                if (str != "") str += ",";
                str += num.substring(point, point + 3);
                point += 3;
            }

            return str;

        }

        $(".usecoin").on("keyup",function(){
            TOTALPRICE = $("#totalprice").val();
            var num = $(this).val();
            var haveObj = $(this).attr("for");
            var haveNum = Number($("#"+haveObj).val().replace(/[^0-9]/g,""));
            if(num > haveNum) {
                alert("보유 포인트보다 더 많습니다.");
                $(this).val("");
                $(this).focus();
            }
            var sum=0;
            $.each($(".usecoin"),function(){
                sum += Number($(this).val());
            });
            $("#paypoint_sum").val(comma(sum));

            TOTALPRICE = Number(TOTALPRICE) - Number(num);
            //$("#sellprice").text(comma(TOTALPRICE));
        });
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        var CHK = false;
        //var NUM = 0;
        $('#signSave').on('click',function(){
            var all_sum = $("#paypoint_sum").val().replace(/[^0-9]/g,"");
            var TotalPrice = $("#sellprice").text().replace(/[^0-9]/g,"");
            if ( all_sum != TotalPrice) {
                alert('결제에 필요한 금액과 사용 할 포인트가 일치하지 않습니다.');
                return false;
            } else {
                //결제하기 버튼 없애기
                $('#signSave').css('display','none');
                $('.submit_info').text('결제 중입니다.');


//            if(!CHK){
//                NUM++;
//		CHK = true;
//                $("#debug").text(NUM+"번째 클릭");
//		return true;
//            }else{
//                return false;
//            }
            }
        });

        function onlyNumber(obj) {
            $(obj).keyup(function(){
                $(this).val($(this).val().replace(/[^0-9]/g,""));
            });
        }

        function comma(str) {
            str = String(str);
            return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
        }


    </script>

    </body>
    </html>
<?php
include_once('../shop/shop.tail.php');
?>