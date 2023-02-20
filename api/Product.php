<?php
    require_once('db.php');
    require_once('DVD.php');
    require_once('Book.php');
    require_once('Furniture.php');

    if(basename($_SERVER['PHP_SELF']) == basename(__FILE__)){
        http_response_code(403);
        echo json_encode(array("message" => "Forbidden."));
        return;
    }
    class Product{
        protected $sku;
        protected $name;
        protected $price;
        protected $attributes = array();


        //constructor
        function __construct($sku, $name, $price, $attributes){
            $this->sku = $sku;
            $this->name = $name;
            $this->price = $price;
            $this->attributes = $attributes;
            foreach($attributes as $key => $value){
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
        //getters and setters
        function getSku(){
            return $this->sku;
        }

        function setSku($sku){
            $this->sku = $sku;
        }

        function getName(){
            return $this->name;
        }
        function setName($name){
            $this->name = $name;
        }

        function getPrice(){
            return $this->price;
        }

        function setPrice($price){
            $this->price = $price;
        }

        function getAttributes(){
            return $this->attributes;
        }

        protected function setAttributes($attributes){
            $this->attributes = $attributes;
            foreach($attributes as $key => $value){
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
        function getChild(){
            $emptyArr = array();
            $count = 0;
            $listOfAttributes = array("DVD" => array("size"), "Book" => array("weight"), "Furniture" => array("height", "width", "length"));
            foreach($listOfAttributes as $key => $value){
                // if(array_keys($this->attributes) == $value){
                //     return new $key($this->sku, $this->name, $this->price, $this->attributes);
                // }
                if(array_intersect(array_keys($this->attributes), $value) != $emptyArr){
                    $correctKey = $key;
                    $count++;
                }
            }
            if($count == 1){
                return new $correctKey($this->sku, $this->name, $this->price, $this->attributes);
            }
            else{
                return false;
            }
        }
        function getType(){
            $child = $this->getChild();
            if($child){
                return get_class($child);
            }
            else{
                return "Product";
            }
        }

        function createRecord(){
            $db = new Database();
            $conn = $db->getConnection();
            $query = "INSERT INTO products (sku, name, price, attributes) VALUES (?, ?, ?, ?)";
            $sku = $this->sku;
            $name = $this->name;
            $price = $this->price;
            $attributes = serialize($this->attributes);
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssds", $sku, $name, $price, $attributes);
            try{
                if($stmt->execute()){
                    if($stmt->affected_rows < 0){
                        return -5;
                    }
                    return 0;
                }
                if($stmt->errno == 1062){
                    return -5;
                }
                return -1;
            }
            catch(Exception $e){
                if($stmt->errno == 1062 || $stmt->affected_rows < 0){
                    return -5;
                }
                return -2;
            }

        }
        public static function deleteRecord($sku){
            $db = new Database();
            $conn = $db->getConnection();
            $query = "DELETE FROM products WHERE sku = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $sku);

            if($stmt->execute()){
                return true;
            }
            return false;
        }

        function get_json_format(){
            $json = array();
            $json["sku"] = $this->sku;
            $json["name"] = $this->name;
            $json["price"] = $this->price;
            $json["attributes"] = $this->attributes;
            $json["type"] = $this->getType();
            return $json;
        }
    }
?>