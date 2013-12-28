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
		//DBからユーザー情報を取得
		$db_link = db_access();
		$sql = 'SELECT * FROM user WHERE user_id = "'.$user_id.'"';
		$result = $db_link->query($sql);

		//DB切断処理
		db_close($db_link);
		
		$user = $result->fetch();
		
		// ユーザーの各種情報を変数に格納
		$this->user_id = $user['user_id'];
		$this->screen_name = $user['screen_name'];
		$this->name = $user['name'];
		$this->profile_image_url = $user['profile_image_url'];

	}
}
?>
