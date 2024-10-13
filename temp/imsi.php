<?php

$email = 'ddd@mmmm.ggg';

$rs = filter_var($email, FILTER_VALIDATE_EMAIL);

echo var_dump($rs);