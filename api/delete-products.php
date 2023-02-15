<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: DELETE, OPTIONS');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require_once 'product-classes.php';

    if($_SERVER['REQUEST_METHOD'] != 'DELETE' && $_SERVER['REQUEST_METHOD'] != 'OPTIONS'){
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

    if(!$data->products || empty($data->products)){
        http_response_code(400);
        echo json_encode(array("message" => "Unable to delete product. Data is incomplete."));
        return;
    }
    $products = $data->products;
    $failed = false;
    $deleted_products = array();

    foreach($products as $product){
        $sku = $product;
        $result = Product::deleteRecord($sku);
        if(!$result){
            $failed = true;
        }
        else{
            array_push($deleted_products, $sku);
        }
    }

    if(!$failed){
        http_response_code(201);
        echo json_encode(array("message" => "Products were deleted.", "deleted_products" => $deleted_products));
        return;
    }

    http_response_code(503);
    echo json_encode(array("message" => "One or more products could not be deleted."));
?>