<?php

const ATTRIBUTES_TYPE = [
    'user_id' => PDO::PARAM_INT,
    'email' => PDO::PARAM_STR,
    'password' => PDO::PARAM_STR,
    'roles' => PDO::PARAM_STR,
    'blogpost_id' => PDO::PARAM_INT,
    'title' => PDO::PARAM_STR,
    'summary' => PDO::PARAM_STR,
    'content' => PDO::PARAM_STR,
    'author' => PDO::PARAM_INT,
    'creation_date' => PDO::PARAM_STR,
    'modification_date' => PDO::PARAM_STR,
    'comment_id' => PDO::PARAM_INT,
    'text' => PDO::PARAM_STR
];
