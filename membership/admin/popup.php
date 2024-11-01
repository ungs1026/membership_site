<?php
$g_title = '네카라쿠배';
$js_array = ['js/popup.js'];

$menu_code = 'popup';

include 'includes/common.php';
include 'includes/part/inc_header.php';
include '../DB/dbconfig.php';
include '../includes/popup.php'; // 게시판 관리 Class

$popup = new Popup($db);
$popupArr = $popup->list();

?>

<main class="border rounded-5 p-5" style="height: calc(100vh - 200px);">
	<div class="container">
		<h3>팝업 관리</h3>
	</div>

	<table class="table table-border">
		<tr>
			<th>번호</th>
			<th>팝업 이름</th>
			<th>쿠키만료</th>
			<th>시작일</th>
			<th>종료일</th>
			<th>등록일시</th>
			<th>관리</th>
		</tr>

		<?php
		foreach ($popupArr as $row) {
			// 데이터 정리
			// $row['create_at'] = substr($row['create_at'], 0, 16);
		?>
			<tr>
				<td><?= $row['idx'] ?></td>
				<td><?= $row['name'] ?></td>
				<td><?= $row['cookie'] ?></td>
				<td><?= $row['sdate'] ?></td>
				<td><?= $row['edate'] ?></td>
				<td><?= $row['create_at'] ?></td>
				<td>
					<button class="btn btn-success btn-sm btn_popup_view"
						data-idx="<?= $row['idx']; ?>">보기</button>
					<button class="btn btn-primary btn-sm btn_popup_edit"
						data-bs-toggle="modal" data-bs-target="#popup_create_modal"
						data-idx="<?= $row['idx']; ?>">수정</button>
					<button class="btn btn-danger btn-sm btn_popup_delete" data-idx="<?= $row['idx']; ?>">삭제</button>
				</td>
			</tr>
		<?php } ?>
	</table>

	<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#popup_create_modal" id="btn_create_modal">팝업 생성</button>

	</div>
</main>

<style>
  .close {
    width: 11px; height: 11px; background-color: white; margin-top: 7px; margin-right: 7px;
    clip-path: polygon(20% 0%, 0% 20%, 30% 50%, 0% 80%, 20% 100%, 50% 70%, 80% 100%, 100% 80%, 70% 50%, 100% 20%, 80% 0%, 50% 30%);
  }
  #pop1 {
    border: 1px solid #999; position: absolute; left: 5px; top: 5px; z-index: 1000;
		display: none;
  }
</style>

<div id="pop1">
  <img src="../data/board/a202410301823362182.jpg" alt="">  
  <div class="d-flex gap-2 bg-dark text-white">
    <input type="checkbox" name="chk" class="ms-auto">
    <span id="cookie_term">하루 동안 이 창을 다시 열지 않음</span>
    <span class="close"></span>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="popup_create_modal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="modalTitle">팝업 생성</h1>
				<input type="hidden" name="mode" id="popup_mode" value="">
				<input type="hidden" name="idx" id="popup_idx" value="">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">

				<div class="d-flex gap-2">
					<input type="text" id="popup_title" class="form-control" placeholder="팝업 이름">
					<select name="use" id="popup_use" class="form-select">
						<option value="">팝업사용 여부</option>
						<option value="1">팝업사용함</option>
						<option value="0">팝업사용안함</option>
					</select>
				</div>

				<div class="mt-2">
					<input type="text" id="popup_link" class="form-control" placeholder="팝업 링크">
				</div>

				<div class="mt-2 d-flex gap-2">
					<input type="text" id="pop_x" class="form-control" placeholder="팝업위치 left">
					<input type="text" id="pop_y" class="form-control" placeholder="팝업위치 top">
				</div>

				<div class="mt-2 d-flex gap-2">
					<input type="date" id="sdate" class="form-control" placeholder="팝업시작일">
					<input type="date" id="edate" class="form-control" placeholder="팝업종료일">
				</div>

				<div class="mt-2 d-flex gap-2">
					<input type="file" id="file" class="form-control" placeholder="팝업파일">
					<select name="cookie" id="cookie" class="form-select">
						<option value="day">쿠키만료 - 하루</option>
						<option value="week">쿠키만료 - 일주일</option>
						<option value="month">쿠키만료 - 한달</option>
					</select>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btn_popup_create">확인</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
			
		</div>
	</div>
</div>

<?php

include 'includes/part/inc_footer.php';
?>