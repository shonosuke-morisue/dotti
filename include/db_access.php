<?php
//DB接続処理
function db_access() {

	// DBアクセス用の情報を取得
	include '../../pass/dotti.php';

	//mysql接続
	$link = mysql_connect( $db_searver , $db_id , $db_pass );
	if (!$link) {
		die('接続失敗です。'.mysql_error());
	}

	//DB選択
	$db_selected = mysql_select_db('dotti', $link);
	if (!$db_selected){
		die('データベース選択失敗です。'.mysql_error());
	}
	return $link;

	//文字コードをutf8に設定
	mysql_set_charset('utf8');
}


//DB切断処理
function db_close($link) {
	//mysql切断
	$close_flag = mysql_close($link);

	// DBの切断に成功したらメッセージ表示
// 	if ($close_flag){
// 		print('<p>DBの切断に成功しました。</p>');
// 	}


	// エラーメッセージを格納する変数を初期化
	$error_message = "";
}

//DB接続エラー
function db_error($result) {
	if (!$result) {
		die('クエリーが失敗しました。'.mysql_error());
	}
}

?>