<span><?php
// OAuth用ライブラリ「twitteroauth」
require_once 'twitteroauth/twitteroauth.php';
// アプリ登録した際に発行された値を入れて下さい。
$consumer_key = 'fDbEZYlCo1VYA56n4PvQ';
$consumer_secret = 'DLbKrDLqyuPFqUMrVPorYcK2gMhT8TvpBxGEeB8DKZE';
$access_token = '23560217-ljitT55i03ovRCU3nfIzEHxKSwB6O4Db23lR5G8EH';
$access_token_secret = 'L3IPm0kDUMdK24gNh3WDMif4HI8dR8LtafAyxY0HTTXE1';
// オブジェクト生成
$tw_obj = new TwitterOAuth (
  $consumer_key,
  $consumer_secret,
  $access_token,
  $access_token_secret);
// REST_API指定(今回はタイムラインのREST_API)
$tw_rest_api = '<a class="linkclass" href="https://api.twitter.com/1.1/statuses/home_timeline.json">https://api.twitter.com/1.1/statuses/home_timeline.json</a>';
// メソッド指定
$request_method = 'GET';
// オプション指定
$options = array (
  'count'=> 20);
// ユーザータイムライン取得
$tw_obj_request = $tw_obj->OAuthRequest (
  $tw_rest_api,
  $request_method,
  $options);
// デバッグ出力
var_dump ($tw_obj_request);
// json形式で取得
$tw_obj_request_json = json_decode($tw_obj_request, true);
// 変数生成
$time_line_texts = '';
// 取得したデータを回して入れていく
foreach ($tw_obj_request_json as $key => $value) {
  $time_line_texts .= '<p>'.$value["text"].'</p>';
}
// 表示
print($time_line_texts);
?>
</span>