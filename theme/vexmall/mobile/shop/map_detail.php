<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');

include_once(G5_EDITOR_LIB);


define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

$row = sql_fetch("select subject, content from vdMapInfoTBL where mb_id = '{$_GET['mb_id']}' and step > 1");
//echo $this->get_server_var('SCRIPT_FILENAME');

$is_dhtml_editor = true;
$editor_html = editor_html('wr_content', $row['content'], $is_dhtml_editor);
$editor_js = get_editor_js('wr_content', $is_dhtml_editor);
$editor_js .= chk_editor_js('wr_content', $is_dhtml_editor);
echo '<script src="'.G5_EDITOR_URL.'/'.$config['cf_editor'].'/autosave.editor.js"></script>';
?>

<div style="width:100%;height:600px;position:absolute;left:0px;top:10px;display:none">
<iframe style="width:100%;height:600px" name="fileuploadFrame" id="fileuploadFrame"></iframe>
</div>
<div id="mapDetail">
<form name="fwrite" id="fwrite" action="/vexMall/back/vdSetDetail.php" method="post" enctype="multipart/form-data" autocomplete="off" onSubmit="return formcheck()">
<input type="hidden" name="mb_id" id="mb_id" value="<?=$_GET['mb_id']?>">
        <div class="map_content">
            <!--div class="con_head clearfix">
                <h3>제목:	</h3>
                <input type="text" name="subject" id="subject" value="<?=$row['subject']?>">
            </div-->
        <div class="write_div">
            <label for="wr_content" class="sound_only">내용<strong>필수</strong></label>
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
            <?php } ?>
            <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <div id="char_count_wrap"><span id="char_count"></span>글자</div>
            <?php } ?>
        </div>
        </div>
        <button type="submit" id="mapSub">설정 완료</button>
</form>   
</div>


<style>
.cke_sc{
	display:none;
}
.sound_only {
	display:none;
}
</style>

<script type="text/javascript">
<?php //echo editor_html('test', get_text($co['co_content'], 0)); ?>   

function formcheck(){
//	if($("#subject").val() == ""){
//		$('#subject').focus();            
//		alert('제목을 입력해주세요.');
//		return false;
//	}
    <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>
	return true;
}
</script>


<?php
function br2nl($string) {
	return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}


include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>