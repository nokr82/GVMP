<?php
if (!defined('_GNUBOARD_')) exit;

include_once(G5_PHPMAILER_PATH . '/PHPMailerAutoload.php');

// 메일 보내기 (파일 여러개 첨부 가능)
// type : text=0, html=1, text+html=2
function mailer($fname, $fmail, $to, $subject, $content, $type = 0, $file = "", $cc = "", $bcc = "")
{
    global $config;
    global $g5;

    // 메일발송 사용을 하지 않는다면
    if (!$config['cf_email_use']) return;

    if ($type != 1)
        $content = nl2br($content);

    $mail = new PHPMailer(); // defaults to using php "mail()"
    $mail->SMTPDebug = 2; // 디버깅 설정
    if (defined('G5_SMTP') && G5_SMTP) {
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = G5_SMTP; // SMTP server
        if (defined('G5_SMTP_PORT') && G5_SMTP_PORT)
            $mail->Port = G5_SMTP_PORT;
    }
    $mail->CharSet = 'UTF-8';
    $mail->From = $fmail;
    $mail->FromName = $fname;
    $mail->Subject = $subject;
    $mail->AltBody = ""; // optional, comment out and test
    $mail->msgHTML($content);
    $mail->addAddress($to);
    if ($cc)
        $mail->addCC($cc);
    if ($bcc)
        $mail->addBCC($bcc);
    //print_r2($file); exit;
    if ($file != "") {
        foreach ($file as $f) {
            $mail->addAttachment($f['path'], $f['name']);
        }
    }
    return $mail->send();
}



//20200615홍동우  메일보내기 쓰지말것 제한걸려있음
function mailer2($fname, $fmail, $to, $subject, $content, $type = 0, $file = "", $cc = "", $bcc = "")
{
    global $config;
    global $g5;

    // 메일발송 사용을 하지 않는다면
    if (!$config['cf_email_use']) return;

    if ($type != 1)
        $content = nl2br($content);

    $mail = new PHPMailer(); // defaults to using php "mail()"
    try {

        $mail->SMTPDebug = 2; // 디버깅 설정
        $mail->isSMTP(); // SMTP 사용 설정

// 지메일일 경우 smtp.gmail.com, 네이버일 경우 smtp.naver.com

        $mail->Host = "smtp.naver.com";               // 네이버의 smtp 서버
        $mail->SMTPAuth = true;                         // SMTP 인증을 사용함
        $mail->Username = "vmp4620@naver.com";    // 메일 계정 (지메일일경우 지메일 계정)
        $mail->Password = "vmp40364620";                  // 메일 비밀번호
        $mail->SMTPSecure = "ssl";                       // SSL을 사용함
        $mail->Port = 465;                                  // email 보낼때 사용할 포트를 지정
        $mail->CharSet = "utf-8"; // 문자셋 인코딩


// 보내는 메일
        $mail->setFrom('vmp4620@naver.com', "VMP");
// 받는 메일
        $mail->addAddress($to, $fname);

// 메일 내용
        $mail->isHTML(true); // HTML 태그 사용 여부
        $mail->Subject = $subject;  // 메일 제목
        $mail->Body = $content;     // 메일 내용

// Gmail로 메일을 발송하기 위해서는 CA인증이 필요하다.
// CA 인증을 받지 못한 경우에는 아래 설정하여 인증체크를 해지하여야 한다.
        $mail->SMTPOptions = array(
            "ssl" => array(
                "verify_peer" => false
            , "verify_peer_name" => false
            , "allow_self_signed" => true
            )
        );
// 메일 전송
        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error : ", $mail->ErrorInfo;
    }
}


// 파일을 첨부함
function attach_file($filename, $tmp_name)
{
    // 서버에 업로드 되는 파일은 확장자를 주지 않는다. (보안 취약점)
    $dest_file = G5_DATA_PATH . '/tmp/' . str_replace('/', '_', $tmp_name);
    move_uploaded_file($tmp_name, $dest_file);
    $tmpfile = array("name" => $filename, "path" => $dest_file);
    return $tmpfile;
}

?>