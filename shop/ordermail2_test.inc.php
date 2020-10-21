<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');

//if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불
//가
//------------------------------------------------------------------------------
// 운영자에게 메일보내기
//------------------------------------------------------------------------------
//ob_start();
//include G5_SHOP_PATH.'/mail/orderupdate1.mail.php';
//$content = ob_get_contents();
//ob_end_clean();
$od_name = '홍동우';
$od_email = 'nokr82@gmail.com';
$buyname = 'nokr82@naver.com';
$subject = 'test입니다333.';
$content = 'test입니다333';
//네이버메일은안됨 확인된건 구글 vmp@vmp.company이게아니면 전송이안됨
//mailer($od_name, $od_email, 'nokr82@gmail.com', $subject, $content, 0, "", "", "");
mailer($od_name, $od_email, $od_email, $subject, $content, 1);

// 메일 보내기 (파일 여러개 첨부 가능)
// type : text=0, html=1, text+html=2

//------------------------------------------------------------------------------
// 운영자에게 메일보내기
//------------------------------------------------------------------------------
$subject = $config['cf_title'] . ' - 주문 알림 메일 (' . $od_name . ')';
ob_start();
include G5_SHOP_PATH . '/mail/orderupdate1.mail.php';
$content = ob_get_contents();
ob_end_clean();

mailer($od_name, $od_email, $buyname, $subject, $content, 1);
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// 주문자에게 메일보내기
//------------------------------------------------------------------------------
$subject = $config['cf_title'] . ' - 주문 내역 안내 메일';
ob_start();
include G5_SHOP_PATH . '/mail/orderupdate2.mail.php';
$content = ob_get_contents();
ob_end_clean();

mailer($od_name, $od_email, $buyname, $subject, $content, 1);

?>