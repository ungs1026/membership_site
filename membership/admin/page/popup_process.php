<?php

include_once '../includes/common.php';
include_once '../../DB/dbconfig.php';
include_once '../../includes/popup.php';

$name  = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name']  : '';
$sdate = (isset($_POST['sdate']) && $_POST['sdate'] != '') ? $_POST['sdate'] : '';
$edate = (isset($_POST['edate']) && $_POST['edate'] != '') ? $_POST['edate'] : '';
$pop_x = (isset($_POST['pop_x']) && $_POST['pop_x'] != '') ? $_POST['pop_x'] : '0';
$pop_y = (isset($_POST['pop_y']) && $_POST['pop_y'] != '') ? $_POST['pop_y'] : '0';
$cookie = (isset($_POST['cookie']) && $_POST['cookie'] != '') ? $_POST['cookie'] : '';
$link  = (isset($_POST['link']) && $_POST['link'] != '') ? $_POST['link'] : '';
$use   = (isset($_POST['use']) && $_POST['use'] != '') ? $_POST['use'] : '';
$mode  = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';
$idx   = (isset($_POST['idx']) && $_POST['idx'] != '') ? $_POST['idx'] : '';

if ($mode == '') {
	$arr = ['result' => 'empty_mode'];
	die(json_encode(($arr)));
}

if ($mode == 'getInfo' || $mode == 'edit') {
	if ($idx == '') {
		$arr = ['result' => 'empty_idx'];
		die(json_encode(($arr)));
	}
}

if ($mode == 'input' || $mode == 'edit') {
	if ($name == '') {
		$arr = ['result' => 'empty_name'];
		die(json_encode(($arr)));
	}
	if ($sdate == '') {
		$arr = ['result' => 'empty_sdate'];
		die(json_encode(($arr)));
	}
	if ($edate == '') {
		$arr = ['result' => 'empty_edate'];
		die(json_encode(($arr)));
	}

	if ($use == '') {
		$arr = ['result' => 'empty_use'];
		die(json_encode(($arr)));
	}

	if ($cookie == '') {
		$arr = ['result' => 'empty_cookie'];
		die(json_encode(($arr)));
	}
}

$popup = new Popup($db);

if ($mode == 'input') {

	if (!isset($_FILES['file']['name']) || $_FILES['file']['name'] == '') {
		$arr = ['result' => 'empty_file'];
		die(json_encode(($arr)));
	}

	$filename = $popup->file_attach($_FILES['file']);

	$arr = [
		'name' => $name,
		'sdate' => $sdate,
		'edate' => $edate,
		'pop_x' => $pop_x,
		'pop_y' => $pop_y,
		'file' => $filename,
		'link' => $link,
		'cookie' => $cookie,
		'use' => $use
	];

	$popup->input($arr);

	$arr = ['result' => 'success'];
	die(json_encode(($arr)));
} else if ($mode == 'edit') {

	$filename = '';
	if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
		// 파일업로드
		$filename = $popup->file_attach($_FILES['file']);

		// 기존 파일 삭제
		$row = $popup->get_info($idx);
		if ($row['file'] != '') {
			// aaaadfkjlfdjf.png|실제파일이름.png
			list($file_src,) = explode('|', $row['file']);
			if ($file_src != '') {
				$popup->file_unlink($file_src);
			}
		}
		// 새로운 파일이름 -> db update 시켜줄...  $filename
	}

	$arr = [
		'name' => $name,
		'sdate' => $sdate,
		'edate' => $edate,
		'pop_x' => $pop_x,
		'pop_y' => $pop_y,
		'file' => $filename,
		'link' => $link,
		'cookie' => $cookie,
		'use' => $use,
		'idx' => $idx
	];

	$popup->edit($arr);

	$arr = ['result' => 'success'];
	die(json_encode(($arr)));
} else if ($mode == 'delete') {

	$row = $popup->get_info($idx);
	if ($row['file'] != '') {
		// aaaadfkjlfdjf.png|실제파일이름.png
		list($file_src,) = explode('|', $row['file']);
		if ($file_src != '') {
			$popup->file_unlink($file_src);
		}
	}

	$popup->delete($idx);
} else if ($mode == 'getInfo') {
	if ($idx == '') {
		die(json_encode(['result' => 'empty_idx']));
	}

	$row = $popup->get_info($idx);

	list($file,) = explode('|', $row['file']);
	$file = '../data/popup/' . $file;

	$arr = [
		'result' => 'success',
		'name' => $row['name'],
		'use' => $row['useok'],
		'pop_x' => $row['pop_x'],
		'pop_y' => $row['pop_y'],
		'sdate' => $row['sdate'],
		'edate' => $row['edate'],
		'file' => $file,
		'link' => $row['link'],
		'cookie' => $row['cookie']
	];

	die(json_encode($arr));
}
