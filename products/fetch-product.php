<?php
    require_once('../classes/product.class.php');

    $productObj = new Product();

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $productObj->fetchRecord($id);
    } else {
        $record = null;
    }

    header('Content-Type: application/json');
    echo json_encode($record);
?> 