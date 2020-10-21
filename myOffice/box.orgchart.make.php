<?php
$start = get_time();
set_time_limit(0); // 시간 제한 없애는 코드이며 이거 없으면 해당 로직은 수행 시간이 오래걸려 돌다가 멈춥니다. 지우지 마세요.
ini_set('memory_limit', '-1'); // 메모리 제한을 없애는 코드. 지우면 메모리 부족으로 중간에 멈춥니다. 지우지 마세요.
include_once('./dbConn.php');
include_once('./dbConn2.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


$TREEM;
$MEMBER;
$TMPARRAY="";


$NOWDATA = strtotime(date("Y-m-d"));
$MEMBER = getAllMemeber();
$TREE = memberTreeDetail('00000001');
//$TREE = memberTreeDetail('00004251');
$result = parseTreeDetail($TREE);

//echo "<pre>";
//print_r($result);
//echo "</pre>";
//echo exit;
//printTreeDetail($result);


$sql = "UPDATE orgChartTable SET jsonData='".json_encode($result,JSON_UNESCAPED_UNICODE)."' WHERE n = '1';";
$result = mysql_query($sql);
$end = get_time();
$time = $end - $start;
echo "<br>" . $time . 's<br>';
if($result){
	echo "OK";
}else{
//    echo "FAIL";
    die('Invalid query: ' . mysql_error());
}
//echo "<pre>";
//print_r($result);
//echo "</pre>";
echo exit;

function getAllMemeber()
{
	$data="";
	$sql = "SELECT mb_id, mb_name, renewal FROM g5_member where mb_id <> 'admin'";
	$result = mysql_query($sql);
	while( $row = mysql_fetch_array($result) ) {
		$data[$row['mb_id']] = $row['mb_name'];
	}
	$data['999999999999'] = '+';
	return $data;
}

function memberTreeDetail($MBID) {
    global $connection;
    
    
    $CHECK="";
    $TEAMBILD=false;
    $TMPTEAM=1;
    if (mysqli_multi_query($connection, "CALL SP_TREE('{$MBID}');SELECT * FROM genealogy_tree WHERE rootid = '{$MBID}' ORDER BY sponsorID,sponsorTeam")) {
        mysqli_store_result($connection);
        mysqli_next_result($connection);

            /* store first result set */
            if ($result = mysqli_store_result($connection)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if($CHECK!=$row['sponsorID']){
                            if($row['scnt']=="1"&&$row['sponsorTeam']=="1"){
                                    $TEAMBILD=true;
                            }
                            $TMPTEAM = 1;
                            $CHECK=$row['sponsorID'];
                    }
                    if($TMPTEAM==1&&$row['sponsorTeam']=="2"){
                            $DATA[$row['mb_id'].':first']= $row['sponsorID'] . "||1||false||".$row['sponsorName']."||".$row['recommenderName']."||".$row['scnt'];
                    }

                    $DATA[$row['mb_id']]= $row['sponsorID'] . "||" . $row['sponsorTeam'] . "||" . $row['newstate']."||".$row['sponsorName']."||".$row['recommenderName']."||".$row['scnt'];
                    if($TEAMBILD){
                            $DATA[$row['mb_id'].':second']= $row['sponsorID'] . "||2||false||".$row['sponsorName']."||".$row['recommenderName']."||".$row['scnt'];
                            $TEAMBILD=false;
                    }
                    $TMPTEAM++;
                }
                mysqli_free_result($result);
            }
    } else {
        echo mysqli_error($connection);
    }
    $DATA[$MBID] = NULL;
    return $DATA;
}

function parseTreeDetail($tree, $root = null) {
	global $MEMBER;
    $return = array();
    foreach($tree as $child => $parent) {
		if($parent != null || $parent){
			$tmp = explode('||', $parent);
			$parent = $tmp[0];
			$team = $tmp[1];
			$sponsorName = $tmp[3];
			$recommName = $tmp[4];
			$scnt = $tmp[5];
			if($tmp[2]!="false"){
				$RENEWAL = $tmp[2];
			}else{
				$RENEWAL = "";
			}
		}
		else{
			$parent = $parent;
			$team = "super";
			$RENEWAL = "";
		}

		if (strpos($child, ":")){
			$NAME = ""; // + 버튼 빼는 공식
			/*플러스가 필요하다면 아래 주석 제거*/
//			$NAME = $MEMBER['999999999999'];
//			$MID = $parent;
//			$RENEWAL = "plus";
			/*플러스가 필요하다면 여기까지 주석 제거*/
		}else{
			$NAME = $MEMBER[$child];
			$MID = $child;
		}
        if($parent == $root&&$NAME) {
            unset($tree[$child]);
            $return[] = array(
                'name' => $NAME,
				'mid'	=> $MID,
				'team'	=> $team,
				'renewal' => $RENEWAL,
				'parent' => $parent,
				'parentName' => $sponsorName,
				'recommName' => $recommName,
				'childrenCNT' => $scnt,
                'children' => parseTreeDetail($tree, $child)
            );
        }
    }
    return empty($return) ? null : $return;    
}
$POSITION=0;
function printTreeDetail($tree) {
	global $POSITION;
    if(!is_null($tree) && count($tree) > 0) {
        echo '<ul';
		if($POSITION==0){
//			echo ' id="organisation" style="display:none"';
			echo ' id="organisation"';
		}
		echo '>';
		$POSITION++;
        foreach($tree as $key => $node) {
			$ADDC = ($node['renewal'])?$node['renewal']:"";
//            echo "<li><a href=\"#none\" OnClick=\"DetailShow('".$node['mid']."')\" alt=\"".$ADDC."\">".$node['name']."</a>";

			if($node['name']=="+"){
	            echo "<li><a href=\"#none\" OnClick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id=".$node['parent']."&mb_name=".$node['parentName']."&team=".$node['team']."','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\" alt=\"".$ADDC."\">".$node['name']."</a>";
			} else {
	            echo "<li><a href=\"#none\" OnClick=\"detailMember('".$node['mid']."','".$node['recommName']."');\" alt=\"".$ADDC."\">".$node['name']."</a>";
			}
            printTreeDetail($node['children']);
            echo '</li>';
        }
        echo '</ul>';
    }
}

function renewalData($data,$sadData,$angryData)
{
	global $NOWDATA;
	$DATA="";
	if($NOWDATA<=$sadData){
		$DATA = "";
	}else if($NOWDATA>$sadData&&$NOWDATA<=$angryData){
		$DATA = "sad";
	}else if($NOWDATA>$angryData){
		$DATA = "angry";
	}else{
		$DATA = "";
	}
	return $DATA;
}

mysql_close( $connect );





function get_time()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
?>