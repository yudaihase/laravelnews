<?php

$id = $_GET['id'];
$dbh = dbConnect();

require_once('comment.php');
/* require_once('detele_comment.php'); */

//idが入っていない場合
/* if(empty($id)) {
   exit('IDが不正です')；
} */

///コメントの削除
/* $pdo->beginTransaction(); */

if (isset($_POST['delete'])) {
    try {

        // SQL作成
        $statement = $dbh->prepare("DELETE FROM comments WHERE comment_id = :id");

        // 値をセット
        $statement->bindValue(':id', $_POST['delete'], PDO::PARAM_INT);

        // SQLクエリの実行
        $statement->execute();

        // コミット
        /*         $res = $pdo->commit(); */
    } catch (Exception $err) {

        // エラーが発生した時はロールバック
        /*         $pdo->rollBack(); */
    }
}

function dbConnect()
{
    $dsn = 'mysql:host=localhost;dbname=blog;charset=utf8';
    $user = 'bloguser';
    $pass = 'yudai1216';

    try {
        $dbh = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //SQLインジェクションのオプション
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        /*     echo '接続成功'; */
    } catch (PDOException $err) {
        echo '接続失敗' . $err->getMessage();
        exit();
    };

    return $dbh;
}



//SQL準備+SQLインジェクションを防ぐ
//プレースホルダーとは：後でSQL文に値を入れる
$statement = $dbh->prepare('SELECT * FROM blog Where id = :id');
$statement->bindvalue(':id', (int)$id, PDO::PARAM_INT);

//SQLを実行
$statement->execute();

//結果を取得
$result = $statement->fetch(PDO::FETCH_ASSOC);

//違うidが入っていた場合
/* if(!$result) {
  exit('投稿がありません')；
} */

function getComment()
{
    $id = $_GET['id'];
    $dbh = dbConnect();
    //1.SQLの実行
    $statement = $dbh->prepare('SELECT * FROM comments Where content_id = :id');
    $statement->bindvalue(':id', (int)$id, PDO::PARAM_INT);

    //2.SQLの実行
    $statement->execute();

    //結果を取得
    $result = $statement->fetchall(PDO::FETCH_ASSOC);

    //3.SQLの結果を受け取る
    return $result;
    $dbh = null;
}
//取得したデータを表示
$commentData = getComment();


function hsc($protect)
{
    return htmlspecialchars($protect, ENT_QUOTES, "UTF-8");
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>詳細</title>
</head>

<body>
    <h1 class='title link'><a href='dbc.php'>Laravel News</a></h1>

    <h2>詳細</h2>
    <hr>
    <h3>タイトル:<?php echo $result['title'] ?></h3>
    <p>記事:<?php echo $result['content'] ?></p>
    <hr>

    <section class="main">
        <!-- エラーメッセージ -->
        <ul>
            <?php foreach ($error_message as $error) : ?>
                <li>
                    <?php echo $error ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class='commentContainer'>

            <!-- コメント表示部分 -->
            <form method="post" class="commentForm">
                <input type="hidden" name="content_id" value="<?php echo $id; ?>">
                <textarea name="comment" class="inputFlex commentInput"></textarea>
                <input type="submit" value="コメントを書く" name='<?php echo $id; ?>' class="commnetSubmitStyle">
            </form>
            <?php foreach ($commentData as $column) : ?>
                <div class="comment">
                    <p class="articlecomment">
                        <?php echo hsc($column['comment']) ?>
                    </p>
                    <form action="" method="post">
                        <input type="hidden" name="delete" value="<?php echo $column['comment_id']; ?>">
                        <input type="submit" value="コメントを消す" class="deleteComment">
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>

</html>