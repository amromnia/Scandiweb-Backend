<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require_once 'product-classes.php';

    // check if method is POST
    if($_SERVER['REQUEST_METHOD'] != 'POST' && $_SERVER['REQUEST_METHOD'] != 'OPTIONS'){
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        return;
    }

    // allow preflight
    if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
        http_response_code(200);
        return;
    }

    $data = json_decode(file_get_contents("php://input"));
    if(
        empty($data->sku) ||
        empty($data->name) ||
        empty($data->price) ||
        empty($data->attributes)
    ){
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
        return;
    }
        $sku = $data->sku;
        $name = $data->name;
        $price = $data->price;
        $attributes = json_decode(json_encode($data->attributes), true);

        $product = new product($sku, $name, $price, $attributes);
        $product = $product->getChild();
        $result = $product->createRecord();

        if($result == 0){
            http_response_code(201);
            echo json_encode(array("message" => "Product was created.", "ErrorCode"=>0));
        }
        else if($result == -5){
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create product. Product with this SKU already exists.", "ErrorCode"=>-5));
        }
        else{
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create product.", "ErrorCode"=>-1));
        }
?>