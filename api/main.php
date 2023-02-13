<?php
    require_once 'product-classes.php';
    require_once 'db.php';

    //$dvd = new DVD("123", "test", 10, 100);
    //$chair = new Furniture("456", "test", 10, 100, 100, 100);
    //$book = new Book("789", "test", 10, 100);
    //$generic_product = new product("123", "test", 10, array("size" => 100, "weight" => 100, "height" => 100, "width" => 100, "length" => 100, "color" => "red"));

    // $db = new Database();
    // $conn = $db->getConnection();
    // $result = $conn->query("SELECT * FROM products");
    // $products = array();
    // if ($result->num_rows > 0) {
    //     while($row = $result->fetch_assoc()) {
    //         $products[] = $row;
    //     }
    // }
    // print_r($products);

    // $dvd = $product->getChild();
    // var_dump($dvd->getAttributes());
    // echo "<br>";
    // var_dump($product->getAttributes());
    // //get size
    // get weight
    // get height
    $product = new Product("123", "test", 10, array("size" => 100));
    $newproduct = $product->getChild();
    echo json_encode($newproduct->getAttributes());
    //$newproduct->createRecord();
    // if($newproduct){
    //     echo get_class($newproduct);
    //     echo "<br>";
    //     echo "Class Functions: ";
    //     echo "<br>";
    //     var_dump(get_class_methods($newproduct));
    //     echo "<br>";
    //     echo $newproduct->getType();
    // }
    // if($product){
    //     echo get_class($product);
    //     echo "<br>";
    //     echo "Class Functions: ";
    //     echo "<br>";
    //     var_dump(get_class_methods($product));
    //     echo "<br>";
    //     echo $product->getType();
    // }
    $dvd = new DVD("123", "test", 10, array());
    // echo $dvd->getSku();
    // echo $dvd->getName();
    // echo $dvd->getPrice();
    // echo $dvd->getSize();
    // echo "<br>";
    // print_r($dvd->getAttributes());

    // echo "<br>";

    // echo $chair->getSku();
    // echo $chair->getName();
    // echo $chair->getPrice();
    // echo $chair->getHeight();
    // echo $chair->getWidth();
    // echo $chair->getLength();

    // echo "<br>";

    // echo $book->getSku();
    // echo $book->getName();
    // echo $book->getPrice();
    // echo $book->getWeight();
?>