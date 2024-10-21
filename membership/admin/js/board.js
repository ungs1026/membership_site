document.addEventListener("DOMContentLoaded", () => {

	const btn_board_create = document.querySelector('#btn_board_create');
	const board_title = document.querySelector('#board_title');

	btn_board_create.addEventListener("click", () => {
		if (board_title.value == '') {
			alert('게시판 이름을 작성해주세요.');
			board_title.focus();
			return false;
		}

		btn_board_create.disable = true;

		const board_mode = document.querySelector('#board_mode');

		const xhr = new XMLHttpRequest();

		const f = new FormData();
		f.append('board_title', board_title.value);
		f.append('board_btype', document.querySelector('#board_btype').value);
		f.append('mode', board_mode.value);
		f.append('idx', document.querySelector('#board_idx').value);

		xhr.open("POST", './page/board_process.php', "true");
		xhr.send(f);

		xhr.onload = () => {
			if (xhr.status == 200) {
				const data = JSON.parse(xhr.responseText);
				if (data.result == "mode_empty") {
					alert('Mode 값이 존재하지않습니다');
					return false;
				} else if (data.result == 'title_empty') {
					alert('게시판 명이 누락되었습니다.');
					board_title.focus();
					return false;
				} else if (data.result == 'btype_empty') {
					alert('게시판 타입이 누락되었습니다.');
					return false;
				} else if (data.result == 'success') {
					alert('게시판이 생성되었습니다.');
					self.location.reload();
				} else if (data.result == 'edit_success') {
					alert('게시판이 수정되었습니다.');
					self.location.reload();
				}
			} else {
				alert('통신 실패 : ' + xhr.status);
			}
			btn_board_create.disable = false;
		}
	})

	// 게시판 생성 버튼 클릭
	const btn_create_modal = document.querySelector('#btn_create_modal');
	btn_create_modal.addEventListener('click', () => {
		board_title.value = '';
		const board_mode = document.querySelector('#board_mode');
		board_mode.value = 'input';
		document.querySelector('#modalTitle').textContent = '게시판 생성';
	})

	// 수정 버튼 클릭
	const btn_mem_edit = document.querySelectorAll('.btn_mem_edit');
	btn_mem_edit.forEach((box) => {
		box.addEventListener('click', () => {
			const board_mode = document.querySelector('#board_mode');
			board_mode.value = 'edit';
			document.querySelector('#modalTitle').textContent = '게시판 수정';

			const idx = box.dataset.idx;

			const board_idx = document.querySelector('#board_idx');
			board_idx.value = idx;

			const f = new FormData();
			f.append('idx', idx);
			f.append('mode', 'getInfo');

			const xhr = new XMLHttpRequest();
			xhr.open("post", './page/board_process.php', true);
			xhr.send(f);

			xhr.onload = () => {
				if (xhr.status == 200) {
					const data = JSON.parse(xhr.responseText);
					if (data.result == "idx_empty") {
						alert('idx 값이 존재하지않습니다');
						return false;
					} else if (data.result == 'success') {
						// alert(data.list.bcode);
						document.querySelector('#board_title').value = data.list.name;
						document.querySelector('#board_btype').value = data.list.btype;
					}

				} else {
					alert('통신실패 : ' + xhr.status);
				}
			}

		})
	})

	// 해당 게시판으로 이동
	const btn_board_view = document.querySelectorAll('.btn_board_view');
	btn_board_view.forEach((box) => {
		box.addEventListener('click', () => {
			self.location.href= '../board.php?bcode=' + box.dataset.bcode;
		})
	})

	// 삭제 버튼 클릭
	const btn_mem_delete = document.querySelectorAll('.btn_mem_delete');
	btn_mem_delete.forEach((box) => {
		box.addEventListener('click', () => {
			if (!confirm('본 게시판을 삭제하시겠습니까?')) {
				return false;
			}

			const idx = box.dataset.idx;

			const f = new FormData();
			f.append('idx', idx);
			f.append('mode', 'delete');

			const xhr = new XMLHttpRequest();
			xhr.open("post", './page/board_process.php', true);
			xhr.send(f);

			xhr.onload = () => {
				if (xhr.status == 200) {
					const data = JSON.parse(xhr.responseText);
					if (data.result == 'success') {
						self.location.reload();
					}
				} else {
					alert('통신 실패 : ' + xhr.status);
				}
			}

		})
	})
})