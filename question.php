<?php
require_once './include/session.php';

include './include/header.php';

?>
<br>
<?php
if (isset($_GET['question_id'])) {
	$question_id = $_GET['question_id'];
	$question = new question($question_id);
	$question_owner = new user($question->user_id);
	
	if (isset($question->question_id)) {
?>
		<div class="question_title">
			<?php echo $question->question_title;?>
		</div>
		
		<div class="question_box">
			<div class="question_left">
				<img class="question_image" src="./images/<?php echo $question->img_url[0]; ?>" width="150px">
			</div>
			<div class="question_right">
				<img class="question_image" src="./images/<?php echo $question->img_url[1]; ?>" width="150px">
			</div>
		</div>
		
		<div class="question_box">
			投稿者:<?php echo $question_owner->screen_name?>
		</div>
		
		<div class="question_box">
			A投票数:[<?php echo $question->answer_count['0']?>] <=> B投票数:[<?php echo $question->answer_count['1']?>]
		</div>
		
		<div class="question_box">
		<?php
		//--------------------------------------------
		//割合バーの表示処理
		//--------------------------------------------
		switch (0) {
			case $question->answer_ratio['0']:
			?>
			<div class="question_bar_right_only">
			</div>
			<?php
			break;
		
			case $question->answer_ratio['1']:
			?>
			<div class="question_bar_left_only">
			</div>
			<?php
			break;
			
			default:
			?>
			<div class="question_bar_left" style="width:<?php echo ($question->answer_ratio['0']*3-1); ?>px;">
			</div>
			<div class="question_bar_right" style="width:<?php echo ($question->answer_ratio['1']*3-1); ?>px;">
			</div>
			<?php
			break;
		}
		?>
		</div>
		<?php	
		// 投票済みチェック
	
		if (isset($user_id)) {
			// DBに接続
			$db_link = db_access();
							
			// 投票状況を取得
			$sql = 'SELECT * FROM answer WHERE question_id = "'.$question->question_id.'" AND user_id = "'.$user_id.'"';
			$result = $db_link->query($sql);
	
			//DB切断処理
			db_close($db_link);
				
			$answer = $result->fetch();
	
			if ($answer["answer"] == 0) {
				$post_answer = "A";
			}elseif ($answer["answer"] == 1){
				$post_answer = "B";
			}
			
			if ($answer) {
				echo "<p class='question_box'>すでに".$post_answer."に投票済みです</p>";
			}
			
			// まだ投票してなくて、投票がPOSTされていれば投票処理
			if (!$answer && isset($_POST["answer"])) {
				$question->answer($_POST["answer"], $user_id) ;
				
				if ($_POST["answer"] == 0) {
					$post_answer = "A";
				}elseif ($_POST["answer"] == 1){
					$post_answer = "B";
				}
				echo "<p class='question_box'>".$post_answer."に投票しました！</p>";
			}elseif (!$answer){
			// まだ投票してないので投票ボタン表示
				?>
				<br>
				<br>
				<div class="question_box">
			
					<div class="question_left">
						<form class="question_button" action="?question_id=<?php echo $_GET['question_id']?>" method="POST">
						<input type="hidden" name="answer" value="0">
						<input type="submit" value="Aに投票" />
						</form>
					</div>
			
					<div class="question_right">
						<form class="question_button" action="?question_id=<?php echo $_GET['question_id']?>" method="POST">
						<input type="hidden" name="answer" value="1">
						<input type="submit" value="Bに投票" />
						</form>
					</div>
					
				</div>
			<?php
			}
		}else {
		//ログインしてない場合は、ログインに誘導
		?>
		<div class="question_box">
				投票したい場合はログインしてください。
				<br>
				<a href="<?php print($sUrl);?>">Twitterアカウントでログイン</a><br>
		</div>
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
<hr>
最近投票したユーザー
<?php
// 最近投票したユーザーを取得してリストアップ
$db_link = db_access();
$sql = 'SELECT user_id FROM answer WHERE question_id = "'.$question->question_id.'" ORDER BY time DESC LIMIT 5';
$result = $db_link->query($sql);

$answer_list = array();
while ($row = $result->fetch()) {
	$user = new user($row['user_id']);
	if (!$user->screen_name == null) {
		?>
		<div class="">
		<a href="./mypage.php?user_id=<?php echo $user->user_id;?>"><img src="<?php echo $user->profile_image_url;?>"></a>
		<a href="./mypage.php?user_id=<?php echo $user->user_id;?>"><?php echo $user->screen_name;?></a><br>
		</div>
		<br>
		<?php
	} 
}
?>

<?php
include './include/footer.php';
?>
