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
}else {
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


//OAuthオブジェクトを生成する
$twObj = new TwitterOAuth(ConsumerKey,ConsumerSecret,$_SESSION["oauth_token"],$_SESSION["oauth_token_secret"]);
//ユーザー情報を取得するAPIを利用。Twitterからjson形式でデータが返ってくる
$vRequest = $twObj->OAuthRequest("https://api.twitter.com/1.1/users/show.json","GET",array("user_id"=>$_SESSION['user_id'],"screen_name"=>$_SESSION["screen_name"]));
//Jsonデータをオブジェクトに変更
$oObj = json_decode($vRequest);

if(isset($oObj->{'errors'}) && $oObj->{'errors'} != ''){
}

//-------------------------
//取得した値をSESSIONに格納
//-------------------------
$_SESSION['name'] = $oObj->{'name'};
$_SESSION['profile_image_url'] = $oObj->{'profile_image_url'};

//-------------------------
//DBのユーザー情報と比較
//-------------------------

//ユーザー情報を取得
$user = new user($_SESSION['user_id']);

$db_link = db_access();

// ユーザー名を確認して未登録の場合は新規登録、登録済みの場合はデータを更新する。
if ($_SESSION['user_id'] == $user->user_id) {
	// userテーブルへユーザーデータの更新
	// 最新のデータに更新する必要があるので、必ずtwitterAPIから取得したデータをUPDATEすること
	$sql = 'UPDATE user SET screen_name = "'.$_SESSION["screen_name"].'", name ="'.$_SESSION["name"].'", profile_image_url="'.$_SESSION["profile_image_url"].'"  WHERE user_id = "'.$_SESSION["user_id"].'"';
	$result = $db_link->query($sql);
}else{
	//タイムゾーンの設定
	date_default_timezone_set('Asia/Tokyo');
	//日付の取得
	$datetime = date("Y-m-d H:i:s");
	//userテーブルへユーザーデータの登録
	$sql = 'INSERT INTO user( user_id , screen_name , name , profile_image_url ,time ) VALUES ( "'.$_SESSION['user_id'].'","'.$_SESSION['screen_name'].'","'.$_SESSION['name'].'","'.$_SESSION['profile_image_url'].'","'.$datetime.'" )';
	$result = $db_link->query($sql);
}


//DB切断処理
db_close($db_link);


// loginページへリダイレクト
header("Location: http://morisue.sakura.ne.jp/dotti/login.php");
?>