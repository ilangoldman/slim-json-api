<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/dbclass.php';

include_once '../entities/investimento.php';

$dbclass = new DBClass();
$connection = $dbclass->getConnection();

$investimento = new Investimento($connection);

$data = json_decode(file_get_contents("php://input"));

$investimento->name = $data->name;
$investimento->price = $data->price;
$investimento->description = $data->description;
$investimento->category_id = $data->category_id;
$investimento->created = date('Y-m-d H:i:s');

if($investimento->create()){
    echo '{';
        echo '"message": "Investimento was created."';
    echo '}';
}
else{
    echo '{';
        echo '"message": "Unable to create Investimento."';
    echo '}';
}
?>