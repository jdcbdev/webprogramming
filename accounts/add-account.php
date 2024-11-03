<?php

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$firstName = $lastName = $username = $password = $role = $isStaff = $isAdmin = '';
$firstNameErr = $lastNameErr = $usernameErr = $passwordErr = $roleErr = '';

$accountObj = new Account();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = clean_input($_POST['first-name'] ?? '');
    $lastName = clean_input($_POST['last-name'] ?? '');
    $username = clean_input($_POST['username'] ?? '');
    $password = clean_input($_POST['password'] ?? '');
    $role = clean_input($_POST['role'] ?? '');
    $isStaff = isset($_POST['is-staff']) ? true : false;
    $isAdmin = isset($_POST['is-admin']) ? true : false;

    if (empty($firstName)) {
        $firstNameErr = 'First name is required.';
    }

    if (empty($lastName)) {
        $lastNameErr = 'Last name is required.';
    }

    if (empty($username)) {
        $usernameErr = 'Username is required.';
    } else if ($accountObj->usernameExist($username)) {
        $usernameErr = 'Username already exists.';
    }

    if (empty($password)) {
        $passwordErr = 'Password is required.';
    }

    if (empty($role)) {
        $roleErr = 'Role is required.';
    }

    // If there are validation errors, return them as JSON
    if (!empty($firstNameErr) || !empty($lastNameErr) || !empty($usernameErr) || !empty($passwordErr) || !empty($roleErr)) {
        echo json_encode([
            'status' => 'error',
            'firstNameErr' => $firstNameErr,
            'lastNameErr' => $lastNameErr,
            'usernameErr' => $usernameErr,
            'passwordErr' => $passwordErr,
            'roleErr' => $roleErr,
        ]);
        exit;
    }

    $accountObj->first_name = $firstName;
    $accountObj->last_name = $lastName;
    $accountObj->username = $username;
    $accountObj->password = $password;
    $accountObj->role = $role;
    $accountObj->is_staff = $isStaff;
    $accountObj->is_admin = $isAdmin;

    if ($accountObj->add()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new account.']);
    }
}
?>