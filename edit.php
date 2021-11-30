<?php

/** @var Connection $connection */
$connection = require_once 'connection.php';

// Validate note object;

$id = $_POST['id'] ?? '';
$connection->updateNote($id, $_POST);
header('Location: biologists-notes.php');
