<?php
//SESSIONスタート
session_start();
// OAuth用ライブラリ「twitteroauth」
require_once 'twitteroauth/twitteroauth.php';
// アプリ登録した際に発行された値を入れて下さい。
$consumer_key = 'fDbEZYlCo1VYA56n4PvQ';
$consumer_secret = 'DLbKrDLqyuPFqUMrVPorYcK2gMhT8TvpBxGEeB8DKZE';
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
$oOauth = new TwitterOAuth($consumer_key, $consumer_secret, $_SESSION['request_token'], $_SESSION['request_token_secret']);
//oauth_verifierを使ってAccess tokenを取得
$oAccessToken = $oOauth->getAccessToken($sVerifier);
//-------------------------
//取得した値をSESSIONに格納
//-------------------------
$_SESSION['user_id'] = $oAccessToken['user_id'];
$_SESSION['screen_name'] = $oAccessToken['screen_name'];
$_SESSION['oauth_token'] = $oAccessToken['oauth_token'];
$_SESSION['oauth_token_secret'] = $oAccessToken['oauth_token_secret'];
// loginページへリダイレクト
header("Location: http://morisue.sakura.ne.jp/dotti/twitter/login.php");
?>