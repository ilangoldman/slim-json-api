<?php

// header('Content-Type: application/json');
header("Acess-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// $user;
$user->nome = "Ilan";
$user->tipo = "investidor";

echo json_encode($user);