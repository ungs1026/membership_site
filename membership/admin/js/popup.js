document.addEventListener("DOMContentLoaded", () => {
	const btn_popup_create = document.querySelector('#btn_popup_create');
	btn_popup_create.addEventListener('click', () => {
		// 팝업 타이틀
		const popup_title = document.querySelector('#popup_title');
		if (popup_title.value == '') {
			alert('팝업 제목을 입력해 주시기 바랍니다.')
			popup_title.focus();
			return false;
		}

		// 팝업 사용 여부
		const popup_use = document.querySelector('#popup_use');
		if (popup_use.value == '') {
			alert('팝업 사용 여부를 확인하기 바랍니다.')
			popup_use.focus()
			return false
		}

		// 팝업 위치 정보
		const pop_x = document.querySelector('#pop_x');
		if (pop_x.value == '') {
			alert('팝업 위치 정보를 입력해주시기 바랍니다.')
			pop_x.focus()
			return false
		}
		const pop_y = document.querySelector('#pop_y');
		if (pop_y.value == '') {
			alert('팝업 위치 정보를 입력해주시기 바랍니다.')
			pop_y.focus()
			return false
		}

		// 팝업 날짜
		const sdate = document.querySelector('#sdate');
		if (sdate.value == '') {
			alert('팝업 시작일을 입력해 주시기 바랍니다.')
			sdate.focus()
			return false
		}
		const edate = document.querySelector('#edate');
		if (edate.value == '') {
			alert('팝업 종료일 입력해 주시기 바랍니다.')
			edate.focus()
			return false
		}

		if (sdate.value > edate.value) {
			alert('팝업 시작일이 팝업 종료일보다 크면 안됩니다.')
			return false
		}

		if (document.querySelector('#popup_mode') != 'edit') {
			const file = document.querySelector('#file')
			if (file.value == '') {
				alert('이미지 파일을 첨부해 주시기 바랍니다.')
				file.focus()
				return false
			}
		}

		const cookie = document.querySelector('#cookie')
		const link = document.querySelector('#popup_link')
		const idx = document.querySelector('#popup_idx')
		const mode = (document.querySelector('#popup_mode').value != '') ? document.querySelector('#popup_mode').value : 'input'

		const f1 = new FormData();
		f1.append('name', popup_title.value)
		f1.append('use', popup_use.value)
		f1.append('sdate', sdate.value)
		f1.append('edate', edate.value)
		f1.append('pop_x', pop_x.value)
		f1.append('pop_y', pop_y.value)
		f1.append('cookie', cookie.value)
		f1.append('link', link.value)
		f1.append('file', file.files[0])
		f1.append('idx', idx.value)
		f1.append('mode', mode)

		const xhr = new XMLHttpRequest()
		xhr.open('post', './page/popup_process.php', true)
		xhr.send(f1);

		xhr.onload = () => {
			if (xhr.status == 200) {
				const data = JSON.parse(xhr.responseText)
				if (data.result == 'success') {
					alert('등록을 성공하였습니다.')
					self.location.reload()
				} else if (data.result == 'empty_name') {
					alert('팝업 이름이 비었습니다.')
					return false
				} else if (data.result == 'empty_sdate') {
					alert('팝업 시작일이 비었습니다.')
					return false
				} else if (data.result == 'empty_edate') {
					alert('팝업 종료일이 비었습니다.')
					return false
				} else if (data.result == 'empty_use') {
					alert('팝업 사용여부 설정이 비었습니다.')
					return false
				}
			} else {
				alert(xhr.status)
			}
		}
	})

	// Popup Layer 닫기 X
	const btn_x_close = document.querySelector(".close")
	btn_x_close.addEventListener("click", () => {
		document.querySelector("#pop1").style.display = 'none'
	})

	// 팝업 보기 버튼 클릭
  const btn_popup_views = document.querySelectorAll(".btn_popup_view")
  btn_popup_views.forEach((box) => {
    box.addEventListener("click", () => {
      getInfo(box.dataset.idx, "view")
    })
  })

	// 팝업 수정 버튼 클릭
  const btn_popup_edits = document.querySelectorAll(".btn_popup_edit")
  btn_popup_edits.forEach((box) => {
    box.addEventListener("click", () => {
      getInfo(box.dataset.idx, "edit")

      // 모달 윈도
      document.querySelector("#modalTitle").textContent = '팝업 수정'
      document.querySelector("#popup_mode").value = 'edit'
      document.querySelector("#popup_idx").value = box.dataset.idx
    })
  })

	// 팝업 삭제 버튼 클릭
  const btn_popup_deletes = document.querySelectorAll(".btn_popup_delete")
  btn_popup_deletes.forEach((box) =>  {
    box.addEventListener("click", () => {

      if(!confirm('이 팝업을 삭제하시겠습니까?')) {
        return false
      }
      const f1 = new FormData()
      f1.append('idx', box.dataset.idx)
      f1.append('mode', 'delete')

      const xhr = new XMLHttpRequest()
      xhr.open("post", "page/popup_process.php", true)
      xhr.send(f1)
      xhr.onload = () => {
        if(xhr.status == 200) {
          self.location.reload()
        }else if(xhr.status = 404) {
          alert('통신실패')
        }
      }
    })
  })

}) // DOMContentLoaded

// 공통 사용
function getInfo(idx, mode) {
  const f1 = new FormData()
  f1.append("idx", idx)
  f1.append("mode", "getInfo")

  const xhr = new XMLHttpRequest()
  xhr.open("post", "page/popup_process.php", true)
  xhr.send(f1)
  xhr.onload = () => {
    if(xhr.status == 200) {
      //console.log(xhr.responseText)
      const data = JSON.parse(xhr.responseText)
      //console.log(data)

      if(mode == 'view') {

        const pop1 = document.querySelector("#pop1")
        // pop1.style.display = 'block'
        //pop1.style.left = data.pop_x + 'px'
        // pop1.style.left =  `${data.pop_x}px`
        // pop1.style.top = data.pop_y + 'px'
  
        pop1.style.cssText = `
          display:block; 
          left:${data.pop_x}px; 
          top:${data.pop_y}px
          `
  
        document.querySelector("#pop1 img").src = data.file
  
        const cookie_term = document.querySelector("#cookie_term")
  
        if(data.cookie == 'day') {
          cookie_term.textContent = '하루 동안 이 창을 다시 열지 않음'
        }else if(data.cookie == 'week') {
          cookie_term.textContent = '1주일 동안 이 창을 다시 열지 않음'
        }else if(data.cookie == 'month') {
          cookie_term.textContent = '1개월 동안 이 창을 다시 열지 않음'
        }

      }else if(mode == 'edit') {
        document.querySelector("#popup_title").value = data.name
        document.querySelector("#popup_use").value = data.use
        document.querySelector("#popup_link").value = data.link
        document.querySelector("#pop_x").value = data.pop_x
        document.querySelector("#pop_y").value = data.pop_y
        document.querySelector("#sdate").value = data.sdate
        document.querySelector("#edate").value = data.edate
        document.querySelector("#cookie").value = data.cookie
      }
  
    }else if(xhr.status == 404) {
        alert('통신실패')
    }        

  }  
}