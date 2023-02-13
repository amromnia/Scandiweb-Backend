<?php
    if(basename($_SERVER['PHP_SELF']) == basename(__FILE__)){
        http_response_code(403);
        echo json_encode(array("message" => "Forbidden."));
        return;
    }
    class Database{
        protected $conn = null;
        protected $servername = "localhost";
        protected $username = "root";
        protected $password = "";
        protected $dbname = "scandiweb";

        function __construct(){
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
        }

        function __destruct(){
            $this->conn->close();
        }


        function getConnection(){
            if(!$this->conn || $this->conn->connect_error){
                $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            }
            return $this->conn;
        }

        function discardConnection(){
            $this->conn->close();
            $this->conn = null;
        }
    }


?>