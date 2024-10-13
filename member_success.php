<?php

$g_title = '회원가입을 축하드립니다.';
$js_array = ['js/member_success.js'];

$menu_code = 'member';

include 'includes/part/inc_header.php';

?>

<main class="w-75 mx-auto border raunded-5 p-5 d-flex gap-5 " style="height: calc(100vh - 200px);">
  <img src="sources/logo.svg" class="w-50" alt="">
  <div>
    <h3>회원 가입을 축하드립니다.</h3>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta beatae, delectus facilis aliquid cum architecto sed labore commodi pariatur quos ex magni quas sunt, facere laborum! Rem in culpa sunt vitae placeat velit commodi saepe voluptatibus exercitationem! Consectetur minima ea voluptate tenetur molestiae, soluta veritatis dolorum delectus assumenda recusandae voluptatibus!</p>
    <button class="btn btn-primary" id="btn_login">로그인 하기</button>
  </div>
</main>

<?php

include 'includes/part/inc_footer.php';
?>