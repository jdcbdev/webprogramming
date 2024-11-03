<?php

require_once('../tools/functions.php');
require_once('../classes/product-image.php');

$code = $name = $category = $price = $image = $imageTemp = '';
$codeErr = $nameErr = $categoryErr = $priceErr = $imageErr = '';

$uploadDir = '../uploads/';
$allowedType = ['jpg', 'jpeg', 'png'];
$maxFileSize = 5000000;

$productObj = new ProductImage();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $code = clean_input($_POST['code']);
    $name = clean_input($_POST['name']);
    $category = clean_input($_POST['category']);
    $price = clean_input($_POST['price']);
    $image = $_FILES['product_image']['name'];
    $imageTemp = $_FILES['product_image']['tmp_name'];

    if(empty($code)){
        $codeErr = 'Product Code is required.';
    } else if ($productObj->codeExists($code)){
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
    if(empty($image)){
        $imageErr = 'Product image is required.';
    }else if(!in_array($imageFileType, $allowedType)){
        $imageErr = 'Accepted files are jpg, jpeg, and png only.';
    } else if (filesize($imageTemp) > $maxFileSize){
        $imageErr = 'File size must not exceed 5MB.';
    }

    // If there are validation errors, return them as JSON
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
        $productObj->code = $code;
        $productObj->name = $name;
        $productObj->category_id = $category;
        $productObj->price = $price;

        if($productObj->add()){

            $targetImage = $uploadDir . uniqid() . basename($image);
            move_uploaded_file($imageTemp, $targetImage);

            $productObj->file_path = $targetImage;
            $productObj->image_role = 'main';
            $productObj->addImage();
            
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new product.']);
        }
        exit;
    }
}
?>