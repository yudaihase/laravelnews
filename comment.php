<?php
//POSTがコメントで送信されてきたら

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comment'])) {

        $blogs = $_POST;

        $sql = 'INSERT INTO
         comments(content_id,comment)
         VALUES
         (:content_id, :comment)';

        $dbh = dbConnect();

        try {
            $statement = $dbh->prepare($sql);
            $statement->bindValue(':comment', $_POST['comment'], PDO::PARAM_STR);
            $statement->bindValue(':content_id', $_POST['content_id'], PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $err) {
            exit($err);
        }
    }
}
