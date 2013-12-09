<?php
// セッションスタート
session_start();
$_SESSION = array();
session_destroy();
header("Location: http://morisue.sakura.ne.jp/dotti/twitter/login.php");
?>