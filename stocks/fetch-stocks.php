<?php
    require_once('../classes/stocks.class.php');

    $stockObj = new Stocks();
    $record = 0;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $stockObj->getAvailableStocks($id);
    }

    header('Content-Type: application/json');
    echo json_encode($record);
?> 