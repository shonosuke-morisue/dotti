<?php
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
		$db_link = db_access();
		$sql = 'SELECT * FROM question WHERE question_id = '.$question_id;
		$result = $db_link->query($sql);

		$question = $result->fetch();
		
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
    	db_close($db_link);
	}

	// 投票処理
	function answer($answer , $user_id) {
		// DBに接続
		$db_link = db_access();
		$sql = 'INSERT INTO answer( user_id , question_id , answer , answer_msg ) VALUES ("'.$user_id.'","'.$this->question_id.'","'.$answer.'","")';

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
		$result = $db_link->query('UPDATE question SET
				 answer_count_0 ='.$this->answer_count[0].',
				 answer_ratio_0 ='.$this->answer_ratio[0].',
				 answer_count_1 ='.$this->answer_count[1].',
				 answer_ratio_1 ='.$this->answer_ratio[1].'
				 WHERE
				 question_id = '.$this->question_id);

    	//DB切断処理
    	db_close($db_link);
	}
}
?>
