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
  <form action="logout.php" method="POST">
  <input type="submit" name="logout" value="ログアウト" />
  </form>
  <br>
  <form action="question.php" method="GET">
      <input type="radio" name="question_id" value="5">5<br>
      <input type="radio" name="question_id" value="6">6<br>
      <input type="radio" name="question_id" value="7">7<br>
      <input type="radio" name="question_id" value="8">8<br>
      <input type="radio" name="question_id" value="9">9<br>
      <input type="radio" name="question_id" value="10">10<br>
  <input type="submit" value="投票する" />
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
