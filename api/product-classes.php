<?php
    require_once('db.php');

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

            if($stmt->execute()){
                return true;
            }
            return false;
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


    class DVD extends Product{
        protected $size;

        function __construct($sku, $name, $price, $attributes){
            //call parent constructor
            if(!array_key_exists("size", $attributes)){
                $attributes["size"] = 0;
            }
            $size = $attributes["size"];
            parent::__construct($sku, $name, $price, array("size" => $size));
        }

        function getSize(){
            return $this->size;
        }
        function setSize($size){
            parent::setAttributes(array("size" => $size));
        }

        function get_json_format(){
            $json = array();
            $json["sku"] = $this->sku;
            $json["name"] = $this->name;
            $json["price"] = $this->price;
            $json["size"] = $this->size;
            $json["type"] = "DVD";
            return $json;
        }
    }

    class Book extends Product{
        protected $weight;

        //constructor
        function __construct($sku, $name, $price, $attributes){
            if(!array_key_exists("weight", $attributes)){
                $attributes["weight"] = 0;
            }
            $weight = $attributes["weight"];
            parent::__construct($sku, $name, $price, array("weight" => $weight));
        }

        function getWeight(){
            return $this->weight;
        }

        function setWeight($weight){
            parent::setAttributes(array("weight" => $weight));
        }

        function get_json_format(){
            $json = array();
            $json["sku"] = $this->sku;
            $json["name"] = $this->name;
            $json["price"] = $this->price;
            $json["weight"] = $this->weight;
            $json["type"] = "Book";
            return $json;
        }
    }

    class Furniture extends Product{
        protected $height;
        protected $width;
        protected $length;

        //constructor
        function __construct($sku, $name, $price, $attributes){
            if(!array_key_exists("height", $attributes)){
                $attributes["height"] = 0;
            }
            if(!array_key_exists("width", $attributes)){
                $attributes["width"] = 0;
            }
            if(!array_key_exists("length", $attributes)){
                $attributes["length"] = 0;
            }
            $height = $attributes["height"];
            $width = $attributes["width"];
            $length = $attributes["length"];
            parent::__construct($sku, $name, $price, array("height" => $height, "width" => $width, "length" => $length));
        }

        function getHeight(){
            return $this->height;
        }

        function setHeight($height){
            parent::setAttributes(array("height" => $height));
        }

        function getWidth(){
            return $this->width;
        }

        function setWidth($width){
            parent::setAttributes(array("width" => $width));
        }

        function getLength(){
            return $this->length;
        }

        function setLength($length){
            parent::setAttributes(array("length" => $length));
        }

        function getDimensions(){
            return $this->height . "x" . $this->width . "x" . $this->length;
        }

        function setDimensions($height, $width, $length){
            parent::setAttributes(array("height" => $height, "width" => $width, "length" => $length));
        }

        function get_json_format(){
            $json = array();
            $json["sku"] = $this->sku;
            $json["name"] = $this->name;
            $json["price"] = $this->price;
            $json["height"] = $this->height;
            $json["width"] = $this->width;
            $json["length"] = $this->length;
            $json["type"] = "Furniture";
            return $json;
        }
    }
?>