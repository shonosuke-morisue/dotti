<?php
//----------------------------------------
// ユーザーの情報を取得するクラス
//----------------------------------------
class user {
	public $user_id;
	public $screen_name;
	public $name;
	public $profile_image_url;

	// ユーザーの初期化
	function __construct($user_id) {
		// DBに接続
		$db_link = db_access();		
		//DBからユーザー情報を取得
		$sth = $db_link->prepare('SELECT * FROM user WHERE user_id = :user_id ');
		$sth->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$sth->execute();

		//DB切断処理
		db_close($db_link);
		
		$user = $sth->fetch();
		
		// ユーザーの各種情報を変数に格納
		$this->user_id = $user['user_id'];
		$this->screen_name = $user['screen_name'];
		$this->name = $user['name'];
		$this->profile_image_url = $user['profile_image_url'];

	}
}
?>
