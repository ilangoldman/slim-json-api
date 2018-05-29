<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Length');
header('Access-Control-Expose-Headers: Content-Length');
header('Timing-Allow-Origin: *');

/* anything.. really... */
header('Content-Type: text/json;');
header('X-Powered-By: peanut-butter');

header("Content-Type: application/json; charset=UTF-8");
// $user;
$user  = array(
        "nome" => "Ilan",
        "tipo" => "investidor"
);
echo json_encode($user);