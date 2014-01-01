<?php
//----------------------------------------
// 設問の情報を取得、処理するクラス
// ・設問の情報の取得
// ・投票処理
//----------------------------------------
class question {
	public $question_id;
	public $question_title;
	public $user_id;
	public $img_url = array();
	public $answer_count = array();
	public $answer_ratio = array();

	// 設問の初期化
	function __construct($question_id) {
	
		// DBに接続
		$db_link = db_access();
		$sth = $db_link->prepare('SELECT * FROM question WHERE question_id = :question_id');
		$sth->bindValue(':question_id' , $question_id , PDO::PARAM_INT);
		$sth->execute();
		
		$question = $sth->fetch();
		
		// 設問の各種情報を変数に格納
		$this->question_id = $question['question_id'];
		$this->question_title = $question['question_title'];
		$this->user_id = $question['user_id'];
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
		$sth = $db_link->prepare('INSERT INTO answer( user_id , question_id , answer , answer_msg ) VALUES ( :user_id , :question_id , :answer , :answer_msg )');
		$sth->bindValue(':user_id' , $user_id , PDO::PARAM_INT);
		$sth->bindValue(':question_id' , $this->question_id , PDO::PARAM_INT);
		$sth->bindValue(':answer' , $answer , PDO::PARAM_INT);
		$sth->bindValue(':answer_msg' , '' , PDO::PARAM_STR);
		$sth->execute(); 
		
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
		$sth = $db_link->prepare('UPDATE question SET
				 answer_count_0 = :answer_count_0,
				 answer_ratio_0 = :answer_ratio_0,
				 answer_count_1 = :answer_count_1,
				 answer_ratio_1 = :answer_ratio_1
				 WHERE
				 question_id = :question_id' );
		
		$sth->bindValue(':answer_count_0' , $this->answer_count[0] , PDO::PARAM_INT);
		$sth->bindValue(':answer_ratio_0' , $this->answer_ratio[0] , PDO::PARAM_INT);
		$sth->bindValue(':answer_count_1' , $this->answer_count[1] , PDO::PARAM_INT);
		$sth->bindValue(':answer_ratio_1' , $this->answer_ratio[1] , PDO::PARAM_INT);
		$sth->bindValue(':question_id' , $this->question_id , PDO::PARAM_INT);
		$sth->execute();
		
		
    	//DB切断処理
    	db_close($db_link);
	}
}
?>
