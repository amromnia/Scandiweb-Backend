<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header('Content-Type: application/json');

    require_once 'product-classes.php';

    $data = json_decode(file_get_contents("php://input"));

    $sku = $data->sku;

    $result = Product::deleteRecord($sku);


    if($result){
        http_response_code(201);
        echo json_encode(array("message" => "Product was deleted."));
    }

    http_response_code(503);
    echo json_encode(array("message" => "Unable to delete product."));
?>