<?php
    require_once 'Product.php';
    require_once 'db.php';

    class REST{
        protected $db;

        function __construct(){
            $this->db = new Database();
        }

        function handle_headers($method){
            header('Access-Control-Allow-Origin: *');
            //allow POST to delete for hosts that don't support DELETE (e.g. 000webhost)
            $method_string = $method;
            $alternate_method = '';
            if($method == 'DELETE'){
                $method_string .= ', POST';
                $alternate_method = 'POST';
            }
            //allow OPTIONS for preflight
            $method_string.= ', OPTIONS';
            header('Access-Control-Allow-Methods: ' . $method_string);
            header('Content-Type: application/json');
            header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

            if($_SERVER['REQUEST_METHOD'] != $method && $_SERVER['REQUEST_METHOD'] != 'OPTIONS' && $_SERVER['REQUEST_METHOD'] != $alternate_method){
                http_response_code(405);
                echo json_encode(array("message" => "Method not allowed."));
                return false;
            }
            http_response_code(200);
            return true;
        }

        function get_products(){
            if(!$this->handle_headers('GET') || $_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
                return;
            }
            $conn = $this->db->getConnection();

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
            echo json_encode($products);
        }

        function add_product(){
            if(!$this->handle_headers('POST') || $_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
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
                return http_response_code(200);
        }


        function delete_products(){
            if(!$this->handle_headers('DELETE') || $_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
                return;
            }
            $data = json_decode(file_get_contents("php://input"));

            try {
                if(!$data->products || empty($data->products) || $data == null ){
                    http_response_code(400);
                    echo json_encode(array("message" => "Unable to delete product. Data is incomplete."));
                    return;
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(array("message" => "Unable to delete product. Data is incomplete. 2"));
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
                http_response_code(200);
                echo json_encode(array("message" => "Products were deleted.", "deleted_products" => $deleted_products));
                return;
            }
            http_response_code(503);
            echo json_encode(array("message" => "One or more products could not be deleted."));
        }
    }
?>
