<?php
    require_once("Product.php");
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