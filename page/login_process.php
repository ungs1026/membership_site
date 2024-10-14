<?php

$id = (isset($_POST['id']) && $_POST['id'] != '') ? $_POST['id'] : '';
$pw = (isset($_POST['pw']) && $_POST['pw'] != '') ? $_POST['pw'] : '';


if ($id == '') {
  $arr = ['result' => 'empty_id'];
  die(json_encode($arr));
}

if ($pw == '') {
  $arr = ['result' => 'empty_pw'];
  die(json_encode($arr));
}

include '../DB/dbconfig.php';
include '../includes/member.php';

$mem = new Member($db);

if($mem->login($id, $pw)) {
  $arr = ['result' => 'login_success'];

  session_start();

  $_SESSION['ses_id'] = $id;

} else {
  $arr = ['result' => 'login_fail'];
}

die(json_encode($arr));

