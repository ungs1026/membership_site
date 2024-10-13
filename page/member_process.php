<?php

include '../DB/dbconfig.php';
include '../includes/member.php';

$mem = new Member($db);


$id = (isset($_POST['id']) && $_POST['id'] != '') ? $_POST['id'] : '';
$password = (isset($_POST['password']) && $_POST['password'] != '') ? $_POST['password'] : '';
$email = (isset($_POST['email']) && $_POST['email'] != '') ? $_POST['email'] : '';
$name = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : '';
$zipcode = (isset($_POST['zipcode']) && $_POST['zipcode'] != '') ? $_POST['zipcode'] : '';
$addr1 = (isset($_POST['addr1']) && $_POST['addr1'] != '') ? $_POST['addr1'] : '';
$addr2= (isset($_POST['addr2']) && $_POST['addr2'] != '') ? $_POST['addr2'] : '';

$mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';

// 아이디 중복 확인
if ($mode == 'id_chk') {
	if ($id == '') {
		die(json_encode(['result' => 'empty_id']));
	}

	if ($mem->id_exists($id)) {
		die(json_encode(['result' => 'fail']));
	} else {
		die(json_encode(['result' => 'success']));
	}
	// 이메일 중복 확인
} else if ($mode == 'email_chk') {
	if ($email == '') {
		die(json_encode(['result' => 'empty_email']));
	}

	if ($mem->email_exists($email)) {
		die(json_encode(['result' => 'fail']));
	} else {
		die(json_encode(['result' => 'success']));
	}
} else if ($mode == 'input') {
	// Profile Image 처리
	$arr = explode('.', $_FILES['photo']['name']);
	$ext = end($arr);
	$photo = $id .'.'. $ext;

	copy($_FILES['photo']['tmp_name'], '../data/profile/'.$photo);

// 	Array
// (
//   [photo] => Array
//     (
//       [name] => 주석 2024-10-09 000040.png
//       [type] => image/png
//       [tmp_name] => D:\xampp\tmp\phpF5D.tmp
//       [error] => 0
//       [size] => 1235043
//     )
// )

	$arr = [
		'id' => $id,
		'name' => $name,
		'password' => $password,
		'email' => $email,
		'zipcode' => $zipcode,
		'addr1' => $addr1,
		'addr2' => $addr2,
		'photo' => $photo,
	];

	$mem->input($arr);
}
