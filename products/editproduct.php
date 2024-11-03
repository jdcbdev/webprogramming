<?php

session_start();

if(isset($_SESSION['account'])){
    if(!$_SESSION['account']['is_staff']){
        header('location: login.php');
    }
}else{
    header('location: login.php');
}

// Include the necessary files for utility functions and the Product class.
require_once('../tools/functions.php');
require_once('../classes/product.class.php');

// Initialize variables to hold form input values and error messages.
$code = $name = $category = $price = '';
$codeErr = $nameErr = $categoryErr = $priceErr = '';
$productObj = new Product(); // Initialize the Product object

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Handle GET request to fetch and display the product details for editing.
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $productObj->fetchRecord($id); // Fetch product details by ID
        if (!empty($record)) {
            // Populate form fields with existing product details for editing.
            $code = $record['code'];
            $name = $record['name'];
            $category = $record['category_id'];
            $price = $record['price'];
        } else {
            echo 'No product found';
            exit;
        }
    } else {
        echo 'No product found';
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle POST request to update the product details.

    // Clean and assign the input values to variables using the clean_input function.
    $id = clean_input($_GET['id']);
    $code = clean_input($_POST['code']);
    $name = clean_input($_POST['name']);
    $category = clean_input($_POST['category']);
    $price = clean_input($_POST['price']);

    // Validate the 'code' field.
    if (empty($code)) {
        $codeErr = 'Product Code is required';
    } else if ($productObj->codeExists($code, $id)) { // Ensure the code is unique for other products.
        $codeErr = 'Product Code already exists';
    }

    // Validate the 'name' field.
    if (empty($name)) {
        $nameErr = 'Name is required';
    }

    // Validate the 'category' field.
    if (empty($category)) {
        $categoryErr = 'Category is required';
    }

    // Validate the 'price' field.
    if (empty($price)) {
        $priceErr = 'Price is required';
    } elseif (!is_numeric($price)) {
        $priceErr = 'Price should be a number';
    } elseif ($price < 1) {
        $priceErr = 'Price must be greater than 0';
    }

    // If there are no validation errors, proceed to update the product in the database.
    if (empty($codeErr) && empty($nameErr) && empty($priceErr) && empty($categoryErr)) {
        // Set the product properties.
        $productObj->id = $id;
        $productObj->code = $code;
        $productObj->name = $name;
        $productObj->category_id = $category;
        $productObj->price = $price;

        // Try to update the product in the database.
        if ($productObj->edit()) {
            // If successful, redirect to the product list page.
            header('Location: product.php');
        } else {
            // If there's an issue, display an error message.
            echo 'Something went wrong when updating the product';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <!-- Form to collect product details for editing -->
    <form action="?id=<?= $id ?>" method="post"> <!-- Pass the id in the form action -->

        <!-- Display a note indicating required fields -->
        <span class="error">* are required fields</span>
        <br>

        <!-- Code field with validation error display -->
        <label for="code">Code</label><span class="error">*</span>
        <br>
        <input type="text" name="code" id="code" value="<?= $code ?>"> <!-- Retain entered values -->
        <br>
        <?php if (!empty($codeErr)): ?>
            <span class="error"><?= $codeErr ?></span><br>
        <?php endif; ?>

        <!-- Name field with validation error display -->
        <label for="name">Name</label><span class="error">*</span>
        <br>
        <input type="text" name="name" id="name" value="<?= $name ?>"> <!-- Retain entered values -->
        <br>
        <?php if (!empty($nameErr)): ?>
            <span class="error"><?= $nameErr ?></span><br>
        <?php endif; ?>

        <!-- Category dropdown with validation error display -->
        <label for="category">Category</label><span class="error">*</span>
        <br>
        <select name="category" id="category">
            <option value="">--Select--</option>
            <?php
                $categoryList = $productObj->fetchCategory();
                foreach ($categoryList as $cat){
            ?>
                <option value="<?= $cat['id'] ?>" <?= ($category == $cat['id']) ? 'selected' : '' ?>><?= $cat['name'] ?></option>
            <?php
                }
            ?>
        </select>
        <br>
        <?php if (!empty($categoryErr)): ?>
            <span class="error"><?= $categoryErr ?></span><br>
        <?php endif; ?>

        <!-- Price field with validation error display -->
        <label for="price">Price</label><span class="error">*</span>
        <br>
        <input type="number" name="price" id="price" value="<?= $price ?>"> <!-- Retain entered values -->
        <br>
        <?php if (!empty($priceErr)): ?>
            <span class="error"><?= $priceErr ?></span>
            <br>
        <?php endif; ?>

        <!-- Submit button -->
        <br>
        <input type="submit" value="Update Product">
    </form>
</body>
</html>
