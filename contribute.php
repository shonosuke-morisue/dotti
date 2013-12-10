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
$upload_key   = 'upfile';
$image_dir    = 'images';
$thumb_dir    = 'thumbs';
$max_filesize = 50000;
$max_width    = 100;
$max_height   = 100;
$thumb_width  = 32;
$thumb_height = 32;

/* 処理 */
if (isset($_FILES[$upload_key])) {

    try {

    	for ($i = 0; $i < 2; $i++) {
	        $error = $_FILES[$upload_key]['error'];

	//         // 配列は除外
	//         if (is_array($error)) {
	//             throw new RuntimeException('複数ファイルの同時アップロードは許可されていません。');
	//         }

	        // エラーチェック
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
	        $name = "{$rand}.{$ext}";

	        // 画像リソースを生成
	        $img = call_user_func("imagecreatefrom{$mime}", $tmp_name);
	        if (!$img) {
	            throw new RuntimeException('画像リソースの生成に失敗しました。');
	        }

	        // サムネイルを作成
	        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
	        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
	        if (!call_user_func("image{$mime}", $thumb, "{$thumb_dir}/{$name}")) {
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
	            if (!call_user_func("image{$mime}", $new_img, "{$image_dir}/{$name}")) {
	                throw new RuntimeException('サムネイルの保存に失敗しました。');
	            }
	        } else {
	            // リサイズの必要がなければそのままファイルを移動
	            if (!move_uploaded_file($tmp_name, "{$image_dir}/{$name}")) {
	                throw new RuntimeException('画像の保存に失敗しました。');
	            }
	        }

	        $msg = '<span style="color:green;">ファイルをアップロードしました！</span>';

    	}
    } catch (Exception $e) {

        $msg = '<span style="color:red;">' . $e->getMessage() . '</span>';

    }

}

?>


<?php if (isset($msg)): ?>
    <p><?=$msg?></p>
<?php endif; ?>
    <form enctype="multipart/form-data" method="post" action="">
      <fieldset>
        <legend>画像ファイルを選択(JPEG, GIF, PNGのみ対応)</legend>
        <label><input type="file" name="upfile[]"></label><br>
        <label><input type="file" name="upfile[]"></label><br>
        <label><input type="submit" value="送信"></label>
      </fieldset>
    </form>






<?php
include './include/footer.php';
?>
