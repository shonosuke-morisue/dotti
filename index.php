<?php
session_start();

//ログアウト処理
if (isset($_POST["logout"])) {
	//セッション変数は上書きして初期化
	$_SESSION = array();

	//cookieのセッションIDを破棄
	if (isset($_COOKIE["PHPSESSID"])) {
		setcookie("PHPSESSID", '', time() - 1800, '/');
	}
	//セッションを破棄
	session_destroy();
}

//DB接続処理
function db_access() {
	//mysql接続
	$link = mysql_connect('localhost', 'morisue', '7815659');
	if (!$link) {
		die('接続失敗です。'.mysql_error());
	}

	//DB選択
	$db_selected = mysql_select_db('dotti', $link);
	if (!$db_selected){
		die('データベース選択失敗です。'.mysql_error());
	}
	return $link;

	//文字コードをutf8に設定
	mysql_set_charset('utf8');
}


//DB切断処理
function db_close($link) {
	//mysql切断
	$close_flag = mysql_close($link);

	if ($close_flag){
		print('<p>切断に成功しました。</p>');
	}


	// エラーメッセージを格納する変数を初期化
	$error_message = "";
}


// ログインボタンが押されたかを判定
// 初めてのアクセスでは認証は行わずエラーメッセージは表示しないように
if (isset($_POST["login"])) {

	//DBからユーザー情報を取得して認証
	$link = db_access();

	//テーブルの内容を取得して表示
//	$_POST["user_name"] = mb_convert_encoding($_POST["user_name"], "utf-8", "auto");
	$result = mysql_query('SELECT * FROM user WHERE user_name = "'.$_POST['user_name'].'";');
	
	db_error($result);
	
	$user = mysql_fetch_assoc($result);
	
	// user_nameが「php」でpasswordが「password」だとログイン出来るようになっている
	if ($_POST["user_name"] == $user["user_name"] && $_POST["password"] == $user["password"]) {



		// ログインが成功した証をセッションに保存
		$_SESSION["user_name"] = $_POST["user_name"];

		// 管理者専用画面へリダイレクト
		$login_url = ((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . "/dotti/mypage.php");

		//$login_url = "http://{$_SERVER["HTTP_HOST"]}/php_10days/anq_mypage.php";
		header("Location: {$login_url}");
		exit;
	}
	$error_message = "ユーザ名もしくはパスワードが違っています。";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="robots" content="noindex,nofollow,noarchive">
<title>バトル</title>
	<link href="./morisueke.css" type="text/css" rel="stylesheet">
</head>
<body>




<hr>

<?php
    print('セッション変数の確認をします。<br>');
    if (!isset($_SESSION["user_name"])){
        print('セッション変数user_nameは登録されていません。<br>');
    }else{
        print($_SESSION["user_name"].'<br>');
    }

    print('セッションIDの確認をします。<br>');
    if (!isset($_COOKIE["PHPSESSID"])){
        print('セッションは登録されていません。<br>');
    }else{
        print($_COOKIE["PHPSESSID"].'<br>');
    }
?>

<hr>





<?php
if ($error_message) {
print '<font color="red">'.$error_message.'</font>';
}
?>
<form action="index.php" method="POST">
ユーザ名：<input type="text" name="user_name" value="" /><br />
パスワード：<input type="password" name="password" value="" /><br />
<input type="submit" name="login" value="ログイン" />
</form>

<hr>
<br>

<?php
// // セッションテスト
//     session_start();
//     if (!isset($_COOKIE["PHPSESSID"])){
//         print('初回の訪問です。セッションを開始します。');
//     }else{
//         print('セッションは開始しています。<br>');
//         print('セッションIDは '.$_COOKIE["PHPSESSID"].' です。');
//     }
?>







<?php

print($_SERVER["REQUEST_URI"]."<br>");

print($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]."<br>");

print((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]."<br>");


// キャラクタークラス
class player{


	public $name = '';
	public $maxhp = 100;
	public $hp = 100;
	public $atk = 10;
	public $def = 10;
	public $agi = 10;

	// 名前追加
	function add_name($get_name){
		$this->name = $get_name;
	}

	// ダメージ処理
	function damage($damage){
		$this->hp = $this->hp - $damage;
		if ($this->hp <= 0) {
			$this->hp = 0;
		}
	}

	//攻撃処理
	function attack($enemy_def){
		$player_atk = round( $this->atk * rand(80, 120) * 2 * 0.01);
		$enemy_def = round($enemy_def * rand(80, 120) * 0.01);
		$damage = $player_atk - $enemy_def;

		// 0以下の時には0点ダメージ
		if ($damage <= 0) {
			$damage = 0	;
		}
		return $damage;
	}
}


// キャラ生成

$player_01 = new player();
$player_01->add_name("<font color='red'>player_01</font>");
$player_01->atk = 11;
$player_02 = new player();
$player_02->add_name("<font color='blue'>player_02</font>");



//プレイヤ－１
echo '<br><div>■'.$player_01->name.'<br>
	  ＨＰ'.$player_01->hp.'/'.$player_02->maxhp.'<br>
	  攻撃'.$player_01->atk.'<br>
	  防御'.$player_01->def.'<br>
	  速度'.$player_01->agi.'<br>
	  </di>';

//プレイヤ－２
echo '<br><div>■'.$player_02->name.'<br>
	  ＨＰ'.$player_02->hp.'/'.$player_02->maxhp.'<br>
	  攻撃'.$player_02->atk.'<br>
	  防御'.$player_02->def.'<br>
	  速度'.$player_02->agi.'<br>
	  </di>';


//戦闘処理
function battle ($attacker , $defender){

	echo '<br><div>';
	while( $defender->hp > 0){

		//攻防入れ替え
		list($attacker,$defender) = array($defender,$attacker);

		// 攻撃処理
		$damage = $attacker->attack($defender->def);
		// ダメージ処理
		$defender->damage($damage);
		echo $damage.'点のダメージを受けて「'.$defender->name.'」のHPが残り'.$defender->hp.'点<br>';
	}
	echo $defender->name.'の負け！</div>';
}

battle($player_01,$player_02);


//mysql接続
$link = mysql_connect('localhost', 'morisue', '7815659');
if (!$link) {
	die('接続失敗です。'.mysql_error());
}

print('<p>接続に成功しました。</p>');

//DB選択
$db_selected = mysql_select_db('dotti', $link);
if (!$db_selected){
	die('データベース選択失敗です。'.mysql_error());
}

print('<p>dottiデータベースを選択しました。</p>');

//文字コードをutf8に設定
mysql_set_charset('utf8');

//テーブルの内容を取得して表示
$result = mysql_query('SELECT id,name FROM shouhin where id >= 1');

if (!$result) {
	die('クエリーが失敗しました。'.mysql_error());
}

while ($row = mysql_fetch_assoc($result)) {
	print('<p>');
	print('id='.$row['id']);
	print(',name='.$row['name']);
	print('</p>');
}


// パソコン→ほげ　に変更
$result = mysql_query('UPDATE shouhin SET name="ほげ" WHERE name = "パソコン"');

if (!$result) {
	die('クエリーが失敗しました。'.mysql_error());
}
//変更後のテーブルの内容を表示
$result = mysql_query('SELECT id,name FROM shouhin where id >= 1');

if (!$result) {
	die('クエリーが失敗しました。'.mysql_error());
}

while ($row = mysql_fetch_assoc($result)) {
	print('<p>');
	print('id='.$row['id']);
	print(',name='.$row['name']);
	print('</p>');
}

//ほげ→パソコン　に戻す
$result = mysql_query('UPDATE shouhin SET name="パソコン" WHERE name = "ほげ"');

if (!$result) {
	die('クエリーが失敗しました。'.mysql_error());
}

//データの追加
print('<p>データを追加します。</p>');

$sql = "INSERT INTO shouhin (id, name) VALUES (4, 'プリンター')";
$result_flag = mysql_query($sql);

if (!$result_flag) {
	die('INSERTクエリーが失敗しました。'.mysql_error());
}

print('<p>追加後のデータを取得します。</p>');

//追加後のテーブルの内容を表示
$result = mysql_query('SELECT id,name FROM shouhin where id >= 1');

if (!$result) {
	die('クエリーが失敗しました。'.mysql_error());
}

while ($row = mysql_fetch_assoc($result)) {
	print('<p>');
	print('id='.$row['id']);
	print(',name='.$row['name']);
	print('</p>');
}


//mysql切断
$close_flag = mysql_close($link);

if ($close_flag){
    print('<p>切断に成功しました。</p>');
}


?>






<br>
<br>



<?php

function put_tree($no, $line, $broths, $childs, $texts) {
  echo '<span class="line">' . $line . '</span>';
  echo '▼[' . $no . ']' . $texts[$no] . '<br>';

  $line = preg_replace('/├$/', '│', $line);
  $line = preg_replace('/└$/', '　', $line);

  $no = isset($childs[$no]) ? $childs[$no] : 0;

  while ($no > 0) {
    $tail = $broths[$no] ? '├' : '└';
    put_tree($no, $line . $tail, $broths, $childs, $texts);
    $no = $broths[$no];
  }
}

?>

<?php

$logs = array(
  array(1, 0, 'あああああ'), //記事番号・親記事番号・記事内容
  array(2, 1, 'いいいいい'),
  array(3, 1, 'ううううう'),
  array(4, 2, 'えええええ'),
  array(5, 3, 'おおおおお'),
  array(6, 3, 'かかかかか'),
  array(7, 0, 'ききききき'),
  array(8, 6, 'くくくくく'),
  array(9, 8, 'けけけけけ'),
  array(10, 7, 'こここここ')
);

$roots  = array();
$broths = array();
$childs = array();
$texts  = array();

foreach ($logs as $log) {
  list($no, $pno, $text) = $log;

  if ($pno == 0) {
    $roots[] = $no;
  } else {
    $broths[$no]  = isset($childs[$pno]) ? $childs[$pno] : 0;
    $childs[$pno] = $no;
  }
  $texts[$no] = $text;
}
rsort($roots);

foreach ($roots as $root) {
  put_tree($root, '', $broths, $childs, $texts);
}

?>



</body>
</html>
