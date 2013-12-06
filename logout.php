<?php
include './include/session.php';
include './include/db_access.php';

include './include/header.php';

echo('<hr>');

?>


■ログアウトページ<br>
<?php
    print('セッション変数の確認をします。<br>');
    if (!isset($_SESSION["user_name"])){
        print('セッション変数user_nameは登録されていません。<br>');
    }else{
        print($_SESSION["user_name"].'<br>');
    }

    print('セッションIDの確認をします。<br>');
    if (!isset($_COOKIE["PHPSESSID"])){
        print('セッションは登録されていません。<br>');
    }else{
        print($_COOKIE["PHPSESSID"].'<br>');
    }
?>

<hr>

<a href="./login.php">ログインページ</a><br>
<a href="./logout.php">ログアウトページ</a><br>
<a href="./mypage.php">リザルトページ</a><br>




<?php
include './include/footer.php';
?>
