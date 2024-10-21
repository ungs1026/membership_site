<?php

include_once '../DB/dbconfig.php';
include_once '../includes/part/common.php';
include_once '../includes/board.php'; // 게시판 클래스
include_once '../includes/member.php'; // 회원 클래스

$mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';
$bcode = (isset($_POST['bcode']) && $_POST['bcode'] != '') ? $_POST['bcode'] : '';
$subject = (isset($_POST['subject']) && $_POST['subject'] != '') ? $_POST['subject'] : '';
$content = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';

if ($mode == '') {
	$arr = ["result" => "empty_mode"];
	die(json_encode($arr));
}

if ($bcode == '') {
	$arr = ["result" => "empty_bcode"];
	die(json_encode($arr));
}

$board = new Board($db);
$mem = new Member($db);

if ($mode == 'input') {

	// 이미지 정규화
	preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $matches);
	$img_array = [];
	foreach ($matches[1] as $key => $row) {
		if (substr($row, 0, 5) != 'data:') {
			continue;
		}
		list($type, $data) = explode(';', $row);
		list(, $data) = explode(',', $data);
		$data = base64_decode($data);
		list(, $ext) = explode('/', $type);
		$ext = ($ext == 'jpeg') ? 'jpg' : $ext;
		$filename = date('YmdHis') . '_' . $key . '.' . $ext;

		file_put_contents(BOARD_DIR . "/" . $filename, $data);

		$content = str_replace($row, BOARD_WEB_DIR . '/' . $filename, $content);
		$img_array[] = BOARD_WEB_DIR . '/' . $filename;
	}

	if ($subject == '') {
		die(json_encode(["result" => "empty_subject"]));
	}

	if ($content == '' || $content == '<p><br></p>') {
		die(json_encode(["result" => "empty_content"]));
	}

	// 파일 첨부
	// $_FILES[]
	if (isset($_FILES['files']) && $_FILES['files']['name'] != '') {
		$tmparr = explode('.', $_FILES['files']['name']);
		$ext = end($tmparr);
		$flag = rand(1000, 9999);
		$filename = 'a' . date('YmdHis') . $flag . '.' . $ext;
		$file_ori = $_FILES['files']['name'];
		$full_str = $filename . '|' . $file_ori;
	}

	$memArr = $mem->getInfo($ses_id);
	$name = $memArr['name'];

	// bcode , id, name, subject, content, ip
	$arr = [
		'bcode' => $bcode,
		'id' => $ses_id,
		'name' => $name,
		'subject' => $subject,
		'content' => $content,
		'files' => $full_str,
		'ip' => $_SERVER['REMOTE_ADDR'],
	];

	$board->input($arr);
	die(json_encode(["result" => "success"]));
}
