<?php

require_once('contents.php');


/* 
関数を定義
function 関数名(引数){        引数＝あるデータが渡される
         return 返り値;      返り値＝あるデータを返す
}
関数を実行
　関数名(引数)； 
※ ↑でタイトルや記事を引っ張る？
<th><?php echo (関数名idData or titleData or contentData)($column['(関数名idData or titleData or contentData)']) ?></th>
*/

//1.データ接続
function dbConnect()
{
    $dsn = 'mysql:host=localhost;dbname=blog;charset=utf8';
    $user = 'bloguser';
    $pass = 'yudai1216';

    try {
        $dbh = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        /*     echo '接続成功'; */
    } catch (PDOException $err) {
        echo '接続失敗' . $err->getMessage();
        exit();
    };

    return $dbh;
}
//2.データを取得する
function getallBlog()
{
    $dbh = dbConnect();
    //1.SQLの実行
    $sql = 'SELECT * FROM blog';

    //2.SQLの実行
    $statement = $dbh->query($sql);

    //3.SQLの結果を受け取る
    return $statement;
    $dbh = null;
}
//取得したデータを表示
$blogData = getallBlog();



function hsc($protect)
{
    return htmlspecialchars($protect, ENT_QUOTES, "UTF-8");
}

?>

<!-- 
※function (関数名idData or titleData or contentData)($関数){
  if ($関数 === もし関数の値が空じゃなければの処理){
        return 値を表示する処理；
    }
}
 -->


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>掲示板</title>
</head>

<body>
    <h1 class='title'>Laravel News</h1>

    <section class="main">
        <h2 class="subTitle">さぁ、最新のニュースをシェアしましょう</h2>

        <form action="index.php" method="POST">
            <div class='titleContainer'>
                <p class='nameFlex'>title: </p>
                <input type='text' name='title' class="inputFlex">
            </div>
            <div class='articleContainer'>
                <p class='nameFlex'>記事: </p>
                <textarea rows="10" cols="60" name="content" class="inputFlex articleInput"></textarea>
            </div>
            <div class="submitContainer">
                <input type="submit" value="投稿" class="submitStyle">
            </div>
        </form>

        <hr>

        <div class='Container'>
            <?php foreach ($blogData as $column) : ?>
                <div class="content">
                    <p class="articleTitle">
                        <?php echo hsc($column['title']) ?>
                    </p>
                    <p class="articleText">
                        <?php echo hsc($column['content']) ?>
                    </p>
                    <p class='routingStyle'><a href="detail.php?id=<?php echo $column['id'] ?>">記事全文・コメントを見る</a></p>
                </div>
            <?php endforeach; ?>
        </div>

</body>

</html>