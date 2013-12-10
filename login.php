<?php
require_once './include/session.php';
require_once './include/db_access.php';
// OAuth用ライブラリ「twitteroauth」
require_once './twitteroauth/twitteroauth.php';

include './include/header.php';

?>


<a href="./contribute.php">投稿する</a><br>
<a href="./login.php">ログインページ</a><br>
<a href="./logout.php">ログアウトページ</a><br>
<a href="./mypage.php">リザルトページ</a><br>


<?php
// OAuth用ライブラリ「twitteroauth」
require_once 'twitteroauth/twitteroauth.php';
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
      ConsumerKey,
      ConsumerSecret);
    //call_backを指定して request tokenを取得
    $oOauthToken = $twitter_oauth_object->getRequestToken(CallBackUrl);
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
  <br>
  <br>
  <a href="<?php print($sUrl);?>">Twitterアカウントを使いログインする</a>
  <br>
  <form action="logout.php" method="POST">
  <input type="submit" name="logout" value="ログアウト" />
  </form>
  <br>
<?php
echo '<p>$_SESSION["user_id"] => '.$_SESSION['user_id'].'</p>';
echo '<p>$_SESSION["screen_name"] => '.$_SESSION['screen_name'].'</p>';
echo '<p>$_SESSION["oauth_token"] => '.$_SESSION["oauth_token"].'</p>';
echo '<p>$_SESSION["oauth_token_secret"] => '.$_SESSION["oauth_token_secret"].'</p>';

echo "<pre>";
var_dump($_SESSION['oAccessToken']);
echo "</pre>";

?>

<?php
include './include/footer.php';
?>
