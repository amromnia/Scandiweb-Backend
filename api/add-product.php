<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header('Content-Type: application/json');

    require_once 'product-classes.php';

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

        // create the product
        if($product->createRecord()){
            // set response code - 201 created
            http_response_code(201);
            // tell the user
            echo json_encode(array("message" => "Product was created."));
        }

        // if unable to create the product, tell the user
        else{
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("message" => "Unable to create product."));
        }
?>