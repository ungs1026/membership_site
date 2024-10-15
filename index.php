<?php
session_start();

$ses_id = (isset($_SESSION['ses_id']) && $_SESSION['ses_id'] != '') ? $_SESSION['ses_id'] : '';
$ses_level = (isset($_SESSION['ses_level']) && $_SESSION['ses_level'] != '') ? $_SESSION['ses_level'] : '';

$g_title = '네카라쿠배';
$js_array = ['js/home.js'];

$menu_code = 'home';

include 'includes/part/inc_header.php';

?>

<main class="w-75 mx-auto border rounded-5 p-5 d-flex gap-5 " style="height: calc(100vh - 200px);">
  <img src="sources/logo.svg" class="w-50" alt="">
  <div>
    <h3>Home 입니다.</h3>
    
  </div>
</main>

<?php

include 'includes/part/inc_footer.php';
?>