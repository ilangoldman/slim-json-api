<?php

header("Content-Type: application/json; charset=UTF-8");

include_once '../config/dbclass.php';
include_once '../entities/investimento.php';

$dbclass = new DBClass();
$connection = $dbclass->getConnection();

$investimento = new Investimento($connection);

$stmt = $investimento->read();
$count = $stmt->rowCount();

if($count > 0){


    $investimentos = array();
    $investimentos["body"] = array();
    $investimentos["count"] = $count;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);

        $i  = array(
              "id" => $id,
              "name" => $name,
              "createdAt" => $createdAt,
              "updatedAt" => $updatedAt
        );

        array_push($investimentos["body"], $i);
    }

    echo json_encode($investimentos);
}

else {

    echo json_encode(
        array("body" => array(), "count" => 0)
    );
}
?>