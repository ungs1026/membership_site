<?php
include_once '../DB/dbconfig.php';
include_once '../includes/member.php';

$mem = new member($db);
$mem->logout();