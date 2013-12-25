<?php
require_once './include/session.php';

include './include/header.php';
?>
<br>
<?php
// DBに接続
$link = db_access();

// 新規設問を取得
$result = mysql_query('SELECT * FROM question ORDER BY question_id DESC LIMIT 5;');
db_error($result);

//DB切断処理
db_close($link);

$question_list = array();
while ($row = mysql_fetch_assoc($result)) {
	$question = new question($row["question_id"]);
	$question_list[] = $question;
}

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
