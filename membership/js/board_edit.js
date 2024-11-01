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
	const params = getUrlParams();

	const btn_file_del = document.querySelectorAll(".btn_file_del");
	btn_file_del.forEach((box) => {
		box.addEventListener('click', () => {

			if (!confirm('해당 파일을 삭제하시겠습니까?')) {
				return false;
			}

			const f = new FormData();
			f.append("th", box.dataset.th);
			f.append("bcode", params['bcode']); // 게시판 코드
			f.append("mode", "each_file_del"); // 모드 : 개별 파일 삭제
			f.append("idx", params['idx']); // 게시물 번호

			const xhr = new XMLHttpRequest();
			xhr.open("post", "page/board_process.php", true);
			xhr.send(f);
			xhr.onload = () => {
				if (xhr.status == 200) {
					const data = JSON.parse(xhr.responseText);
					if (data.result == 'empty_idx') {
						alert('게시물 번호가 없습니다.');
						return false;
					} else if (data.result == 'empty_th') {
						alert('첨부파일 순서가 없습니다.');
						return false;
					} else if (data.result == 'success') {
						self.location.reload();
					}
				} else {
					alert('통신에 실패하였습니다.');
				}
			}
		})
	})

	// 변화 확인 (첨부파일)
	const id_attach = document.querySelector('#id_attach');
	if (id_attach) {
		id_attach.addEventListener('change', () => {

			const f = new FormData();
			f.append('bcode', params['bcode']); // bcode
			f.append('mode', 'file_attach'); // 모드 : 파일 첨부
			f.append('idx', params['idx']) // idx;

			if (id_attach.files[0].size > 40 * 1024 * 1024) {
				alert('40M보다 큰 파일이 첨부되었습니다.');
				id_attach.value = '';
				return false;
			}

			ext = getExtensionOfFilename(id_attach.files[0].name);
			if (ext == 'txt' || ext == 'exe' || ext == 'xls' || ext == 'php' || ext == 'js') {
				alert('첨부할 수 없는 포멧의 파일이 첨부되었습니다. (exe, txt, php, js, ...)');
				id_attach.value = '';
				return false;
			}
			f.append("files", id_attach.files[0]);

			const xhr = new XMLHttpRequest();
			xhr.open("post", "./page/board_process.php", true);
			xhr.send(f);

			xhr.onload = () => {
				if (xhr.status == 200) {
					const data = JSON.parse(xhr.responseText);
					if (data.result == 'success') {
						self.location.reload();
					} else if (data.result == 'empty_files') {
						alert('파일이 첨부되지 않았습니다.');
						return false;
					}
				} else {
					alert('통신실패' + xhr.status);
				}
			}
		})
	}

	const btn_board_list = document.querySelector('#btn_board_list');
	btn_board_list.addEventListener('click', () => {
		self.location.href = './board.php?bcode=' + params['bcode'];
	})

	// 수정확인 버튼 클릭시
	const btn_edit_submit = document.querySelector("#btn_edit_submit")
	btn_edit_submit.addEventListener("click", () => {
		const id_subject = document.querySelector("#id_subject");
		if (id_subject.value == '') {
			alert('게시물 제목을 입력해 주세요')
			id_subject.focus()
			return false
		}

		const markupStr = $('#summernote').summernote('code')
		if (markupStr == '<p><br></p>') {
			alert('내용을 입력하세요.')
			return false
		}

		const params = getUrlParams()

		const f = new FormData()
		f.append("subject", id_subject.value) // 게시물 제목
		f.append("content", markupStr) // 게시물 내용
		f.append("bcode", params['bcode']) // 게시판 코드
		f.append("idx", params['idx']) // 게시물 번호
		f.append("mode", "edit") // 모드 : 글등록

		const xhr = new XMLHttpRequest()
		xhr.open("post", "./page/board_process.php", true)
		xhr.send(f)

		xhr.onload = () => {
			if (xhr.status == 200) {
				const data = JSON.parse(xhr.responseText)
				if (data.result == 'success') {
					alert('글 수정이 성공했습니다.')
					self.location.href = './board.php?bcode=' + params['bcode']
				} else if (data.result == 'permission_denied') {
					alert('수정 권한이 없는 게시물입니다.');
					self.location.href = './board.php?bcode=' + params['bcode']
				}
			} else if (xhr.status == 404) {
				alert('통신실패, 파일없습니다.')
			}
		}


	})

}) // DOMContentLoaded