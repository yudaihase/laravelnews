<?php
//送信ボタンが押されたら
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $blogs = $_POST;

    $sql = 'INSERT INTO
         blog(title, content)
         VALUES
         (:title, :content)';

    $dbh = dbConnect();

    try {
        $statement = $dbh->prepare($sql);
        $statement->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
        $statement->bindValue(':content', $_POST['content'], PDO::PARAM_STR);
        $statement->execute();
    } catch (PDOException $err) {
        exit($err);
    }
}
