<?php

function validate($review)
{
    $errors = [];

    if (!strlen($review['title'])) {
        $errors['title'] = 'Error：書籍名を入力してください';
    } elseif (strlen($review['title']) > 255) {
        $errors['title'] = 'Error：書籍名は255文以内で入力してください';
    }

    if (!strlen($review['author'])) {
        $errors['author'] = 'Error：著者を入力してください';
    } elseif (strlen($review['author']) > 255) {
        $errors['author'] = 'Error：著者名は255文字以内で入力してください';
    }

    if (!strlen($review['progress'])) {
        $errors['progress'] = 'Error：読書状況を入力してください';
    } elseif (strlen($review['progress']) > 255) {
        $errors['progress'] = 'Error：読書状況は255文字以内で入力してください';
    }

    if ($review['evaluation'] < 1 || $review['evaluation'] > 5) {
        $errors['evaluation'] = 'Error：評価は1~5の整数を入力してください';
    }

    if (!strlen($review['impression'])) {
        $errors['impression'] = 'Error：感想を入力してください';
    } elseif (strlen($review['impression']) > 255) {
        $errors['progress'] = 'Error：感想は255文字以内で入力してください';
    }

    return $errors;
}

function dbConnect()
{
    $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
    if (!$link) {
        echo 'データベースに接続できませんでした' . PHP_EOL;
        echo 'Debugging error：' . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    echo 'データベースに接続しました' . PHP_EOL;
    return $link;
}
$link = dbConnect();

function createReview($link)
{
    $review = [];

    echo '読書ログを登録してください' . PHP_EOL;
    echo '書籍名：';
    $review['title'] = trim(fgets(STDIN));
    echo '著者名：';
    $review['author'] = trim(fgets(STDIN));
    echo '読書状況：';
    $review['progress'] = trim(fgets(STDIN));
    echo '評価：';
    $review['evaluation'] = (int) trim(fgets(STDIN));
    echo '感想：';
    $review['impression']  = trim(fgets(STDIN));

    $validated = validate($review);
    if (count($validated) > 0) {
        foreach ($validated as $error) {
            echo $error . PHP_EOL;
        }
        return; #エラー時は登録されないようにここで処理を止める
    }

    $sql = <<<EOT
    INSERT INTO book_log (
        title,
        author,
        progress,
        evaluation,
        impression
    ) VALUES (
        "{$review['title']}",
        "{$review['author']}",
        "{$review['progress']}",
        "{$review['evaluation']}",
        "{$review['impression']}"
    )
    EOT;

    $result = mysqli_query($link, $sql);
    if ($result) {
        echo '登録が完了しました' . PHP_EOL . PHP_EOL;
    } else {
        echo 'Error：データの登録に失敗しました' . PHP_EOL;
        echo 'Debugging error：' . mysqli_error($link) . PHP_EOL;
    }
}


function displayReview($link)
{
    $sql = 'SELECT * FROM book_log';
    $results = mysqli_query($link, $sql);

    while ($book_log = mysqli_fetch_assoc($results)) {
        echo '------------------------------' . PHP_EOL;
        echo '作品名：' . $book_log['title'] . PHP_EOL;
        echo '著者名：' . $book_log['author'] . PHP_EOL;
        echo '読書状況：' . $book_log['progress'] . PHP_EOL;
        echo '評価：' . $book_log['evaluation'] . PHP_EOL;
        echo '感想：' . $book_log['impression'] . PHP_EOL;
    }

    mysqli_free_result($results);
    // echo '登録されている読書ログを表示します' . PHP_EOL;
    // echo '-------------------' . PHP_EOL;
    // foreach ($lists as $list) {
    //     echo '書籍名：' . $list['title'] . PHP_EOL;
    //     echo '著者名：' . $list['author'] . PHP_EOL;
    //     echo '読書状況：' . $list['progress'] . PHP_EOL;
    //     echo '評価：' . $list['evaluation'] . PHP_EOL;
    //     echo '感想：' . $list['impression'] . PHP_EOL;
    //     echo '-------------------' . PHP_EOL;
}

while (true) {
    echo '------------------------------' . PHP_EOL;
    echo '1.読書ログを登録' . PHP_EOL;
    echo '2.読書ログを表示' . PHP_EOL;
    echo '9.アプリケーションを終了' . PHP_EOL;
    echo '番号を選択してください(1,2,9)：';
    $num = trim(fgets(STDIN));

    if ($num === '1') {
        createReview($link);
    } elseif ($num === '2') {
        displayReview($link);
    } elseif ($num === '9') {
        mysqli_close($link);
        echo 'データベースとの接続を切断しました' . PHP_EOL;
        break; //アプリケーションを終了する
    }
}
