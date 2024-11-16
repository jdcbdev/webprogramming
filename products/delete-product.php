<?php
    require_once('../tools/functions.php');
    require_once('../classes/product-image.class.php');

    $productObj = new ProductImage();

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['id'];
        $productObj->deleteImage($id);
        $productObj->delete($id);
    }

    echo json_encode(['status' => 'success']);
?>