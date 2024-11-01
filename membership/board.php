<?php
include_once './includes/part/common.php';
include_once './DB/dbconfig.php';
include_once './includes/board.php';
include_once './includes/lib.php'; // 페이지네이션

$bcode = (isset($_GET['bcode']) && $_GET['bcode'] != '') ? $_GET['bcode'] : '';
$page = (isset($_GET['page']) && $_GET['page'] != '' && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
$sn = (isset($_GET['sn']) && $_GET['sn'] != '') ? $_GET['sn'] : '';
$sf = (isset($_GET['sf']) && $_GET['sf'] != '') ? $_GET['sf'] : '';

if ($bcode == '') {
	die("
	<script>
	alert('게시판 코드가 누락되었습니다.');
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
$js_array = ['js/board.js'];

$g_title = $board_name;

$paramArr = ['sn' => $sn, 'sf' => $sf];
$total = $board->total($bcode, $paramArr);
$limit = 5;
$page_limit = 5;
$boardRs = $board->list($bcode, $page, $limit, $paramArr);

include 'includes/part/inc_header.php';
?>
<style>
	.tr {
		cursor: pointer;
	}
</style>

<main class="w-100 mx-auto border rounded-2 p-5">
	<h1 class="text-center"><?= $board_name; ?></h1>

	<table class="table striped table-hover mt-5">
		<colgroup>
			<col width=10%>
			<col width=45%>
			<col width=10%>
			<col width=15%>
			<col width=10%>
		</colgroup>
		<tr>
			<th>번호</th>
			<th>제목</th>
			<th>이름</th>
			<th>날짜</th>
			<th>조회 수</th>
		</tr>

		<?php
		$cnt = 0;
		$ntotal = $total - ($page - 1) * $limit;
		foreach ($boardRs as $boardRow) {
			$number = $ntotal - $cnt;
			$cnt++;
		?>
			<tr class="tr" data-idx="<?= $boardRow['idx'] ?>">
				<td><?= $number; ?></td>
				<td><?php
						echo $boardRow['subject'];
						if ($boardRow['comment_cnt'] > 0) {
							echo '<span class="badge bg-secondary ms-2">'.$boardRow['comment_cnt'].'</span>';
						}
						?></td>
				<td><?= $boardRow['name']; ?></td>
				<td><?= $boardRow['create_at']; ?></td>
				<td><?= $boardRow['hit']; ?></td>
			</tr>
		<?php } ?>

	</table>

	<div class="container mt-3 w-50 d-flex gap-2 justify-content-center">
		<select class="form-select w-25" name="" id="sn">
			<option value="1" <?php if ($sn == 1) echo ' selected' ?>>제목+내용</option>
			<option value="2" <?php if ($sn == 2) echo ' selected' ?>>제목</option>
			<option value="3" <?php if ($sn == 3) echo ' selected' ?>>내용</option>
			<option value="4" <?php if ($sn == 4) echo ' selected' ?>>글쓴이</option>
		</select>
		<input type="text" class="form-control w-25" id="sf" value="<?= $sf ?>">
		<button class="btn btn-primary w-25" id="btn_search">검색</button>
		<button class="btn btn-info w-25" id="btn_all">목록</button>
	</div>

	<div class="d-flex justify-content-between align-items-start">
		<?php
		$param = '&bcode=' . $bcode;
		if (isset($sn) && $sn != '' && isset($sf) && $sf != '') {
			$param .= '&sn=' . $sn . '&sf=' . $sf;
		}
		echo My_Pagination($total, $limit, $page_limit, $page, $param);
		?>
		<button class="btn btn-primary" id="btn_write">글쓰기</button>
	</div>
</main>


<?php include 'includes/part/inc_footer.php'; ?>