<?php

session_start();

if(isset($_SESSION['account'])){
    if(!$_SESSION['account']['is_staff']){
        header('location: login.php');
    }
}else{
    header('location: login.php');
}

// Initialize an empty $id variable
$id = '';

// Check if 'id' is set in the GET request and assign it to $id if it exists
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

// Include the product class file that contains the Product class definition
require_once '../classes/product.class.php';

// Create an instance of the Product class
$obj = new Product();

// Call the delete method of the Product class with the $id parameter
// If deletion is successful, output 'success'; otherwise, output 'failed'
if ($obj->delete($id)) {
    echo 'success';
} else {
    echo 'failed';
}
