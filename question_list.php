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
	function answer($answer , $user_id) {
		// DBに接続
		$link = db_access();
		// 投票をDBに登録
		$result = mysql_query('INSERT INTO answer( user_id , question_id , answer , answer_msg ) VALUES ("'.$user_id.'","'.$this->question_id.'","'.$answer.'","");');
		db_error($result);

		// 投票数に+1する
		if ($answer == 0) {
			$this->answer_count[0] = $this->answer_count[0] + 1 ;
		}elseif ($answer == 1){
			$this->answer_count[1] = $this->answer_count[1] + 1 ;
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
