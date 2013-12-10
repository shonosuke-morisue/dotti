<?php
//SESSIONスタート
require_once './include/session.php';
// OAuth用ライブラリ「twitteroauth」
require_once 'twitteroauth/twitteroauth.php';
require_once './include/db_access.php';


//-------------------------------------
//URLパラメータからoauth_verifierを取得
//-------------------------------------
if(isset($_GET['oauth_verifier']) && $_GET['oauth_verifier'] != '') {
  $sVerifier = $_GET['oauth_verifier'];
}
  else {
    echo 'oauth_verifier error!';
    exit;
  }
//-----------------------------------------
//リクエストトークンでOAuthオブジェクト生成
//-----------------------------------------
$oOauth = new TwitterOAuth(ConsumerKey, ConsumerSecret, $_SESSION['request_token'], $_SESSION['request_token_secret']);
//oauth_verifierを使ってAccess tokenを取得
$oAccessToken = $oOauth->getAccessToken($sVerifier);
//-------------------------
//取得した値をSESSIONに格納
//-------------------------
$_SESSION['user_id'] = $oAccessToken['user_id'];
$_SESSION['screen_name'] = $oAccessToken['screen_name'];
$_SESSION['oauth_token'] = $oAccessToken['oauth_token'];
$_SESSION['oauth_token_secret'] = $oAccessToken['oauth_token_secret'];
$_SESSION['oAccessToken'] = $oAccessToken;

//-------------------------
//DBのユーザー情報と比較
//-------------------------

//DBからユーザー情報を取得
$link = db_access();
$result = mysql_query('SELECT * FROM user WHERE user_id = "'.$_SESSION['user_id'].'";');

db_error($result);

$user = mysql_fetch_assoc($result);
// ユーザー名を確認
if (!$_SESSION['user_id'] == $user["user_id"]) {
	// 未登録のユーザー名の場合は新規登録する。

	//タイムゾーンの設定
	date_default_timezone_set('Asia/Tokyo');
	//日付の取得
	$datetime = date("Y-m-d H:i:s");

	//userへデータの登録
	$entry = mysql_query('INSERT INTO user( user_id , screen_name , time ) VALUES ( "'.$_SESSION['user_id'].'","'.$_SESSION['screen_name'].'","'.$datetime.'" );');

	db_error($entry);
}

//DB切断処理
db_close($link);


// loginページへリダイレクト
header("Location: http://morisue.sakura.ne.jp/dotti/login.php");
?>