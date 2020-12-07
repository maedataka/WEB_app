<?php

require_once __DIR__ . '/lib/mysqli.php';

function dropTable($link)
{
    $dropTableSql = 'DROP TABLE IF EXISTS companies;';
    $result = mysqli_query($link, $dropTableSql);
    if ($result) {
        echo 'テーブルを削除しました' . PHP_EOL . PHP_EOL;
    } else {
        echo 'Error：テーブルの削除に失敗しました' . PHP_EOL;
        echo 'Debugging error：' . mysqli_error($link) . PHP_EOL;
        PHP_EOL;
    }
}

function createTable($link)
{
    $createTableSql = <<<EOT
    CREATE TABLE companies (
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    establishment_date DATE,
    founder VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) DEFAULT CHARACTER SET=utf8mb4;
    EOT;

    $result = mysqli_query($link, $createTableSql);
    if ($result) {
        echo 'テーブルを作成しました' . PHP_EOL . PHP_EOL;
    } else {
        echo 'Error：テーブルの作成に失敗しました' . PHP_EOL;
        echo 'Debugging error：' . mysqli_error($link) . PHP_EOL;
    }
}
// function createReview($link)
// {
//     $review = [];

//     echo '読書ログを登録してください' . PHP_EOL;
//     echo '書籍名：';
//     $review['title'] = trim(fgets(STDIN));
//     echo '著者名：';
//     $review['author'] = trim(fgets(STDIN));
//     echo '読書状況：';
//     $review['progress'] = trim(fgets(STDIN));
//     echo '評価：';
//     $review['evaluation'] = (int) trim(fgets(STDIN));
//     echo '感想：';
//     $review['impression']  = trim(fgets(STDIN));

//     $validated = validate($review);
//     if (count($validated) > 0) {
//         foreach ($validated as $error) {
//             echo $error . PHP_EOL;
//         }
//         return; #エラー時は登録されないようにここで処理を止める
//     }

//     $sql = <<<EOT
//     INSERT INTO book_log (
//         title,
//         author,
//         progress,
//         evaluation,
//         impression
//     ) VALUES (
//         "{$review['title']}",
//         "{$review['author']}",
//         "{$review['progress']}",
//         "{$review['evaluation']}",
//         "{$review['impression']}"
//     )
//     EOT;

//     $result = mysqli_query($link, $sql);
//     if ($result) {
//         echo '登録が完了しました' . PHP_EOL . PHP_EOL;
//     } else {
//         echo 'Error：データの登録に失敗しました' . PHP_EOL;
//         echo 'Debugging error：' . mysqli_error($link) . PHP_EOL;
//     }
// }


// function displayReview($link)
// {
//     $sql = 'SELECT * FROM book_log';
//     $results = mysqli_query($link, $sql);

//     while ($book_log = mysqli_fetch_assoc($results)) {
//         echo '------------------------------' . PHP_EOL;
//         echo '作品名：' . $book_log['title'] . PHP_EOL;
//         echo '著者名：' . $book_log['author'] . PHP_EOL;
//         echo '読書状況：' . $book_log['progress'] . PHP_EOL;
//         echo '評価：' . $book_log['evaluation'] . PHP_EOL;
//         echo '感想：' . $book_log['impression'] . PHP_EOL;
//     }
//     mysqli_free_result($results);
// }

$link = dbConnect();
dropTable($link);
createTable($link);
mysqli_close($link);
