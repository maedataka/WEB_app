<?php


require_once __DIR__ . '/lib/mysqli.php';
require_once __DIR__ . '/lib/escape.php';

function listBooks($link)
{
    $books = [];
    $sql = 'SELECT title, author, progress, evaluation, impression FROM book_log';
    $results = mysqli_query($link, $sql);

    while ($book = mysqli_fetch_assoc($results)) {
        $books[] = $book;
    }
    mysqli_free_result($results);

    return $books;
}

$link = dbConnect();
$books = listBooks($link);

$title = '読書ログ';
$content = __DIR__ . '/views/index.php';

include 'views/layout.php';
