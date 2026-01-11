<?php
require_once 'auth.php';

logout();
header("Location: login.php?logged_out=1");
exit();
?>