<?php
require_once './include/session.php';
require_once './include/db_access.php';
// OAuth用ライブラリ「twitteroauth」
require_once './twitteroauth/twitteroauth.php';

include './include/header.php';

?>

<a href="./login.php">ログインページ</a><br>
<a href="./logout.php">ログアウトページ</a><br>
<a href="./mypage.php">リザルトページ</a><br>


<?php

// 画像アップロードテスト
// http://qiita.com/mpyw/items/73ee77a9535cc65eff1e


/* 設定 */
$title = "未入力";
if ( isset($_POST["title"]) || is_string($_POST["title"])) {
	$title = $_POST["title"];
};
$name = array();
$upload_key   = 'upfile';
$image_dir    = 'images';
$thumb_dir    = 'thumbs';
$max_filesize = 1048576; // 1メガバイト 
$max_width    = 600;
$max_height   = 600;
$thumb_width  = 150;
$thumb_height = 150;

/* 処理 */
		
if (isset($title) && isset($_FILES[$upload_key])) {

    try {
 			   	
    	// タイトルエラーチェック
        switch ($title) {
            case mb_strlen($title) > 140: 
                throw new RuntimeException('タイトルは140文字以内にしてください。');
        }
        
        
    	for ($i = 0; $i < 2; $i++) {
	        $error = $_FILES[$upload_key]['error'];

	//         // 配列は除外
	//         if (is_array($error)) {
	//             throw new RuntimeException('複数ファイルの同時アップロードは許可されていません。');
	//         }

	        // 画像エラーチェック
	        switch ($error) {
	            case UPLOAD_ERR_INI_SIZE:
	                throw new RuntimeException('php.iniで許可されている最大サイズを超過しました。');
	            case UPLOAD_ERR_FORM_SIZE:
	                throw new RuntimeException('フォームで許可されている最大サイズを超過しました。');
	            case UPLOAD_ERR_PARTIAL:
	                throw new RuntimeException('ファイルが壊れています。');
	            case UPLOAD_ERR_NO_FILE:
	                throw new RuntimeException('ファイルが選択されていません。');
	            case UPLOAD_ERR_NO_TMP_DIR:
	                throw new RuntimeException('テンポラリディレクトリが見つかりません。');
	            case UPLOAD_ERR_CANT_WRITE:
	                throw new RuntimeException('テンポラリデータの生成に失敗しました。');
	            case UPLOAD_ERR_EXTENSION:
	                throw new RuntimeException('エクステンションでエラーが発生しました。');
	        }

	        // 一時ファイル名
	        $tmp_name = $_FILES[$upload_key]['tmp_name'][$i];

	        // ファイルサイズ
	        $size = $_FILES[$upload_key]['size'][$i];

	        // 不正なファイルでないかチェック
	        if (!is_uploaded_file($tmp_name)) {
	            throw new RuntimeException('不正なファイルです。<br>');
	        }

	        // このスクリプトで定義されたサイズ上限のオーバーチェック
	        if ($size > $max_filesize) {
	            throw new RuntimeException("{$max_filesize}バイトを超過するファイルは受理できません。");
	        }

	        // 画像ファイル情報取得
	        $info = getimagesize($tmp_name);

	        // 取得に失敗したときは画像ファイルではない
	        if ($info === false) {
	            throw new RuntimeException('画像ファイルではありません。');
	        }

	        // MimeTypeを調べる
	        switch ($info['mime']) {
	            case 'image/gif':
	                $mime = $ext = 'gif';
	                break;
	            case 'image/png':
	                $mime = $ext = 'png';
	                break;
	            case 'image/jpeg':
	                $mime = 'jpeg';
	                $ext  = 'jpg';
	                break;
	            default:
	                throw new RuntimeException('この種類の画像形式は受理できません。');
	        }

	        // もとの画像の幅と高さ
	        $width  = $info[0];
	        $height = $info[1];

	        // ユニークなファイル名を拡張子を含めて生成
	        $rand = sha1(mt_rand() . microtime());
	        $name[$i] = "{$rand}.{$ext}";

	        // 画像リソースを生成
	        $img = call_user_func("imagecreatefrom{$mime}", $tmp_name);
	        if (!$img) {
	            throw new RuntimeException('画像リソースの生成に失敗しました。');
	        }

	        // サムネイルを作成
	        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
	        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
	        if (!call_user_func("image{$mime}", $thumb, "{$thumb_dir}/{$name[$i]}")) {
	            throw new RuntimeException('サムネイルの保存に失敗しました。');
	        }

	        // 最大幅・高さを超過していないかチェック・縦横比を維持して新しいサイズを定義
	        if ($width > $height && $width > $max_width) {
	            $resize = true;
	            $new_width  = $max_width;
	            $new_height = $height * $new_width / $width;
	        } elseif ($height > $max_height) {
	            $resize = true;
	            $new_height = $max_height;
	            $new_width  = $width * $new_height / $height;
	        } else {
	            $resize = false;
	        }

	        if ($resize) {
	            // リサイズの必要があれば縦横比を維持してリサイズ
	            $new_img = imagecreatetruecolor($new_width, $new_height);
	            imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	            if (!call_user_func("image{$mime}", $new_img, "{$image_dir}/{$name[$i]}")) {
	                throw new RuntimeException('サムネイルの保存に失敗しました。');
	            }
	        } else {
	            // リサイズの必要がなければそのままファイルを移動
	            if (!move_uploaded_file($tmp_name, "{$image_dir}/{$name[$i]}")) {
	                throw new RuntimeException('画像の保存に失敗しました。');
	            }
	        }

	        $msg = '<span style="color:green;">ファイルをアップロードしました！</span>';

    	}
    	
    	//タイムゾーンの設定
    	date_default_timezone_set('Asia/Tokyo');
    	//日付の取得
    	$datetime = date("Y-m-d H:i:s");
    	
    	//db登録処理
    	$db_link = db_access();
    	//questionへ設問データの登録
		$sth = $db_link->prepare('INSERT INTO question( question_title , user_id , img_url_0 , img_url_1 , time ) VALUES ( :title , :user_id , :name_0 , :name_1 , :datetime )');
		$sth->bindValue(':title' , $title , PDO::PARAM_STR );
		$sth->bindValue(':user_id' , $user_id , PDO::PARAM_INT );
		$sth->bindValue(':name_0' , $name[0] , PDO::PARAM_STR );
		$sth->bindValue(':name_1' , $name[1] , PDO::PARAM_STR );
		$sth->bindValue(':datetime' , $datetime , PDO::PARAM_INT );
		$sth->execute();
		    	
    	//DB切断処理
    	db_close($db_link);
    	$question = true;
    	
    } catch (Exception $e) {

        $msg = '<span style="color:red;">' . $e->getMessage() . '</span>';

    }

}

?>


<?php if (!isset($user_id)): ?>
    <p>ログインしてません</p>
<?php endif; ?>
<?php if (isset($msg)): ?>
    <p><?=$msg?></p>
<?php endif; ?>
<?php if (isset($question)): ?>
    <p><?=$title?></p>
    <img src="./thumbs/<?=$name[0]?>">
    <img src="./thumbs/<?=$name[1]?>">
    <br>
    <img src="./images/<?=$name[0]?>">
    <img src="./images/<?=$name[1]?>">
    <br>
<?php endif; ?>
<form enctype="multipart/form-data" method="post" action="">
      <fieldset>
        <legend>画像ファイルを選択(JPEG, GIF, PNGのみ対応)</legend>
        <label>■タイトル<br><input type="text" name="title" size="10" maxlength="140" value="どっちが○○？" placeholder="お題を入力してください" required></label><br>
        <label>■投稿画像１<br><input type="file" name="upfile[]" required></label><br>
        <label>■投稿画像２<br><input type="file" name="upfile[]" required></label><br>
        <label><input type="submit" value="送信"></label>
      </fieldset>
</form>






<?php
include './include/footer.php';
?>
