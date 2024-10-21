<?php
$g_title = '네카라쿠배';
$js_array = ['js/member_edit.js'];

$menu_code = 'member';

include 'includes/common.php';
include 'includes/part/inc_header.php';
include '../DB/dbconfig.php';
include '../includes/member.php'; // 회원관리 Class
include '../includes/lib.php'; // 페이지네이션


$idx = (isset($_GET['idx']) && $_GET['idx'] != '' && is_numeric($_GET['idx'])) ? $_GET['idx'] : '';

if ($idx == '') {
	die("<script>
		alert('idx 값이 비었습니다.');
		history.go(-1);
		</script>");
}

$mem = new Member($db);

$row = $mem->getInfoFormIdx($idx);

?>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

<main class="w-75 mx-auto border rounded-5 p-5">
	<h1 class="text-center">회원정보 수정</h1>

	<!-- enctype => 이미지 때문 -->
	<form name="input_form" method="post" enctype="multipart/form-data" action="page/member_process.php" autocomplete="off">
		<input type="hidden" name="mode" value="edit">
		<input type="hidden" name="id" value="<?= $row['idx']; ?>">
		<input type="hidden" name="email_chk" value="0">
		<input type="hidden" name="old_email" value="<?= $row['email']; ?>">
		<input type="hidden" name="old_photo" value="<?= $row['photo']; ?>">
		<div class="d-flex gap-2 align-items-end">
			<div>
				<label for="f_id" class="form-label">아이디</label>
				<input type="text" name="id" value="<?= $row['id'] ?>" readonly class="form-control" id="f_id" placeholder="아이디를 입력해주세요.">
			</div>
		</div>

		<div class="d-flex mt-3 gap-2 align-items-end">
			<div class="w-25">
				<label for="f_name" class="form-label">이름</label>
				<input type="text" name="name" value="<?= $row['name']; ?>" class="form-control" id="f_name" placeholder="이름을 입력해주세요.">
			</div>
			<div class="w-25">
				<label for="f_name" class="form-label">LEVEL</label>
				<select name="" id="" class="form-select">
					<option value="1" <?php if($row['level'] == 1) echo" selected;" ?>>가입대기</option>
					<option value="2" <?php if($row['level'] == 2) echo" selected;" ?>>준회원</option>
					<option value="3" <?php if($row['level'] == 3) echo" selected;" ?>>정회원</option>
					<option value="10" <?php if($row['level'] == 10) echo" selected;" ?>>관리자</option>
				</select>
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
				<input type="email" name="email" value="<?= $row['email']; ?>" class="form-control" id="f_email" placeholder="이메일을 입력해주세요.">
			</div>
			<button type="button" class="btn btn-secondary" id="btn_email_check">이메일 중복확인</button>
		</div>

		<div class="d-flex gap-2 mt-3 align-items-end">
			<div>
				<label for="f_zipcode" class="form-label">우편번호</label>
				<input type="text" name="zipcode" value="<?= $row['zipcode']; ?>" id="f_zipcode" readonly class="form-control" maxlength="5" minlength="5">
			</div>
			<button type="button" class="btn btn-secondary" id="btn_zipcode">우편번호 찾기</button>
		</div>

		<div class="d-flex mt-3 gap-2 justify-content-between">
			<div class="flex-grow-1">
				<label for="f_addr1" class="form-label">주소</label>
				<input type="text" class="form-control" id="f_addr1" value="<?= $row['addr1']; ?>" name="addr1" placeholder="">
			</div>
			<div class="flex-grow-1">
				<label for="f_addr2" class="form-label">상세 주소</label>
				<input type="text" class="form-control" id="f_addr2" value="<?= $row['addr2']; ?>" name="addr2" placeholder="상세 주소를 입력해 주세요.">
			</div>
		</div>

		<div class="mt-3 d-flex gap-5">
			<div>
				<label for="f_photo" class="form-label">프로필이미지</label>
				<input type="file" name="photo" id="f_photo" class="form-control">
			</div>
			<?php if ($row['photo'] != '') {
				echo '<img src="../data/profile/' . $row['photo'] . '?v='.date('His').'" id="f_preview" class="w-25" alt="photo img" style="filter: drop-shadow(0 1px 2px black);">';
			} else {
				echo '<img src="../sources/leaf.png" id="f_preview" class="w-25" alt="photo img" style="filter: drop-shadow(0 1px 2px black);">';
			}
			?>
		</div>

		<div class="mt-3 d-flex gap-2">
			<button type="button" class="btn btn-primary flex-grow-1" id="btn_submit">수정확인</button>
			<button type="button" class="btn btn-secondary flex-grow-1">수정취소</button>
		</div>
	</form>
</main>

<?php
include 'includes/part/inc_footer.php';
?>