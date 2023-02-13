<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Content-Type: application/json');

    require_once 'db.php';
    require_once 'product-classes.php';

    if($_SERVER['REQUEST_METHOD'] != 'GET'){
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        return;
    }

    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT * FROM products";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = array();

    while($row = $result->fetch_assoc()){
        $sku = $row["sku"];
        $name = $row["name"];
        $price = $row["price"];
        $attributes = unserialize($row["attributes"]);
        $product = new Product($sku, $name, $price, $attributes);
        $product = $product->getChild();
        $product_json = $product->get_json_format();
        array_push($products, $product_json);
    }
    //echo all products in a humanely readable format
    echo json_encode($products);
?>