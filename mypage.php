<?php
require_once './include/session.php';

if (isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
}elseif (!isset($_SESSION['user_id'])){
	header("Location: http://morisue.sakura.ne.jp/dotti/login.php");
}

include './include/header.php';

echo "ID:".$user_id."<br>";
$user = new user($user_id);

//OAuthオブジェクトを生成する
$twObj = new TwitterOAuth(ConsumerKey,ConsumerSecret,$_SESSION["oauth_token"],$_SESSION["oauth_token_secret"]);
//ユーザー情報を取得するAPIを利用。Twitterからjson形式でデータが返ってくる
$vRequest = $twObj->OAuthRequest("https://api.twitter.com/1.1/users/show.json","GET",array("user_id"=>$user->user_id,"screen_name"=>$user->screen_name));
//Jsonデータをオブジェクトに変換
$oObj = json_decode($vRequest);

$profile_image_url_bigger = str_replace("image_normal","image_bigger",$oObj->{'profile_image_url'});
?>
<hr>

<div class="">
	<a href="https://twitter.com/<?php echo $oObj->{'screen_name'};?>"><img src="<?php echo $profile_image_url_bigger;?>"></a><br>
	<a href="https://twitter.com/<?php echo $oObj->{'screen_name'};?>"><?php echo $oObj->{'screen_name'};?></a><br>
</div>
<div class="">
	<?php echo $oObj->{'name'};?><br>
</div>
<div class="">
	<?php echo $oObj->{'description'};?><br>
</div>

<!-- twitterフォローボタン -->
<a href="https://twitter.com/<?php echo $oObj->{'screen_name'};?>" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @<?php echo $oObj->{'screen_name'};?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<br>

<form action="question_list.php" method="POST">
<input type="submit" name="question_mine" value="自分投稿" />
</form>

<form action="question_list.php" method="POST">
<input type="submit" name="answer_mine" value="自分投票" />
</form>


<hr>

<?php
include './include/footer.php';
?>