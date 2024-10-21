document.addEventListener("DOMContentLoaded", () => {
  const btn_login = document.querySelector('#btn_login');
  btn_login.addEventListener('click', () => {
    const f_id = document.querySelector('#f_id');
    const f_pw = document.querySelector('#f_pw');

    if (f_id.value == '') {
      alert('아이디를 입력해주세요');
      f_id.focus();
      return false;
    }
    if (f_pw.value == '') {
      alert('비밀번호를 입력해주세요');
      f_pw.focus();
      return false;
    }

    // AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./page/login_process.php", true);

    const f1 = new FormData();
    f1.append("id", f_id.value);
    f1.append("pw", f_pw.value);

    xhr.send(f1);

    xhr.onload = () => {
      if (xhr.status == 200) {

        const data = JSON.parse(xhr.responseText);
        if (data.result == 'login_fail') {
          alert('해당 정보는 존재하지 않습니다.');
          f_id.value = '';
          f_pw.value = '';
          f_id.focus();
          return false;
        } else if (data.result == 'login_success') {
          alert('로그인에 성공하였습니다.');
          self.location.href = './index.php';
        }

      } else {
        alert('통신에 실패하였습니다. 다시 시도해주세요.');
      }
    }
  })
})