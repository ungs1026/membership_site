<?php

session_start();

$ses_id = (isset($_SESSION['ses_id']) && $_SESSION['ses_id'] != '') ? $_SESSION['ses_id'] : '';
$ses_level = (isset($_SESSION['ses_level']) && $_SESSION['ses_level'] != '') ? $_SESSION['ses_level'] : '';

if ($ses_id == '' || $ses_level != 10) {
	$arr = ['result' => 'access_denied'];
	die(json_encode($arr));
}

include_once '../../DB/dbconfig.php';
include_once '../../includes/member.php';

$idx = (isset($_POST['idx']) && $_POST['idx'] != '' && is_numeric($_POST['idx'])) ? $_POST['idx'] : '';

if ($idx == '') {
	$arr = ['result' => 'empty_idx'];
	die(json_encode($arr));
}

$mem = new Member($db);
$mem->member_del($idx);

$arr = ['result' => 'member_del_success'];
die(json_encode($arr));
