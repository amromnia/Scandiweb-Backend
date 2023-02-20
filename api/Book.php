<?php
    require_once("Product.php");
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
?>