<?php

require_once('../classes/account.class.php');

$id = $_GET['id'] ?? '';

$accountObj = new Account();

$account = $accountObj->getById($id);

if (!$account) {
    echo json_encode(['status' => 'error', 'message' => 'Account not found']);
    exit();
}

$accountObj->id = $id;
$accountObj->delete();

echo json_encode(['status' => 'success']);