<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>TRYPHP!　Twitter API V1.1 ホームタイムライン[ GET statuses/home_timeline ] サンプルコード</title>
</head>
<body>



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
$vRequest = $twObj->OAuthRequest("https://api.twitter.com/1.1/statuses/home_timeline.json","GET",array("count"=>"10"));

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
    $iCount = sizeof($oObj);;
    for($iTweet = 0; $iTweet<$iCount; $iTweet++){

        $iTweetId =                 $oObj[$iTweet]->{'id'};
        $sIdStr =                   (string)$oObj[$iTweet]->{'id_str'};
        $sText=                     $oObj[$iTweet]->{'text'};
        $sName=                     $oObj[$iTweet]->{'user'}->{'name'};
        $sScreenName=               $oObj[$iTweet]->{'user'}->{'screen_name'};
        $sProfileImageUrl =         $oObj[$iTweet]->{'user'}->{'profile_image_url'};
        $sCreatedAt =               $oObj[$iTweet]->{'created_at'};
        $sStrtotime=                strtotime($sCreatedAt);
        $sCreatedAt =               date('Y-m-d H:i:s', $sStrtotime);
?>
        <hr/>
        <h4><?php echo $sName; ?>さんのつぶやき</h4>
        <ul>
        <li>IDNO[id] : <?php echo $iTweetId; ?></li>
        <li>名前[name] : <?php echo $sIdStr; ?></li>
        <li>スクリーンネーム[screen_name] : <?php echo $sScreenName; ?></li>
        <li>プロフィール画像[profile_image_url] : <img src="<?php echo $sProfileImageUrl; ?>" /></li>
        <li>つぶやき[text] : <?php echo $sText; ?></li>
        <li>ツイートタイム[created_at] : <?php echo $sCreatedAt; ?></li>
        </ul>
<?php
    }//end for
}
?>



</body>
</html>