<?php

$errors = [];
$books = [
    'title' => '',
    'author' => '',
    'progress' => '',
    'evaluation' => '',
    'impression' => ''
];
$title = '読書ログ';
$content = __DIR__ . '/views/new.php';

include 'views/layout.php';
