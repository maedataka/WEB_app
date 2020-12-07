<?php

require_once __DIR__ . '/lib/mysqli.php';

function createCompany($link, $company)
{
    $sql1 = <<<EOT
    CREATE TABLE IF NOT EXISTS companies (
        id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        establishment_date DATE,
        founder VARCHAR(255),
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) DEFAULT CHARACTER SET=utf8mb4;
    EOT;

    $sql2 = <<<EOT
    INSERT INTO companies (
        name,
        establishment_date,
        founder
    ) VALUES (
        "{$company['name']}",
        "{$company['establishment_date']}",
        "{$company['founder']}"
    )
    EOT;
    $result1 = mysqli_query($link, $sql1);
    $result2 = mysqli_query($link, $sql2);
    if (!$result1) {
        error_log('Error: fail to create company');
        error_log('Debugging Error:' . mysqli_error($link));
    };
    if (!$result2) {
        error_log('Error: fail to create company');
        error_log('Debugging Error:' . mysqli_error($link));
    };
}

function validate($company)
{
    $errors = [];
    //会社名
    if (!strlen($company['name'])) {
        $errors['name'] = '会社名を入力してください';
    } elseif (strlen($company['name']) > 255) {
        $errors['name'] = '会社名は255文字以内で入力してください';
    };
    //設立日
    $dates = explode('-', $company['establishment_date']);
    if (!strlen($company['establishment_date'])) {
        $errors['establishment_date'] = '設立日を入力してください';
    } elseif (!checkdate($dates[1], $dates[2], $dates[0])) {
        $errors['establishment_date'] = '正しい日付で入力してください';
    };

    //代表者
    if (!strlen($company['founder'])) {
        $errors['founder'] = '代表者名を入力してください';
    } elseif (strlen($company['founder']) > 255) {
        $errors['founder'] = '代表者名は255文字以内で入力してください';
    };


    return $errors;
}

//HTTPメッソドがPOSTだったら
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company = [
        'name' => $_POST['name'],
        'establishment_date' => $_POST['establishment_date'],
        'founder' => $_POST['founder']
    ];
    //バリデーション
    $errors = validate($company);
    if (!count($errors)) {
        // データベースに接続する
        $link = dbConnect();
        // データベースに登録する
        createCompany($link, $company);
        // データベースの接続を切る
        mysqli_close($link);
        header("Location: index.php");
    };
}

$content = __DIR__ . '/views/new.php';
include 'views/layout.php';
