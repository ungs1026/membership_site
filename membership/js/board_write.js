function getUrlParams() {
	const params = {};
	window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,
		function (str, key, value) {
			params[key] = value;
		}
	);
	return params;
}

// substring(시작위치, 끝위치); => 위치에 따른 문자열 반환
function getExtensionOfFilename(filename) {
	const filelen = filename.length;
	const lastdot = filename.lastIndexOf('.');
	return filename.substring(lastdot + 1, filelen).toLowerCase();
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
	const id_attach = document.querySelector('#id_attach');

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
		// const file = id_attach.files[0];

		// 파일 첨부 제한
		if (id_attach.files.length > 3) {
			alert('파일은 3개까지 가능합니다.')
			id_attach.value = '';
			return false;
		}

		// 전송 -> bcode, title, content
		const params = getUrlParams();

		const f = new FormData();
		f.append('subject', id_subject.value); // 게시물 제목
		f.append('content', markupStr); // 게시물 내용
		f.append('bcode', params['bcode']); // bcode
		f.append('mode', 'input'); // 모드 : 글 등록
		// f.append('files', file); // 파일 첨부

		let ext = '';

		for (const file of id_attach.files) {
			if (file.size > 40 * 1024 * 1024) {
				alert('40M보다 큰 파일이 첨부되었습니다.');
				id_attach.value = '';
				return false;
			}

			ext = getExtensionOfFilename(file.name);
			if (ext == 'txt' || ext == 'exe' || ext == 'xls' || ext == 'php' || ext == 'js') {
				alert('첨부할 수 없는 포멧의 파일이 첨부되었습니다. (exe, txt, php, js, ...)');
				id_attach.value = '';
				return false;
			}

			f.append("files[]", file);
		}

		const xhr = new XMLHttpRequest();
		xhr.open("post", './page/board_process.php', true);
		xhr.send(f);

		xhr.onload = () => {
			if (xhr.status == 200) {
				const data = JSON.parse(xhr.responseText);
				if (data.result == 'success') {
					alert('글이 등록되었습니다.');
					self.location.href = './board.php?bcode=' + params['bcode'];
				} else if (data.result == 'file_upload_count_limit') {
					alert('파일 업로드 갯수를 초과하였습니다.')
					id_attach.value = '';
					return false;
				} else if (data.result == 'post_size_exceed') {
					alert('첨부파일의 용량이 큽니다. 작은 파일로 첨부해주세요.');
					id_attach.value = '';
					return false;
				} else if (data.result == 'not_allow_file') {
					alert('첨부할 수 없는 포멧의 파일이 첨부되었습니다. (exe, txt ...)');
					id_attach.value = '';
					return false;
				}
			} else {
				alert('통신 실패 : ' + xhr.status);
			}
		}
	})

	id_attach.addEventListener('change', () => {
		if (id_attach.files.length > 3) {
			alert('파일은 3개까지 가능합니다.')
			id_attach.value = '';
			return false;
		}
	})
})