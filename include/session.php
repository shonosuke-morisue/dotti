<?php
session_start();

// sessionハイジャック防止
if(mt_rand(1, 30)==1) {	// 実行確率
	$sess_file = 'sess_'.session_id();
	$sess_dir_path = ini_get('session.save_path');
	$sess_file_path = $sess_dir_path. '/'. $sess_file;
	$timestamp = filemtime($sess_file_path);
	$span = 5*60;		// 経過時間
	if(($timestamp+$span) < time()) {
		// PHP Ver取得
		$iPHPVer = (int)sprintf('%.3s', str_replace('.', '', PHP_VERSION));
		if($iPHPVer>=510) {
			session_regenerate_id(true);
		}else {
			$sess_tmp = $_SESSION;
			session_destroy();
			session_id(createUniqueKey(25, true));
			session_start();
			$_SESSION = $sess_tmp;
		}// end if
	}// end if
}// end if



// OAuth用ライブラリ「twitteroauth」
require_once './twitteroauth/twitteroauth.php';

// DBアクセス処理
require_once './include/db_access.php';

// クラスオートロード処理
function __autoload($class_name) {
	$file = './class/'.$class_name.'.php';
	if (is_readable($file)) {
		require_once $file;
	}
}

// ユーザーidの確認
if (isset($_SESSION["user_id"])) {
	$user_id = $_SESSION["user_id"];
}

?>