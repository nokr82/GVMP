<!doctype html>

<?php
include_once('./dbConn.php');
//0109추가 추천인리뉴얼기간및아이디알아내기 리뉴얼지나면글씨빨간색으로
$mem_id = $_GET['deID'];
$re_member = mysql_query("SELECT m.renewal,m.mb_name,m.mb_id,m.accountType FROM gyc5.genealogy as g inner join g5_member as m on g.mb_id = m.mb_id where recommenderID = '{$mem_id}'");

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <meta name="viewport" content="width=device-width, user-scalable=yes">
    <title>Document</title>
    <link rel="stylesheet" href="./css/modal.css">
    <link rel="stylesheet" href="./css/box_new.css">
</head>
<script type="text/javascript">
    <!--
    window.printbtn = function (str) {
        var SUBWINPOP = window.open("./box.curl.php?mb_id=" + str, 'detailwindow', 'width=' + screen.width + ', height=' + screen.height + ', top=0, left=0, toolbar=0,location=false,resize=no');
    }
    //-->
</script>
<body>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display:block">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-primary" style="float:left"
                        onClick="printbtn('<?= $_GET['deID'] ?>')">프린트
                </button>
                <button type="button" class="btn btn-primary" style="float:left;margin-left:10px;"
                        onClick="opener.location.href='/myOffice/box.devtmp1.php?mb_id=<?= $_GET['deID'] ?>'; window.close();">기준
                </button>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="self.close();"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="bn_rank">
                    <img src="<?= $_GET['rankImg'] ?>" id="rankImg">
                </div>
                <ul class="clearfix">
                    <li id="deName">이름: <?= $_GET['deName'] ?></li>
                    <li id="deID">ID : <?= $_GET['deID'] ?></li>
                    <li id="deJoin">가입 : <?= $_GET['deJoin'] ?></li>
                    <li id="deRenew">리뉴얼 : <?php
                        if ($_GET['deRenew'] < date("Y-m-d")) {
                            echo "<font style='color: red'>{$_GET['deRenew']}</font>";
                        } else {
                            echo "{$_GET['deRenew']}";
                        }
                        ?></li>
                    <li id="dePeople">인원 : <?= $_GET['dePeople']
                        ?></li>
                    <li id="deRecom">
                        <table align="center">
                            <tr>
                                <th>추천</th>
                                <th>추천인 리뉴얼</th>
                            </tr>
                            <?php
                            while ($row = mysql_fetch_array($re_member)){
                                $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
                                $re_timestamp = $dateCheck_1["date"];
                                ?>
                                <tr>
                                    <td>
                                        <?= $row['mb_name'] ?>(<?= $row['mb_id'] ?>)                                                     </td>
                                    <td>
                                        <?php
                                        if ($re_timestamp < date("Y-m-d")) {
                                            if ($row['accountType']=='CU'){
                                                echo "CU";
                                            }else{
                                                echo "<font style='color: red'>{$re_timestamp}</font>";
                                            }
                                        } else {
                                            if ($row['renewal']==''){
                                                echo "CU";
                                            }else{
                                                echo "{$re_timestamp}";
                                            }

                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>

                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
</body>
</html>
