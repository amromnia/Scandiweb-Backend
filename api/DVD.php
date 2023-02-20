<?php
    require_once("Product.php");
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
?>