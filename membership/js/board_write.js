function getUrlParams() {
	const params = {};
	window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,
		function(str, key, value) {
			params[key] = value;
		}
	);
	return params;
}

document.addEventListener("DOMContentLoaded", () => {

	// 게시판 목록으로 이동하기
	const btn_board_list = document.querySelector('#btn_board_list');
	btn_board_list.addEventListener('click', () => {
		const params = getUrlParams();

		self.location.href = './board.php?bcode=' + params['bcode'];
	})

	// 게시물 작성 후 확인
	const btn_wirte_submit = document.querySelector('#btn_wirte_submit');
	btn_wirte_submit.addEventListener('click', () => {
		const id_subject = document.querySelector('#id_subject');
		// 공백확인
		if (id_subject.value == '') {
			alert('게시물 제목을 작성해주세요.');
			id_subject.focus();
			return false;
		}

		const markupStr = $('#summernote').summernote('code');
		if (markupStr == '<p><br></p>') {
			alert('내용을 입력해주세요.')
			return false;
		}

		// 파일 첨부
		const id_attach = document.querySelector('#id_attach');
		const file = id_attach.files[0];

		// 전송 -> bcode, title, content
		const params = getUrlParams();

		const f = new FormData();
		f.append('subject', id_subject.value); // 게시물 제목
		f.append('content', markupStr); // 게시물 내용
		f.append('bcode', params['bcode']); // bcode
		f.append('mode', 'input'); // 모드 : 글 등록
		f.append('files', file); // 파일 첨부

		const xhr = new XMLHttpRequest();
		xhr.open("post", './page/board_process.php', true);
		xhr.send(f);

		xhr.onload = () => {
			if (xhr.status == 200) {
				const data = JSON.parse(xhr.responseText);
				if (data.result == 'success'){
					alert('글이 등록되었습니다.');
					self.location.href = './board.php?bcode=' + params['bcode'];
				}
			} else {
				alert('통신 실패 : ' + xhr.status);
			}
		}
	})
})