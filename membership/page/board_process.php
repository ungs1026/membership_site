<?php
// $err_array = error_get_last();

// $_SERVER['CONTENT_LENGTH'];
if (isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > (int) ini_get('post_max_size') * 1024 * 1024) {
	die(json_encode(["result" => "post_size_exceed"]));
}


include_once '../DB/dbconfig.php';
include_once '../includes/part/common.php';
include_once '../includes/board.php'; // 게시판 클래스
include_once '../includes/member.php'; // 회원 클래스

$mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';
$bcode = (isset($_POST['bcode']) && $_POST['bcode'] != '') ? $_POST['bcode'] : '';
$subject = (isset($_POST['subject']) && $_POST['subject'] != '') ? $_POST['subject'] : '';
$content = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
$idx = (isset($_POST['idx']) && $_POST['idx'] != '' && is_numeric($_POST['idx'])) ? $_POST['idx'] : '';
$th = (isset($_POST['th']) && $_POST['th'] != '' && is_numeric($_POST['th'])) ? $_POST['th'] : '';

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

	// 다중 파일 첨부
	/*[files] => Array
	(
			[name] => Array
					(
							[0] => 1 (1).jpg
							[1] => 1 (2).jpg
					)

			[type] => Array
					(
							[0] => image/jpeg
							[1] => image/jpeg
					)

			[tmp_name] => Array
					(
							[0] => D:\xampp\tmp\php671E.tmp
							[1] => D:\xampp\tmp\php672F.tmp
					)
	)

	Array
	(
		[files] => Array
			(
					[name] => 0517-1.jpg
					[type] => image/jpeg
					[tmp_name] => D:\xampp\tmp\phpEC36.tmp
					[error] => 0
					[size] => 8686 46
			)
	)
	*/


	// 파일 첨부
	// $_FILES[]
	$file_list_str = '';
	if (isset($_FILES['files'])) {
		$file_list_str = $board->file_attach($_FILES['files'], 3);
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
		'files' => $file_list_str,
		'ip' => $_SERVER['REMOTE_ADDR'],
	];

	$board->input($arr);
	die(json_encode(["result" => "success"]));
} else if ($mode == 'each_file_del') {
	if ($idx == '') {
		$arr = ["result" => "empty_idx"];
		die(json_encode($arr));
	}

	if ($th == '') {
		$arr = ["result" => "empty_th"];
		die(json_encode($arr));
	}

	$file = $board->getAttachFile($idx, $th);

	$each_files = explode('|', $file);

	// BOARD_DIR .'/'. $each_files[0] => 삭제할 파일
	if (file_exists(BOARD_DIR . '/' . $each_files[0])) {
		unlink(BOARD_DIR . '/' . $each_files[0]);
	}

	$row = $board->view($idx);

	// 파일
	$files = explode('?', $row['files']);
	$tmp_arr = [];
	foreach ($files as $key => $val) {
		if ($key == $th) {
			continue;
		}
		$tmp_arr[] = $val;
	}

	$files = implode('?', $tmp_arr); // 새로 조합된 파일리스트 문자열

	// 다운로드 횟수
	$tmp_arr = [];
	$downs = explode('?', $row['downhit']);
	foreach ($downs as $key => $val) {
		if ($key == $th) {
			continue;
		}
		$tmp_arr[] = $val;
	}
	$downs = implode('?', $tmp_arr); // 새로 조합된 다운로드 수 문자열

	$board->updateFileList($idx, $files, $downs);

	$arr = ["result" => "success"];
	die(json_encode($arr));
} else if ($mode == 'file_attach') {
	// 수정에서 개별 파일 첨부하기
	$file_list_str = '';
	if (isset($_FILES['files'])) {
		$file_cnt = 1;
		$file_list_str = $board->file_attach($_FILES['files'], $file_cnt);
	} else {
		$arr = ["result" => "empty_files"];
		die(json_encode($arr));
	}

	// 파일 존재 여부 확인
	$row = $board->view($idx);

	if ($row['files'] != '') {
		$files = $row['files'] . '?' . $file_list_str;
	} else {
		$files = $file_list_str;
	}

	if ($row['downhit'] != '') {
		$downs = $row['downhit'] . '?0';
	} else {
		$downs = '';
	}

	$board->updateFileList($idx, $files, $downs);

	$arr = ["result" => "success"];
	die(json_encode($arr));
} else if ($mode == 'edit') {

	$row = $board->view($idx);
	if ($row['id'] != $ses_id) {
		die(json_encode(["result" => "permission_denied"]));
	}

	$old_image_arr = $board->extract_image($row['content']);

	// 이미지 정규화
	preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $matches);
	$current_image_arr = [];
	foreach ($matches[1] as $key => $row) {
		if (substr($row, 0, 5) != 'data:') {
			$current_image_arr[] = $row;
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
	}

	// $old_image_arr
	// $current_image_arr

	$diff_img_arr = array_diff($old_image_arr, $current_image_arr);
	foreach ($diff_img_arr as $value) {
		unlink("../" . $value);
	}

	if ($subject == '') {
		die(json_encode(["result" => "empty_subject"]));
	}

	if ($content == '' || $content == '<p><br></p>') {
		die(json_encode(["result" => "empty_content"]));
	}

	$arr = [
		'idx' => $idx,
		'subject' => $subject,
		'content' => $content
	];

	$board->edit($arr);

	die(json_encode(["result" => "success"]));
} else if ($mode == 'delete') {
	// db에서 해당 row 삭제
	// 첨부파일 삭제
	// 본문에 이미지가 있는 경우 본문 이미지도 삭제

	$row = $board->view($idx);
	// 본문 이미지 삭제
	$img_arr = $board->extract_image($row['content']);
	foreach ($img_arr as $value) {
		if (file_exists("../" . $value)) {
			unlink("../" . $value);
		}
	}

	if ($row['files'] != '') {
		// 첨부파일 삭제
		$filelist = explode('?', $row['files']);
		foreach ($filelist as $value) {
			list($file_src,) = explode('|', $value);
			unlink(BOARD_DIR . '/' . $file_src);
		}
	}

	$board->delete($idx);

	die(json_encode(["result" => "success"]));
}
