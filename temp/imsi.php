<?php

include '../DB/dbconfig.php';
include '../includes/member.php';

// 아이디 중복 테스트

$email = 'wodnd565@gmail.com';

$mem = new Member($db);

if ($mem->email_exists($email)) {
	echo 'email가 중복됩니다.';
} else {
	echo '사용할 수 있는 email 입니다.';
}