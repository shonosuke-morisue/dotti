<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow,noarchive">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>dotti</title>

<!-- css -->
	<link href="./dotti.css" type="text/css" rel="stylesheet">

<!-- javascript -->
	<script type="text/javascript" src="./js/iscroll.js"></script>
	<script type="text/javascript">

<!--
	var myScroll;
	function loaded() {
		myScroll = new iScroll("wrapper");
	}
	document.addEventListener("touchmove", function (e) { e.preventDefault(); }, false);
	document.addEventListener("DOMContentLoaded", function () { setTimeout(loaded, 200); }, false);
-->

	var myScroll;
	function loaded() {
	    myScroll = new iScroll("wrapper", {
	        useTransform: false,
	        onBeforeScrollStart: function (e) {
	            var target = e.target;
	            while (target.nodeType != 1) target = target.parentNode;
	            if (target.tagName != "SELECT" && target.tagName != "INPUT" && target.tagName != "TEXTAREA")
	                e.preventDefault();
	        }
	    });
	}
	document.addEventListener("touchmove", function (e) { e.preventDefault(); }, false);
	document.addEventListener("DOMContentLoaded", loaded, false);
	</script>

</head>
<body>

<?php
//--------------------------------------
//セッションのアクセストークンのチェック
//--------------------------------------
if (!isset($_SESSION["user_id"])) {
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
    }else {
      $bAuthorizeBoolean = true;
    }
    //Authorize url を取得
    $sUrl = $twitter_oauth_object->getAuthorizeURL($sToken, $bAuthorizeBoolean);
}
?>
<header id="header">
	<section id="header">
		<?php if(isset($_SESSION["user_id"])): ?>
			<img width="36px" height="36px" src="<?php echo $_SESSION['profile_image_url']; ?>">:<?php echo $_SESSION["screen_name"] ?>
		<?php else: ?>
			<a href="<?php print($sUrl);?>">Twitterアカウントでログイン</a>
		<?php endif; ?>
	</section>
</header>

<div id="wrapper">
	<div id="scroller">



