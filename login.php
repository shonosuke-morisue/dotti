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
