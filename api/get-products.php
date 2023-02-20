<?php
    require_once 'REST.php';
    $rest_handler = new REST();
    @$rest_handler->get_products();
?>