<?php
require_once './include/session.php';
require_once './include/db_access.php';

include './include/header.php';

class question {
	public $question_id;
	public $question_title;
	public $contributor;
	public $img_url = array();
	public $answer_count = array();
	public $answer_ratio = array();

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
		$this->img_url[0] = $question['img_url_0'];
		$this->answer_count[0] = $question['answer_count_0'];
		$this->answer_ratio[0] = $question['answer_ratio_0'];
		$this->img_url[1] = $question['img_url_1'];
		$this->answer_count[1] = $question['answer_count_1'];
		$this->answer_ratio[1] = $question['answer_ratio_1'];

    	//DB切断処理
    	db_close($link);
	}

	// 投票処理
	function answer($answer) {
		// DBに接続
		$link = db_access();
		// 投票をDBに登録
		$result = mysql_query('INSERT answer( user_id , quetion_id , answer , answer_msg ) VALUES ('.$user_id.','.$this->question_id.','.$answer.',"");');
		db_error($result);

		// 投票した結果の設問の総投票数を取得
		for ($i = 0; $i < 2; $i++) {
			$result = mysql_query('SELECT * FROM answer WHERE question_id = '.$this->question_id.' AND answer = '.$answer.';');
			db_error($result);

			$question_answer = mysql_fetch_assoc($result);

			$answer_list = array();
			foreach ($question_answer as $answer) {
				array_push($answer_list, $answer);
			}

			$this->answer_count[$i] = $array_count_values($answer_list);

		}


		// 投票した結果の総投票数から投票比率を取得

		// どちらかの投票数が0の時は0なのですよ。
		if ( $this->answer_count[0] == 0 ) {
			$this->answer_ratio[0] = 0;
			$this->answer_ratio[1] = 100;
		}elseif ($this->answer_count[1] == 0){
			$this->answer_ratio[0] = 100;
			$this->answer_ratio[1] = 0;
		}else{
			// 投票数が0でなければ比率計算
			$this->answer_ratio[0] = round( 100 * ( $this->answer_count[0] / ( $this->answer_count[0] + $this->answer_count[1] )) , 2 );
			$this->answer_ratio[1] = 100 - $this->answer_ratio[0];
		}

		// DBの投票数、投票比率を更新
		$result = mysql_query('UPDATE question SET
				 answer_count_0 ='.$this->answer_count[0].',
				 answer_ratio_0 ='.$this->answer_ratio[0].',
				 answer_count_1 ='.$this->answer_count[1].',
				 answer_ratio_1 ='.$this->answer_ratio[1].'
				 WHERE
				 question_id = '.$this->question_id.';');
		db_error($result);

    	//DB切断処理
    	db_close($link);
	}
}
?>
<br>
■設問
<?php
if (isset($_GET['question_id'])) {
	$question_id = $_GET['question_id'];
	$question = new question($question_id);
	echo '<hr>'.$question->question_id.'<br>'.$question->question_title.'<br><img src="./images/'.$question->img_url[0].'">'.'<br><img src="./images/'.$question->img_url[1].'">'.'<hr>';
}else {
	echo "はずれ！";
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
