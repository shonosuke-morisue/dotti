<?php
session_start();
session_regenerate_id(true); // sessionハイジャック防止

// OAuth用ライブラリ「twitteroauth」
require_once './twitteroauth/twitteroauth.php';

// ユーザーidの確認
if (isset($_SESSION["user_id"])) {
	$user_id = $_SESSION["user_id"];
}

?>