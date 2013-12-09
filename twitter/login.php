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

echo "<pre>";
var_dump($_SESSION['oAccessToken']);
echo "</pre>";

?>



<?php
//twitteroauth.phpをインクルードします。ファイルへのパスはご自分で決めて下さい。
require_once("twitteroauth/twitteroauth.php");

//TwitterAPI開発者ページでご確認下さい。
//Consumer keyの値を格納
$sConsumerKey = "fDbEZYlCo1VYA56n4PvQ";
//Consumer secretの値を格納
$sConsumerSecret = "DLbKrDLqyuPFqUMrVPorYcK2gMhT8TvpBxGEeB8DKZE";
//Access Tokenの値を格納
$sAccessToken = "23560217-ljitT55i03ovRCU3nfIzEHxKSwB6O4Db23lR5G8EH";
//Access Token Secretの値を格納
$sAccessTokenSecret = "L3IPm0kDUMdK24gNh3WDMif4HI8dR8LtafAyxY0HTTXE1";

//OAuthオブジェクトを生成する
$twObj = new TwitterOAuth($sConsumerKey,$sConsumerSecret,$sAccessToken,$sAccessTokenSecret);

//home_timelineを取得するAPIを利用。Twitterからjson形式でデータが返ってくる
$vRequest = $twObj->OAuthRequest("https://api.twitter.com/1.1/users/show.json","GET",array("user_id"=>$_SESSION['user_id'],"screen_name"=>$_SESSION["screen_name"]));

//Jsonデータをオブジェクトに変更
$oObj = json_decode($vRequest);


//オブジェクトを展開
if(isset($oObj->{'errors'}) && $oObj->{'errors'} != ''){
    ?>
    取得に失敗しました。<br/>
    エラー内容：<br/>
    <pre>
    <?php var_dump($oObj); ?>
    </pre>
<?php
}else{
	//タイムゾーンの設定
	date_default_timezone_set('Asia/Tokyo');
    //オブジェクトを展開
	echo "<pre>";
	var_dump($oObj);
	echo "</pre>";
}


?>

$oObj["profile_image_url"]:<?php echo $oObj->{'profile_image_url'}; ?>
<br>
<img src='<?php echo $oObj->{'profile_image_url'}; ?>'>

// フォローボタンテスト
<a href="https://twitter.com/<?php echo $oObj->{'screen_name'}; ?>" class="twitter-follow-button" data-show-count="false">Follow @<?php echo $oObj->{'screen_name'}; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

  </body>
</html>