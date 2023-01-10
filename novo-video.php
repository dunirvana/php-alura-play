<?php

$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");

$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
$titulo = filter_input(INPUT_POST, 'titulo');
if ($url === false || $titulo === false) {
  header('Location: /?sucesso=0');
  exit();
}


$repository = new \Alura\Mvc\Repository\VideoRepository($pdo);
$result = $repository->add(new \Alura\Mvc\Entity\Video($url, $titulo));

if ($result === false) {
    header('Location: /?sucesso=0');
} else {
    header('Location: /?sucesso=1');
}