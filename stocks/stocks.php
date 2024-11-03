<?php

session_start();

if(isset($_SESSION['account'])){
    if(!$_SESSION['account']['is_staff']){
        header('location: login.php');
    }
}else{
    header('location: login.php');
}

require_once('../tools/functions.php');
require_once('../classes/product.class.php');
require_once('../classes/stocks.class.php');

$name = $quantity = $status = $reason = '';
$quantityErr = $statusErr = $reasonErr = '';
$productObj = new Product(); 
$stocksObj = new Stocks();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $productObj->fetchRecord($id);
        if (!empty($record)) {
            $name = $record['name'];
        } else {
            echo 'No product found';
            exit;
        }
    } else {
        echo 'No product found';
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_GET['id'];
    $record = $productObj->fetchRecord($id);
    if (!empty($record)) {
        $name = $record['name'];
    } else {
        echo 'No product found';
        exit;
    }
    $product_id = clean_input($_GET['id']);
    $quantity = clean_input($_POST['quantity']);
    $status = isset($_POST['status']) ? clean_input($_POST['status']): '';
    $reason = clean_input($_POST['reason']);

    if (empty($quantity)) {
        $quantityErr = 'Quantity is required';
    } elseif (!is_numeric($quantity)) {
        $quantityErr = 'Quantity should be a number';
    } elseif ($quantity < 1) {
        $quantityErr = 'Quantity must be greater than 0';
    } elseif($status == 'out' && $quantity > $stocksObj->getAvailableStocks($product_id)){
        $rem = ($stocksObj->getAvailableStocks($product_id))? $stocksObj->getAvailableStocks($product_id):0;
        $quantityErr = "Quantity must be less than the Available Stocks: $rem";
    }

    if (empty($status)) {
        $statusErr = 'Status is required';
    }

    if (empty($reason) && $status == 'out') {
        $reasonErr = 'Reason is required';
    }

    if (empty($quantityErr) && empty($statusErr) && empty($reasonErr)) {
        $stocksObj->product_id = $product_id;
        $stocksObj->quantity = $quantity;
        $stocksObj->status = $status;
        $stocksObj->reason = $reason;

        if ($stocksObj->add()) {
            header('Location: ../index.php');
        } else {
            echo 'Something went wrong when stocking the product';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock In/Out Product <?= $name ?></title>
    <style>
        .error {
            color: red;
        }
        .d-none{
            display: none;
        }
        .d-block{
            display: block;
        }
    </style>
</head>
<body>
    <form action="?id=<?= $id ?>" method="post"> 
        <h3>Stock In/Out for Product <?= $name ?></h3>
        <!-- Display a note indicating required fields -->
        <span class="error">* are required fields</span>
        <br>

        <label for="quantity">Quantity</label><span class="error">*</span>
        <br>
        <input type="number" name="quantity" id="quantity" value="<?= $quantity ?>">
        <br>
        <?php if (!empty($quantityErr)): ?>
            <span class="error"><?= $quantityErr ?></span>
            <br>
        <?php endif; ?>

        <label for="">Status</label><span class="error">*</span>
        <input type="radio" class="stocks" value="in" name="status" id="stockin" <?= $status == 'in' ? 'checked':'' ?>><label for="stockin">Stock In</label>
        <input type="radio" class="stocks" value="out" name="status" id="stockout" <?= $status == 'out' ? 'checked':'' ?>><label for="stockout">Stock Out</label>
        <br>
        <?php if (!empty($statusErr)): ?>
            <span class="error"><?= $statusErr ?></span>
            <br>
        <?php endif; ?>

        <div id="reason" class="<?= $status == 'out'? '':'d-none' ?>">
            <label for="reason">Reason</label><span class="error">*</span>
            <br>
            <textarea name="reason" id="reason" cols="30"><?= $reason ?></textarea>
            <br>
            <?php if (!empty($reasonErr)): ?>
                <span class="error"><?= $reasonErr ?></span>
                <br>
            <?php endif; ?>
        </div>

        <input type="submit" value="Save Stocks">
    </form>

    <script src="../js/stocks.js"></script>
</body>
</html>