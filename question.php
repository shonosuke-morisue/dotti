<?php
require_once './include/session.php';

include './include/header.php';

?>
<br>
■設問
<?php
if (isset($_GET['question_id'])) {
	$question_id = $_GET['question_id'];
	$question = new question($question_id);
	
	if (isset($question->question_id)) {
	
		echo '■設問<br><hr>'.$question->question_id.'<br>'.$question->question_title.'<br><img src="./images/'.$question->img_url[0].'">'.'<br><img src="./images/'.$question->img_url[1].'">'.'<hr>';
	
		// 投票済みチェック
	
		if (isset($user_id)) {
			// DBに接続
			$link = db_access();
	
			// 投票状況を取得
			$result = mysql_query('SELECT * FROM answer WHERE question_id = "'.$question->question_id.'" AND user_id = "'.$user_id.'";');
			db_error($result);
			$answer = mysql_fetch_assoc($result);
	
			if ($answer["answer"] == 0) {
				$post_answer = "A";
			}elseif ($answer["answer"] == 1){
				$post_answer = "B";
			}
			
			if ($answer) {
				echo "すでに".$post_answer."に投票済みです<br>";
			}
			
			// まだ投票してなくて、投票がPOSTされていれば投票処理
			if (!$answer && isset($_POST["answer"])) {
				$question->answer($_POST["answer"], $user_id) ;
				
				if ($_POST["answer"] == 0) {
					$post_answer = "A";
				}elseif ($_POST["answer"] == 1){
					$post_answer = "B";
				}
				echo $post_answer."に投票しました！<br>";
			}elseif (!$answer){
			// まだ投票してないので投票ボタン表示
	?>
				<form action="?question_id=<?php echo $_GET['question_id']?>" method="POST">
				<input type="hidden" name="answer" value="0">
				<input type="submit" value="A" />
				</form>
	
				<form action="?question_id=<?php echo $_GET['question_id']?>" method="POST">
				<input type="hidden" name="answer" value="1">
				<input type="submit" value="B" />
				</form>
	<?php
			}
	
		}else {
	//ログインしてない場合は、ログインに誘導
	?>
			投票したい場合はログインしてください。
			<br>
			<a href="<?php print($sUrl);?>">Twitterアカウントでログイン</a><br>
	<?php
	
		}
	}else {
		echo "はずれ！<br>該当する[question_id]の設問はありません。 ";
	}

}else {
	echo "はずれ！<br>[question_id]を指定してね！ ";
}

?>
<br>


<?php
include './include/footer.php';
?>
