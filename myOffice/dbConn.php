<?php 
// 데이터베이스 커넥트 소스
    $connect = mysql_connect ("192.168.0.30","root","Neungsoft1!") or die ("DB 연결실패");
    if(!$connect) die('Not connected : ' . mysql_error());
    mysql_select_db ("gyc5",$connect);
    
    mysql_query("set session character_set_connection=utf8");
    mysql_query("set session character_set_results=utf8");
    mysql_query("set session character_set_client=utf8");



	
	function get_VMG_check($mb_id)
	{
		$row = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM g5_member WHERE mb_id = '".$mb_id."' AND accountRank IN (SELECT rankAccount FROM rankOrderBy WHERE orderNum < 10)"));


		return $row['cnt'];
	}

?>