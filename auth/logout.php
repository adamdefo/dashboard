<?php

session_start();
require('Auth.php');
$Auth = new Auth();

$Auth->logout();
echo '<html><head><meta http-equiv="Refresh" content="0; URL=../index.php"></head></html>';
