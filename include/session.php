<?php
session_start();
session_regenerate_id(true); // sessionハイジャック防止

// ユーザーidの確認
if (isset($_SESSION["user_id"])) {
	$user_id = $_SESSION["user_id"];
}

// ログイン状態を確認してのリダイレクト処理
if (!isset($_SESSION["user_name"])) {
	// ログインしていない場合
	switch ($_SERVER["SCRIPT_NAME"]) {
		// ログインページ、ログアウトページ、エントリーページなら何もしない
		case '/dotti/login.php':
		case '/dotti/logout.php':
		case '/dotti/entry.php':
			break;

		default:
			// 変数に値がセットされていない場合は不正な処理と判断し、ログイン画面へリダイレクトさせる
			$redirect_url = ((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . "/dotti/login.php");
			header("Location: {$redirect_url}");
			exit;
	}
} else {
	// ログアウトがPOSTされていればログアウト処理を行って、ログアウトページにリダイレクトする。
	if (isset($_POST["logout"])) {
		//セッション変数は上書きして初期化
		$_SESSION = array();

		//cookieのセッションIDを破棄
		if (isset($_COOKIE["PHPSESSID"])) {
			setcookie("PHPSESSID", '', time() - 1800, '/');
		}
		//セッションを破棄
		session_destroy();

		// ログアウトページにリダイレクト
		$redirect_url = ((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . "/dotti/logout.php");
		header("Location: {$redirect_url}");
		exit;
	}

	switch ($_SERVER["SCRIPT_NAME"]) {
		//ログイン状態でログインページ、ログアウトページ、エントリーページにアクセスした場合
		case '/dotti/login.php':
		case '/dotti/logout.php':
		case '/dotti/entry.php':
			//変数に値がセットされている(ログイン)済みの場合はリザルトページにリダイレクトする。
			$redirect_url = ((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . "/dotti/mypage.php");
			header("Location: {$redirect_url}");
			exit;

		// ログイン状態でゲーム内のページはそのままアクセスする。
		default:
			$user_name = $_SESSION["user_name"];
			break;
	}
}

?>