<?php

require_once('../tools/functions.php');
require_once('../classes/stocks.class.php');

$name = $quantity = $status = $reason = '';
$quantityErr = $statusErr = $reasonErr = '';
$stocksObj = new Stocks();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = clean_input($_POST['id']);
    $quantity = clean_input($_POST['quantity']);
    $status = isset($_POST['status']) ? clean_input($_POST['status']): '';
    $reason = clean_input($_POST['reason']);

    if (empty($quantity)) {
        $quantityErr = 'Quantity is required.';
    } elseif (!is_numeric($quantity)) {
        $quantityErr = 'Quantity should be a number.';
    } elseif ($quantity < 1) {
        $quantityErr = 'Quantity must be greater than 0.';
    } elseif($status == 'out' && $quantity > $stocksObj->getAvailableStocks($product_id)){
        $rem = ($stocksObj->getAvailableStocks($product_id))? $stocksObj->getAvailableStocks($product_id):0;
        $quantityErr = "Quantity must be less than the Available Stocks: $rem";
    }

    if (empty($status)) {
        $statusErr = 'Please select status.';
    }

    if (empty($reason) && $status == 'out') {
        $reasonErr = 'When stocking out, reason is required.';
    }

    if (!empty($quantityErr) || empty(!$statusErr) || empty(!$reasonErr)) {
        echo json_encode([
            'status' => 'error',
            'quantityErr' => $quantityErr,
            'statusErr' => $statusErr,
            'reasonErr' => $reasonErr
        ]);
        exit;
    }

    if (empty($quantityErr) && empty($statusErr) && empty($reasonErr)) {
        $stocksObj->product_id = $product_id;
        $stocksObj->quantity = $quantity;
        $stocksObj->status = $status;
        $stocksObj->reason = $reason;

        if ($stocksObj->add()) {  
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong when stocking the product.']);
        }
        exit;
    }
}
