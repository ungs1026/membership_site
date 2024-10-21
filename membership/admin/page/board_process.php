<?php

include_once '../includes/common.php';
include_once '../../DB/dbconfig.php';
include_once '../../includes/board_manage.php';

$board_title = (isset($_POST['board_title']) && $_POST['board_title'] != '') ? $_POST['board_title'] : '';
$board_btype = (isset($_POST['board_btype']) && $_POST['board_btype'] != '') ? $_POST['board_btype'] : '';
$mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';
$idx = (isset($_POST['idx']) && $_POST['idx'] != '') ? $_POST['idx'] : '';

if ($mode == '') {
	$arr = ["result" => "mode_empty"];
	die(json_encode($arr));
}

$board = new BoardManage($db);


if ($mode == 'input') {

	if ($board_title == '') {
		$arr = ["result" => "title_empty"];
		die(json_encode($arr));
	}

	if ($board_btype == '') {
		$arr = ["result" => "btype_empty"];
		die(json_encode($arr));
	}

	// 게시판 코드 생성
	$bcode = $board->bcode_create();

	// 게시판 생성
	$boardArr = [
		"name" => $board_title,
		"bcode" => $bcode,
		"btype" => $board_btype,
	];

	$board->create($boardArr);
	$arr = ["result" => "success"];
	die(json_encode($arr));
} else if ($mode == 'delete') {
	// 게시판 삭제
	$board->delete($idx);
	$arr = ["result" => "success"];
	die(json_encode($arr));
} else if ($mode == 'edit') {
	// 게시판 수정
	if ($idx == '') {
		$arr = ["result" => "empty_idx"];
		die(json_encode($arr));
	}

	if ($board_title == '') {
		$arr = ["result" => "title_empty"];
		die(json_encode($arr));
	}

	if ($board_btype == '') {
		$arr = ["result" => "btype_empty"];
		die(json_encode($arr));
	}

	// 게시판 수정
	$boardArr = [
		"name" => $board_title,
		"btype" => $board_btype,
		"idx" => $idx,
	];

	$board->update($boardArr);

	$arr = ["result" => "edit_success"];
	die(json_encode($arr));

} else if ($mode == 'getInfo') {
	// 게시판 정보확인
	if ($idx == '') {
		$arr = ["result" => "empty_idx"];
		die(json_encode($arr));
	}

	$row = $board->getInfo($idx);

	$arr = [
		"result" => "success",
		"list" => $row
	];
	die(json_encode($arr));
}
