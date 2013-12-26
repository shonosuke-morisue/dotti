<?php
require_once './include/session.php';

include './include/header.php';
?>
<br>
<?php
// DBに接続
$db_link = db_access();

switch (true) {
	case (isset($_POST["question_popularity"])):
		// 人気（投票数が多い）設問を取得
		$sql = 'SELECT question_id FROM question ORDER BY answer_count_0 + answer_count_1 DESC LIMIT 5';
		$result = $db_link->query($sql);
		$list_title = "人気投稿";
	break;

	case (isset($_POST["vote_new"])):
		// 最近投票された設問を取得
		$sql = 'SELECT DISTINCT question_id FROM answer ORDER BY time DESC LIMIT 5';
		$result = $db_link->query($sql);
		$list_title = "新着投票";
	break;

	case (isset($_POST["question_mine"])):
		// 自分が投稿した設問を取得
		$sql = 'SELECT question_id FROM question WHERE user_id = "'.$user_id.'" ORDER BY question_id DESC LIMIT 5';
		$result = $db_link->query($sql);
		$list_title = "自分投稿";
	break;

	case (isset($_POST["answer_mine"])):
		// 自分が投票した設問を取得
		$sql = 'SELECT question_id FROM answer WHERE user_id = "'.$user_id.'" ORDER BY time DESC LIMIT 5';
		$result = $db_link->query($sql);
		$list_title = "自分投票";
	break;

	default:
		// 新規設問を取得
		$sql = 'SELECT question_id FROM question ORDER BY question_id DESC LIMIT 5';
		$result = $db_link->query($sql);
		$list_title = "新着投稿";
	break;
}

//DB切断処理
db_close($db_link);

$question_list = array();
while ($row = $result->fetch()) {
	$question = new question($row["question_id"]);
	$question_list[] = $question;
}
?>

<!-- 
新着投稿　->　新しく投稿された設問
人気　->　投票数多い
新着投票　->　最近投票された
-->


<form action="question_list.php" method="POST">
<input type="submit" name="question_new" value="新着投稿" />
</form>
  
<form action="question_list.php" method="POST">
<input type="submit" name="question_popularity" value="人気" />
</form>

<form action="question_list.php" method="POST">
<input type="submit" name="vote_new" value="新着投票" />
</form>

<form action="question_list.php" method="POST">
<input type="submit" name="question_mine" value="自分投稿" />
</form>

<form action="question_list.php" method="POST">
<input type="submit" name="answer_mine" value="自分投票" />
</form>

<h1><?php echo $list_title;?></h1>
<hr>
<?php
$i = 0;
while ($i < count($question_list)) {
?>
	
	<div class="question_title">
		<a href="./question.php?question_id='<?php echo $question_list[$i]->question_id;?>'"><?php echo $question_list[$i]->question_title;?></a>
	</div>
	
	<div class="question_box">
		<div class="question_left">
			<img class="question_image" src="./images/<?php echo $question_list[$i]->img_url[0]; ?>" width="150px">
		</div>
		<div class="question_right">
			<img class="question_image" src="./images/<?php echo $question_list[$i]->img_url[1]; ?>" width="150px">
		</div>
	</div>
	<div class="question_box">
		<hr>
	</div>
	
<?php
	$i++;
}
?>

<?php
include './include/footer.php';
?>
