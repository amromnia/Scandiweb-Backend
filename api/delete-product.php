<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: DELETE');
    header('Content-Type: application/json');

    require_once 'product-classes.php';

    if($_SERVER['REQUEST_METHOD'] != 'DELETE'){
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        return;
    }

    $data = json_decode(file_get_contents("php://input"));

    if(!$data->sku || empty($data->sku)){
        http_response_code(400);
        echo json_encode(array("message" => "Unable to delete product. Data is incomplete."));
        return;
    }
    $sku = $data->sku;

    $result = Product::deleteRecord($sku);


    if($result){
        http_response_code(201);
        echo json_encode(array("message" => "Product was deleted."));
    }

    http_response_code(503);
    echo json_encode(array("message" => "Unable to delete product."));
?>