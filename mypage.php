<?php
session_start();

$ses_id = (isset($_SESSION['ses_id']) && $_SESSION['ses_id'] != '') ? $_SESSION['ses_id'] : '';

if ($ses_id == '') {
	echo "<script>
	alert('로그인 후 접근 가능한 메뉴입니다.');
	self.location.href='./index.php';
	</script>
	";
	exit;	
}

include_once './DB/dbconfig.php';
include_once './includes/member.php';

$mem = new Member($db);

$memArr = $mem->getInfo($ses_id);

// Array
// (
//     [idx] => 3
//     [id] => wodnd
//     [name] => guest
//     [email] => www@gggg.ggg
//     [password] => $2y$10$76ZWexVPPWuKlxqtWGyCkeFyGqqgN.cHshnjuBMW9yv.xV033iTpG
//     [zipcode] => 10403
//     [addr1] => 경기 고양시 일산동구 호수로 596 (장항동, 엠비씨드림센터)
//     [addr2] => 123
//     [photo] => wodnd.png
//     [create_at] => 2024-10-14 21:36:28
//     [login_dt] => 2024-10-15 16:01:11
//     [ip] => ::1
// )

$js_array = ['js/mypage.js'];

$g_title = 'My Page';

include 'includes/part/inc_header.php';
?>

<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

<main class="w-50 mx-auto border rounded-5 p-5">
	<h1 class="text-center">회원정보수정</h1>

	<!-- enctype => 이미지 때문 -->
	<form name="input_form" method="post" enctype="multipart/form-data" action="page/member_process.php" autocomplete="off">
		<input type="hidden" name="mode" value="edit">
		<input type="hidden" name="email_chk" value="0">
		<input type="hidden" name="old_email" value="<?= $memArr['email']; ?>">
		<input type="hidden" name="old_photo" value="<?= $memArr['photo']; ?>">
		<div class="d-flex gap-2 align-items-end">
			<div>
				<label for="f_id" class="form-label">아이디</label>
				<input type="text" name="id" readonly class="form-control" id="f_id" value="<?= $memArr['id']; ?>">
			</div>
		</div>

		<div class="d-flex mt-3 gap-2 align-items-end">
			<div>
				<label for="f_name" class="form-label">이름</label>
				<input type="text" name="name" class="form-control" id="f_name" value="<?= $memArr['name']; ?>">
			</div>
		</div>

		<div class="d-flex mt-3 gap-2 justify-content-between">
			<div class="flex-grow-1">
				<label for="f_pw" class="form-label">비밀번호</label>
				<input type="password" name="password" class="form-control" id="f_pw" placeholder="비밀번호를 입력해주세요.">
			</div>
			<div class="flex-grow-1">
				<label for="f_pw2" class="form-label">비밀번호 확인</label>
				<input type="password" name="password2" class="form-control" id="f_pw2" placeholder="비밀번호를 입력해주세요.">
			</div>
		</div>

		<div class="d-flex mt-3 gap-2 align-items-end">
			<div class="flex-grow-1">
				<label for="f_email" class="form-label">이메일</label>
				<input type="email" name="email" class="form-control" id="f_email" value="<?= $memArr['email']; ?>">
			</div>
			<button type="button" class="btn btn-secondary" id="btn_email_check">이메일 중복확인</button>
		</div>

		<div class="d-flex gap-2 mt-3 align-items-end">
			<div>
				<label for="f_zipcode" class="form-label">우편번호</label>
				<input type="text" name="zipcode" id="f_zipcode" readonly class="form-control" value="<?= $memArr['zipcode']; ?>" maxlength="5" minlength="5">
			</div>
			<button type="button" class="btn btn-secondary" id="btn_zipcode">우편번호 찾기</button>
		</div>

		<div class="d-flex mt-3 gap-2 justify-content-between">
			<div class="flex-grow-1">
				<label for="f_addr1" class="form-label">주소</label>
				<input type="text" class="form-control" id="f_addr1" name="addr1" value="<?= $memArr['addr1']; ?>">
			</div>
			<div class="flex-grow-1">
				<label for="f_addr2" class="form-label">상세 주소</label>
				<input type="text" class="form-control" id="f_addr2" name="addr2" value="<?= $memArr['addr2']; ?>">
			</div>
		</div>

		<div class="mt-3 d-flex gap-5">
			<div>
				<label for="f_photo" class="form-label">프로필이미지</label>
				<input type="file" name="photo" id="f_photo" class="form-control">
			</div>

			<?php
				if ($memArr['photo']) {
					echo '<img src="data/profile/'.$memArr['photo'].'" id="f_preview" class="w-25" alt="photo img" style="filter: drop-shadow(0 1px 2px black);">';
				} else {
					echo '<img src="sources/leaf.png" id="f_preview" class="w-25" alt="photo img" style="filter: drop-shadow(0 1px 2px black);">';
				}
			?>
			
		</div>

		<div class="mt-3 d-flex gap-2">
			<button type="button" class="btn btn-primary flex-grow-1" id="btn_submit">수정확인</button>
			<button type="button" class="btn btn-secondary flex-grow-1">수정취소</button>
		</div>
	</form>
</main>

<?php include 'includes/part/inc_footer.php'; ?>