<?php

include_once '../DB/dbconfig.php';
include_once '../includes/part/common.php';
include_once '../includes/member.php'; // 회원 클래스
include '../includes/comment.php'; // 댓글 클래스

if ($ses_id == '') {
	die(json_encode(['result' => 'not_login']));
}

$mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';
$idx = (isset($_POST['idx']) && $_POST['idx'] != '' && is_numeric($_POST['idx'])) ? $_POST['idx'] : '';
$pidx = (isset($_POST['pidx']) && $_POST['pidx'] != '' && is_numeric($_POST['pidx'])) ? $_POST['pidx'] : '';
$content = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';

if ($mode == '') {
	die(json_encode(['result' => 'empty_mode']));
}

$comment = new Comment($db);

// 댓글 소유권 확인 ( 인가자만 수정 삭제가 가능하게 처리 )
if($mode == 'edit' || $mode == 'delete') {
  if($idx == '') {
    $arr = ["result" => "empty_idx"];
    die(json_encode($arr));
  }

  $commentRow = $comment->getInfo($idx);

  if($commentRow['id'] != $ses_id) {
    $arr = ["result" => "access_denied"];
    die(json_encode($arr));
  }
}

// 댓글 등록
if ($mode == 'input') {
	if ($pidx == '') {
		die(json_encode(['result' => 'empty_pidx']));
	}

	if ($content == '') {
		die(json_encode(['result' => 'empty_content']));
	}

	$arr = [
		'pidx' => $pidx,
		'content' => $content,
		'id' => $ses_id
	];
	$comment->input($arr);

	die(json_encode(['result' => 'success']));
} else if ($mode == 'edit') {
  if($content == '') {
    $arr = ["result" => "empty content"];
    die(json_encode($arr));
  }

  $arr = [ 
		"idx" => $idx, 
		"content" => $content, 
		"id" => $ses_id
	];
  
  $comment->update($arr);

  $arr = ["result" => "success"];
  die(json_encode($arr));

} else if ($mode == 'delete') {
	if ($pidx == '') {
		die(json_encode(['result' => 'empty_pidx']));
	}

	$comment->delete($pidx, $idx);
	die(json_encode(['result' => 'success']));
}
