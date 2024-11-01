<?php
include_once './includes/part/common.php';
include_once './DB/dbconfig.php';
include_once './includes/board.php'; // 게시판 클래스
include_once './includes/lib.php'; // 페이지네이션
include_once './includes/comment.php'; // 댓글 클래스

$bcode = (isset($_GET['bcode']) && $_GET['bcode'] != '') ? $_GET['bcode'] : '';
$idx = (isset($_GET['idx']) && $_GET['idx'] != '' && is_numeric($_GET['idx'])) ? $_GET['idx'] : '';

if ($bcode == '') {
	die("
	<script>
	alert('게시판 코드가 누락되었습니다.');
	history.go(-1);
	</script>
	");
}

if ($idx == '') {
	die("
	<script>
	alert('게시물 번호가 누락되었습니다.');
	history.go(-1);
	</script>
	");
}

// 게시판 목록
include_once './includes/board_manage.php';
$boardm = new BoardManage($db);
$boardArr = $boardm->list();
$board_name = $boardm->getBoardName($bcode);

$board = new Board($db); // 게시판 클래스
$menu_code = 'board';
$js_array = ['js/board_view.js'];

$g_title = $board_name;

$boardRow = $board->view($idx);

if ($boardRow == null) {
	die("
	<script>
	alert('존재하지 않는 게시물 입니다.');
	history.go(-1);
	</script>
	");
}

// 댓글 목록
$comment = new Comment($db);
$commnentRs = $comment->list($idx);


// $_SERVER['REMOTE_ADDR'] : 지금 접속한 유저
if ($boardRow['last_reader'] != $_SERVER['REMOTE_ADDR']) {
	$board->hitInc($idx);
	$board->updateLastReader($idx, $_SERVER['REMOTE_ADDR']);
}

// 다운로드 횟수 저장 배열
$downhit_arr = explode('?', $boardRow['downhit']);

include 'includes/part/inc_header.php';

?>
<style>
	.tr {
		cursor: pointer;
	}
</style>

<main class="w-100 mx-auto border rounded-2 p-5">
	<h1 class="text-center"><?= $board_name; ?></h1>

	<div class="vstack w-75 mx-auto">
		<div class="p-3">
			<span class="h3 fw-bolder"><?= $boardRow['subject'] ?></span>
		</div>
		<div class="d-flex border border-start-0 border-end-0 border-top-0 border-bottom-1">
			<span><?= $boardRow['name'] ?></span>
			<span class="ms-5 me-auto"><?= $boardRow['hit'] ?>회</span>
			<span><?= $boardRow['create_at'] ?></span>
		</div>
		<div class="p-3">
			<?= $boardRow['content'] ?>

			<?php
			// 첨부파일 출력
			if ($boardRow['files'] != '') {
				$filelist = explode('?', $boardRow['files']);

				if ($boardRow['downhit'] == '') {
					$downhit_arr = array_fill(0, count($filelist), 0);
				}

				$th = 0;
				foreach ($filelist as $file) {
					list($file_source, $file_name) = explode('|', $file);

			?>
					<a href="./page/board_download.php?idx=<?= $idx ?>&th=<?= $th ?>"><?= $file_name ?></a>&nbsp;&nbsp;(Down : <?= $downhit_arr[$th] ?>) <br>
			<?php
					$th++;
				}
			}
			?>
		</div>
		<div class="d-flex gap-2 p-3">
			<button class="btn btn-secondary me-auto" id="btn_list">목록</button>
			<?php if ($boardRow['id'] == $ses_id) { ?>
				<button class="btn btn-primary" id="btn_edit">수정</button>
				<button class="btn btn-danger" id="btn_delete">삭제</button>
			<?php } ?>
		</div>

		<div class="d-flex gap-2 mt-3">
			<textarea name="" rows="3" class="form-control" id="comment_content"></textarea>
			<button class="btn btn-secondary" id="btn_comment">등록</button>
		</div>

		<div class="mt-3">
			<table class="table">
				<colgroup>
					<col width="50%" />
					<col width="10%" />
					<col width="10%" />
				</colgroup>
				<?php
				foreach ($commnentRs as $comRow) {
				?>
					<tr>
						<td><span><?php echo nl2br($comRow['content']); ?></span>
							<?php 
								if ($comRow['id'] == $ses_id) {
									echo '
									<button class="btn btn-info p-1 btn-sm btn_comment_edit" data-comment-idx="'. $comRow['idx'] .'">수정</button>
									<button class="btn btn-danger btn-sm p-1 ms-2 btn_comment_delete" data-comment-idx="' . $comRow['idx'] . '">삭제</button>';
								}
								?></td>
						<td><?php echo $comRow['id']; ?></td>
						<td><?php echo $comRow['create_at']; ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>

	</div>
</main>


<?php include 'includes/part/inc_footer.php'; ?>