<?php

include 'includes/common.php';
include_once '../DB/dbconfig.php';
include_once '../includes/member.php';

$mem = new Member($db);

$rs = $mem->getAllData();

// 1. header() ... => 사용
// 2. PHPExcel 라이브러리 .xls .xlsx 완벽한 엑셀 포맷

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=member.xls");
header("Content-Description:PHP8 Generated Data");


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		.title {
			font-size: 25px;
			text-align: center;
			font-weight: 900;
		}
	</style>
</head>

<body>
	<table border="1">
		<tr>
			<td colspan="6" class="title">회원목록</td>
		</tr>
		<tr>
			<td>아이디</td>
			<td>이름</td>
			<td>이메일</td>
			<td>우편번호</td>
			<td>주소</td>
			<td>등록일시</td>
		</tr>
		<?php
		foreach ($rs as $row) {
			echo '
					<tr>
						<td>' . $row['id'] . '</td>
						<td>' . $row['name'] . '</td>
						<td>' . $row['email'] . '</td>
						<td>' . $row['zipcode'] . '</td>
						<td>' . $row['addr1'] . ' ' . $row['addr2'] . '</td>
						<td>' . $row['create_at'] . '</td>
					</tr>
				';
		}
		?>

	</table>
</body>

</html>