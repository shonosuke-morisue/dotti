<?php
session_start();
session_regenerate_id(true); // sessionハイジャック防止

// OAuth用ライブラリ「twitteroauth」
require_once './twitteroauth/twitteroauth.php';

// DBアクセス処理
require_once './include/db_access.php';

// 設問情報取得クラス
require_once './include/question.php';



// ユーザーidの確認
if (isset($_SESSION["user_id"])) {
	$user_id = $_SESSION["user_id"];
}

?>