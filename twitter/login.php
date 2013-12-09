<?php
// セッションスタート
session_start();
// OAuth用ライブラリ「twitteroauth」
require_once 'twitteroauth/twitteroauth.php';
// アプリ登録した際に発行された値を入れて下さい。
$consumer_key = 'fDbEZYlCo1VYA56n4PvQ';
$consumer_secret = 'DLbKrDLqyuPFqUMrVPorYcK2gMhT8TvpBxGEeB8DKZE';
// call_backする場所
$call_back_url = 'http://morisue.sakura.ne.jp/dotti/twitter/callback.php';
//--------------------------------------
//セッションのアクセストークンのチェック
//--------------------------------------
if((isset($_SESSION["oauth_token"]) && $_SESSION["oauth_token"] !== NULL) && (isset($_SESSION["oauth_token_secret"]) && $_SESSION["oauth_token_secret"] !== NULL)) {
  // ログインしたらここにくる
}
  // ログアウトの状態
  else {
    // オブジェクト生成
    $twitter_oauth_object = new TwitterOAuth (
      $consumer_key,
      $consumer_secret);
    //call_backを指定して request tokenを取得
    $oOauthToken = $twitter_oauth_object->getRequestToken($call_back_url);
    //セッション格納
    $_SESSION['request_token'] = $oOauthToken['oauth_token'];
    $sToken = $oOauthToken['oauth_token'];
    $_SESSION['request_token_secret'] = $oOauthToken['oauth_token_secret'];
    //認証URLの引数 falseの場合はtwitter側で認証確認表示
    if(isset($_GET['authorizeBoolean']) && $_GET['authorizeBoolean'] != '') {
      $bAuthorizeBoolean = false;
    }
      else {
        $bAuthorizeBoolean = true;
      }
    //Authorize url を取得
    $sUrl = $twitter_oauth_object->getAuthorizeURL($sToken, $bAuthorizeBoolean);
  }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
  <body>
  <a href="<?php print($sUrl);?>">Twitterアカウントを使いログインする</a>
  <br>
  <a href="./logout.php">ログアウト</a><br>
  <a href="./test.php">test</a>
  <br>
<?php
echo '<p>$_SESSION["user_id"] => '.$_SESSION['user_id'].'</p>';
echo '<p>$_SESSION["screen_name"] => '.$_SESSION['screen_name'].'</p>';
echo '<p>$_SESSION["oauth_token"] => '.$_SESSION["oauth_token"].'</p>';
echo '<p>$_SESSION["oauth_token_secret"] => '.$_SESSION["oauth_token_secret"].'</p>';
?>
  </body>
</html>