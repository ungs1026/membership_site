<?php

$server = 'localhost';
$user = 'root';
$password = '';
$db = 'ytmembership';

try {
	$db = new PDO("mysql:host={$server};dbname={$db}", $user, $password);

	// Prepared Statement를 지원하지 않는 경우 데이터베이스의 기능을 사용하도록 해줌
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); // 쿼리 버퍼링을 활성화
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // PDO 객체가 에러를 처리하는 방식

	// echo "DB Connection";
} catch (PDOException $e) {
	echo $e->getMessage();
}

define('DOCUMENT_ROOT'	, $_SERVER['DOCUMENT_ROOT'] .'/membership');
define('ADMIN_DIR'			, DOCUMENT_ROOT		.'/admin');
define('DATA_DIR'				, DOCUMENT_ROOT		.'/data');
define('PROFILE_DIR'		, DATA_DIR 				.'/profile');
define('BOARD_DIR'			, DATA_DIR				.'/board');
define('BOARD_WEB_DIR'	, 'data/board');
define('POPUP_DIR'			, DATA_DIR				.'/popup');
define('POPUP_WEB_DIR'	, 'data/popup');