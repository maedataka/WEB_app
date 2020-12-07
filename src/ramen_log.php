<?php

function dbConnect()
{
    $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
    if (!$link) {
        echo 'Error：データベースに接続できませんでした';
        echo 'Debugging error：' . mysqli_connect_error() . PHP_EOL;
    }
    echo 'データベースに接続できました' . PHP_EOL;
    return $link;
}

$link = dbConnect();

function validate($review)
{
    $errors = [];
    if (!strlen($review['shop'])) {
        $errors['shop'] = 'Error：店名を入力してください';
    } elseif (strlen($review['shop']) > 255) {
        $errors['shop'] = 'Error：店名は255文字以内で入力してください';
    }

    if (!strlen($review['menu'])) {
        $errors['menu'] = 'Error：注文メニューを入力してください';
    } elseif (strlen($review['menu']) > 255) {
        $errors['menu'] = 'Error：注文メニューは255文字以内で入力してください';
    }

    if (!strlen($review['picture'])) {
        $errors['picture'] = 'Error：写真を入力してください';
    } elseif (strlen($review['picture']) > 255) {
        $errors['picture'] = 'Error：写真は255文字以内で入力してください';
    }

    if (!strlen($review['evaluation'])) {
        $error['evaluation'] = 'Error：評価を入力してください';
    } elseif (strlen($review['evaluation']) > 255) {
        $errors['evaluation'] = 'Error：評価は255文字以内で入力してください';
    }

    if (!strlen($review['impression'])) {
        $errors['impression'] = 'Error：感想を入力してください';
    } elseif (strlen($review['impression']) > 255) {
        $errors['impression'] = 'Error：感想は255文字以内で入力してください';
    }

    return $errors;
}

function createReview($link)
{
    echo 'ラーメンログを登録してください' . PHP_EOL;

    $review = [];

    echo '店名：';
    $review['shop'] = trim(fgets(STDIN));
    echo '注文メニュー：';
    $review['menu'] = trim(fgets(STDIN));
    echo '写真：';
    $review['picture'] = trim(fgets(STDIN));
    echo '評価：';
    $review['evaluation'] = trim(fgets(STDIN));
    echo '感想：';
    $review['impression'] = trim(fgets(STDIN));

    $validated = validate($review);
    if (count($validated) > 0) {
        foreach ($validated as $error) {
            echo $error . PHP_EOL;
        }
        return;
    }

    $sql = <<<EOT
    INSERT INTO ramen_log (
        shop,
        menu,
        picture,
        evaluation,
        impression
    ) VALUES (
        "{$review['shop']}",
        "{$review['menu']}",
        "{$review['picture']}",
        "{$review['evaluation']}",
        "{$review['impression']}"
    )
    EOT;

    $result = mysqli_query($link, $sql);
    if ($result) {
        echo '登録が終了しました';
    } else {
        echo 'Error：登録に失敗しました' . PHP_EOL;
        echo 'Debugging error：' . mysqli_connect_error() . PHP_EOL;
    }
}

function displayReview($link)
{
    echo '登録されている読書ログを表示します' . PHP_EOL;

    $sql = 'SELECT * FROM ramen_log';
    $result = mysqli_query($link, $sql);
    while ($ramen_log = mysqli_fetch_assoc($result)) {
        echo '------------------------------' . PHP_EOL;
        echo '店名：' . $ramen_log['shop'] . PHP_EOL;
        echo 'メニュー名：' . $ramen_log['menu'] . PHP_EOL;
        echo '写真：' . $ramen_log['picture'] . PHP_EOL;
        echo '評価：' . $ramen_log['evaluation'] . PHP_EOL;
        echo '感想：' . $ramen_log['impression'] . PHP_EOL;
    }

    mysqli_free_result($result);
}


while (true) {
    echo '1.ラーメンログを登録' . PHP_EOL;
    echo '2.ラーメンログを表示' . PHP_EOL;
    echo '9.アプリケーションを終了' . PHP_EOL;
    echo '番号を選択してください(1,2,9)：';
    $num = trim(fgets(STDIN));

    if ($num === '1') {
        createReview($link);
    } elseif ($num === '2') {
        displayReview($link);
        echo '------------------------------' . PHP_EOL;
    } elseif ($num === '9') {
        break; //アプリケーションを終了する
    }
}
