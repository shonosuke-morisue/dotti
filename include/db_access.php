<?php
// DBアクセス用の情報を取得
require_once '../../pass/dotti.php';



//DB接続処理
function db_access() {

	//mysql接続
	$dsn = 'mysql:dbname='.DB_NAME.';host='.DB_SEAVER;
	$user = DB_ID;
	$password = DB_PASS;
	
	try{
		$db_link = new PDO($dsn, $user, $password);
		$db_link->query('SET NAMES utf8');
	}catch (PDOException $e){
		print('Connection failed:'.$e->getMessage());
		die();
	}
	
	return $db_link;
}

//DB切断処理
function db_close($db_link) {
	$db_link = null;
}

//DB接続エラー
function db_error($result) {
	if (!$result) {
		//文字コードをutf8に設定
		mysql_set_charset('utf8');
		
		die('クエリーが失敗しました。'.mysql_error());
	}
}

?>