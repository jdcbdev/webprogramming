<?php

require_once('../tools/functions.php');
require_once('../classes/product-image.class.php');

$code = $name = $category = $price = $image = $imageTemp = '';
$codeErr = $nameErr = $categoryErr = $priceErr = $imageErr = '';

$uploadDir = '../uploads/';
$allowedType = ['jpg', 'jpeg', 'png'];
$maxFileSize = 5 * 1024 * 1024;

$productObj = new ProductImage();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['id'];
    $code = clean_input($_POST['code']);
    $name = clean_input($_POST['name']);
    $category = clean_input($_POST['category']);
    $price = clean_input($_POST['price']);
    $image = $_FILES['product_image']['name'];
    $imageTemp = $_FILES['product_image']['tmp_name'];
    $imageSize = $_FILES['product_image']['size'];
    $hasImagePreview = isset($_POST['hasImagePreview']) && $_POST['hasImagePreview'] === 'true';

    if(empty($code)){
        $codeErr = 'Product Code is required.';
    } else if ($productObj->codeExists($code, $id)){
        $codeErr = 'Product Code already exists.';
    }

    if(empty($name)){
        $nameErr = 'Name is required.';
    }

    if(empty($category)){
        $categoryErr = 'Category is required.';
    }

    if(empty($price)){
        $priceErr = 'Price is required.';
    } else if (!is_numeric($price)){
        $priceErr = 'Price should be a number.';
    } else if ($price < 1){
        $priceErr = 'Price must be greater than 0.';
    }

    $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    if (!$hasImagePreview && empty($image)) {
        $imageErr = 'Product image is required.';
    } else if (!empty($image) && !in_array($imageFileType, $allowedType)) {
        $imageErr = 'Accepted files are jpg, jpeg, and png only.';
    } else if (!empty($image) && $imageSize > $maxFileSize) {
        $imageErr = 'File size must not exceed 5MB.';
    }

    if(!empty($codeErr) || !empty($nameErr) || !empty($categoryErr) || !empty($priceErr) || !empty($imageErr)){
        echo json_encode([
            'status' => 'error',
            'codeErr' => $codeErr,
            'nameErr' => $nameErr,
            'categoryErr' => $categoryErr,
            'priceErr' => $priceErr,
            'imageErr' => $imageErr
        ]);
        exit;
    }

    if(empty($codeErr) && empty($nameErr) && empty($categoryErr) && empty($priceErr) && empty($imageErr)){
        $productObj->id = $id;
        $productObj->code = $code;
        $productObj->name = $name;
        $productObj->category_id = $category;
        $productObj->price = $price;

        if($productObj->edit()){

            if (!empty($image)) {

                $productObj->deleteImage($id);

                $targetImage = $uploadDir . uniqid() . basename($image);
                move_uploaded_file($imageTemp, $targetImage);
                $productObj->file_path = $targetImage;
                $productObj->image_role = 'main';
                $productObj->addImage($id);
            }
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new product.']);
        }
        exit;
    }
}
?>