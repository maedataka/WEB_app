<?php

require_once __DIR__ . '/lib/mysqli.php';

function createBooks($link, $books)
{
    $sql1 = <<<EOT
    CREATE TABLE IF NOT EXISTS book_log (
        ID INTEGER AUTO_INCREMENT NOT NULL PRIMARY KEY,
        title VARCHAR(255),
        author VARCHAR(255),
        progress VARCHAR(10),
        evaluation INTEGER,
        impression VARCHAR(255),
        created_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) DEFAULT CHARACTER SET=utf8mb4
    EOT;

    $sql2 = <<<EOT
INSERT INTO book_log (
    title,
    author,
    progress,
    evaluation,
    impression
) VALUES (
    "{$books['title']}",
    "{$books['author']}",
    "{$books['progress']}",
    "{$books['evaluation']}",
    "{$books['impression']}"
)
EOT;
    $result1 = mysqli_query($link, $sql1);
    $result2 = mysqli_query($link, $sql2);
    if (!$result1) {
        error_log('Error: fail to create book_log');
        error_log('Debugging_Error:' . mysqli_error($link));
    };
    if (!$result2) {
        error_log('Error: fail to create book_log');
        error_log('Debugging_Error:' . mysqli_error($link));
    };
}

function validate($books)
{
    $errors = [];

    if (!strlen($books['title'])) {
        $errors['title'] = '作品名を入力してください';
    } elseif (strlen($books['title']) > 255) {
        $errors['title'] = '作品名は255文字以内で入力してください';
    }

    if (!strlen($books['author'])) {
        $errors['author'] = '著者名を入力してください';
    } elseif (strlen($books['author']) > 255) {
        $errors['author'] = '著者名は255文字以内で入力してください';
    }

    if (!in_array($books['progress'], ['Not yet', 'Going', 'Complete'])) {
        $errors['progress'] = '進捗を入力してください';
    }

    if ($books['evaluation'] > 5 || $books['evaluation'] < 1) {
        $errors['evaluation'] = '評価は1~5で入力してください';
    }

    if (!strlen($books['impression'])) {
        $errors['impression'] = '感想を入力してください';
    } elseif (strlen($books['impression']) > 255) {
        $errors['impression'] = '感想は255文字以内で入力してください';
    }

    return $errors;
}
//HTTPメソッドがPOSTの場合
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $books = [
        'title' => $_POST['title'],
        'author' => $_POST['author'],
        'progress' => $_POST['progress'],
        'evaluation' => $_POST['evaluation'],
        'impression' => $_POST['impression']
    ];
    $errors = validate($books);
    if (!count($errors)) {
        //データベースに接続
        $link = dbConnect();
        //データベースの登録
        createBooks($link, $books);
        //データベースの接続を切る
        mysqli_close($link);
        //リベース
        header("Location: index.php");
    }
}

$title = '読書ログ';
$content = __DIR__ . '/views/new.php';
include "views/layout.php";
