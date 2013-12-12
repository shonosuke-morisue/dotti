<?php
require_once './include/session.php';
require_once './include/db_access.php';

include './include/header.php';

class question {
	public $question_id;
	public $question_title;
	public $contributor;
	public $img_url_0;
	public $answer_count_0;
	public $answer_ratio_0;
	public $img_url_1;
	public $answer_count_1;
	public $answer_ratio_1;
	
	// 設問の初期化
	function __construct($question_id) {
		// DBに接続
		$link = db_access();
		// DBから設問の情報を取得する
		$result = mysql_query('SELECT * FROM question WHERE question_id = '.$question_id.';');
		db_error($result);
		$question = mysql_fetch_assoc($result);

		// 設問の各種情報を変数に格納
		$this->question_id = $question['question_id'];
		$this->question_title = $question['question_title'];
		$this->contributor = $question['contributor'];
		$this->img_url_0 = $question['img_url_0'];
		$this->answer_count_0 = $question['answer_count_0'];
		$this->answer_ratio_0 = $question['answer_ratio_0'];
		$this->img_url_1 = $question['img_url_1'];
		$this->answer_count_1 = $question['answer_count_1'];
		$this->answer_ratio_1 = $question['answer_ratio_1'];
		
    	//DB切断処理
    	db_close($link);
	}
	
	// 投票処理
	function answer($answer) {
		;
	}
}
?>
  <br>
  ■設問
  <?php
  for ($i = 1; $i < 10; $i++) {
		$question_id = $i;
		$question = new question($question_id);
		echo '<hr>'.$question->question_id.'<br>'.$question->question_title.'<br><img src="./images/'.$question->img_url_0.'">'.'<br><img src="./images/'.$question->img_url_1.'">'.'<hr>';
  }
  $question_id = 10;
  $question = new question($question_id);
  echo "<pre>test<br>";
  var_dump($question);
  echo "</pre>";
  echo '<hr>'.$question->question_title.'<hr>'; 
  ?>
  <br>


<?php
include './include/footer.php';
?>
