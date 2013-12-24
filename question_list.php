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
		echo '■設問'.$question_list[$i]->question_id.'<br><hr><br>'.$question_list[$i]->question_title.'<br><img src="./images/'.$question_list[$i]->img_url[0].'">'.'<br><img src="./images/'.$question_list[$i]->img_url[1].'">'.'<hr>';
	$i++;
}
?>

<?php
include './include/footer.php';
?>
