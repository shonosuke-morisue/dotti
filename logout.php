<?php
require_once './include/session.php';
require_once './include/db_access.php';

//-------------------------------------------------------
//
// ログアウト処理
//
//-------------------------------------------------------

//セッション変数は上書きして初期化
$_SESSION = array();

//cookieのセッションIDを破棄
if (isset($_COOKIE["PHPSESSID"])) {
	setcookie("PHPSESSID", '', time() - 1800, '/');
}
//セッションを破棄
session_destroy();

//-------------------------------------------------------

include './include/header.php';

?>

<hr>
■ログアウトページ<br>
ログアウトしましたよ
<hr>

<a href="./login.php">ログインページ</a><br>
<a href="./logout.php">ログアウトページ</a><br>
<a href="./mypage.php">リザルトページ</a><br>


<?php
include './include/footer.php';
?>
