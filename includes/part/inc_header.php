<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $g_title ?></title>

	<!-- Bootstrap CSS JS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=ADLaM+Display&family=Jua&family=Nanum+Gothic:wght@400;700;800&display=swap" rel="stylesheet">

	<?php
	if (isset($js_array)) {
		foreach ($js_array as $var) {
			echo '<script src="' . $var . '?v=' . date('YmdHis') . '"></script>' . PHP_EOL;
		}
	}
	?>
</head>

<body>

	<div class="container">
		<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
			<a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
				<img src="./sources/logo.svg" alt="Logo" style="width: 2rem;" class="me-2">
				<span class="fs-4">네카라쿠배</span>
			</a>

			<ul class="nav nav-pills">
				<li class="nav-item"><a href="#" class="nav-link active" aria-current="page">Home</a></li>
				<li class="nav-item"><a href="#" class="nav-link">회사소개</a></li>
				<li class="nav-item"><a href="#" class="nav-link">회원가입</a></li>
				<li class="nav-item"><a href="#" class="nav-link">게시판</a></li>
				<li class="nav-item"><a href="#" class="nav-link">로그인</a></li>
			</ul>
		</header>