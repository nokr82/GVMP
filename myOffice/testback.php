
<?php
    $connect = mysql_connect("192.168.0.30", "root", "Neungsoft1!") or die("DB 연결실패");
        if (!$connect)
            die('Not connected : ' . mysql_error());
        mysql_select_db("gyc5", $connect);

    mysql_query("set session character_set_connection=utf8");
    mysql_query("set session character_set_results=utf8");
    mysql_query("set session character_set_client=utf8");
?>

<?php
    if (isset($_FILES['upload']) && !$_FILES['upload']['error']) {
// 3-1.허용할 이미지 종류를 배열로 저장
        $imageKind = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
// 3-2.imageKind 배열내에 $_FILES['upload']['type']에 해당되는 타입(문자열) 있는지 체크
        if (in_array($_FILES['upload']['type'], $imageKind)) {
// 4.허용하는 이미지파일이라면 지정된 위치로 이동
            $img_type = $_FILES['upload']['name'];
            $img_last_type = (strrchr($img_type, '.'));
            $img_name_num;
            while (true) {
                $img_name_num = rand(000000000000000, 999999999999999) . $img_last_type;
                $row = mysql_fetch_array(mysql_query("select * from account_test where upload = '{$img_name_num}'"));
                if ($row['upload'] == "") {
                    // 중복 안 됨
                    break;
                }
            }
            mysql_query("insert into account_test set upload = '{$img_name_num}'") or die("에러 남");
                //../vexMall/images/category/
            if (move_uploaded_file($_FILES['upload']['tmp_name'], "../myOffice/uploads/category/$img_name_num")) {
                echo '<p><img src="./uploads/category/' . $img_name_num . '" /></p>';
                echo '<p>파일명: ' . $img_name_num . '</p>';
            } //if , move_uploaded_file
        } else { // 3-3.허용된 이미지 타입이 아닌경우
            echo '<p>JPEG 또는 PNG 이미지만 업로드 가능합니다.</p>';
        }//if , inarray
    } //if , isset
// 6.에러가 존재하는지 체크
    if ($_FILES['upload']['error'] > 0) {
        //echo '<p>파일 업로드 실패 이유: <strong>';
// 실패 내용을 출력
        switch ($_FILES['upload']['error']) {
            case 1:
                echo 'php.ini 파일의 upload_max_filesize 설정값을 초과함(업로드 최대용량 초과)';
                break;
            case 2:
                echo 'Form에서 설정된 MAX_FILE_SIZE 설정값을 초과함(업로드 최대용량 초과)';
                break;
            case 3:
                echo '파일 일부만 업로드 됨';
                break;
            case 4:
                echo '업로드된 파일이 없음';
                break;
            case 6:
                echo '사용가능한 임시폴더가 없음';
                break;
            case 7:
                echo '디스크에 저장할수 없음';
                break;
            case 8:
                echo '파일 업로드가 중지됨';
                break;
            default:
                echo '시스템 오류가 발생';
                break;
        } // switch
        echo '</strong></p>';
    } // if
// 7.임시파일이 존재하는 경우 삭제
    if (file_exists($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name'])) {
        unlink($_FILES['upload']['tmp_name']);
    }
?>