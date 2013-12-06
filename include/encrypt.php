<?php

//暗号化を回す回数
$iterationCount = 1000;
//「Salt」の文字数
$saltLength = 32;

//--------------------------------------
//パスワード登録時の暗号化処理
//--------------------------------------
// $password : 任意のパスワード(十分な長さが好ましい)
// $salt : 付加する「Salt」
// $saltLength : 「Salt」の長さ (最大32文字)
// $iterationCount : 暗号化の回数
// $encryptedpassword : 暗号化されたパスワード
//--------------------------------------

function encryptin($pass){
	//暗号化を回す回数
	global $iterationCount;
	//「Salt」の文字数
	global $saltLength;

	//「Salt」の作成
	$salt = substr(md5(uniqid(rand(), true)), 0, $saltLength);
	// 任意のパスワードに「Salt」を付加
	$encryptedpassword = $salt.$pass;
	//「Iteration Count」回数分の暗号化実行
	for($i=0;$i<$iterationCount;$i++)
	{
		$encryptedpassword = hash("sha256",$encryptedpassword);
	}
	//暗号化されたパスワードに「Salt」を付加した状態で、ファイル等に保存
	$pass = $salt.$encryptedpassword;
	return $pass;
}




//--------------------------------------
//パスワード認証処理
//--------------------------------------
// $password : 入力されたパスワード
// $databasePassword : データファイルに保存された、「Salt」付の暗号化パスワード
// $salt : 「$databasePassword」から抜き出された「Salt」
// $saltLength : 「Salt」の長さ
// $iterationCount : 暗号化の回数
// $encryptedpassword : 暗号化されたパスワード
//--------------------------------------

function pass_check($password , $databasePassword){
	//暗号化を回す回数
	global $iterationCount;
	//「Salt」の文字数
	global $saltLength;

	//「Salt」を取得
	$salt = substr($databasePassword, 0, $saltLength);
	// 入力されたパスワードに、「Salt」を付加
	$encryptedpassword = $salt.$password;
	//「Iteration Count」回数分の暗号化実行
	for($i=0;$i<$iterationCount;$i++)
	{
		$encryptedpassword =  hash("sha256",$encryptedpassword);
	}
	// 暗号化されたパスワードに「Salt」を付加した状態で、
	// データファイル上の暗号化された文字列との比較を行い、
	// 一致すれば認証成功
	if ($databasePassword == $salt.$encryptedpassword)
	{
		return true;// 認証成功
	}
	else
	{
		return false;// 認証失敗
	}
}


?>