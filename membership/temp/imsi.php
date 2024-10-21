<?php

// a~z
// 6자리

$letter = range('a', 'z');
$bcode = '';
for ($i = 0; $i < 6; $i++) {
	$r = rand(0, 25);
	$bcode .= $letter[$r];
}
