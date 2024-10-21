<?php
include_once './includes/part/common.php';
include_once './DB/dbconfig.php';
include_once './includes/board.php';

$menu_code = 'board';

$bcode = (isset($_GET['bcode']) && $_GET['bcode'] != '') ? $_GET['bcode'] : '';

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

$board = new Board($db);

$js_array = ['js/board_write.js'];

$g_title = '게시판';

include 'includes/part/inc_header.php';
?>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<main class="w-75 mx-auto border rounded-2 p-5">
	<h1 class="text-center">게시판 글쓰기</h1>

	<div class="mb-3">
		<input type="text" name="subject" id="id_subject" class="form-control" placeholder="제목을 입력하세요." autocomplete="off">
	</div>
	<div id="summernote"></div>

	<div class="mt-3">
		<input type="file" name="attach" id="id_attach" class="form-control">
	</div>

	<div class="mt-3 d-flex gap-2 justify-content-end">
		<button class="btn btn-primary" id="btn_wirte_submit">확인</button>
		<button class="btn btn-secondary" id="btn_board_list">목록</button>
	</div>
</main>

<script>
      $('#summernote').summernote({
        placeholder: '내용을 입력해주세요.',
        tabsize: 2,
        height: 400,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
    </script>

<?php include 'includes/part/inc_footer.php'; ?>