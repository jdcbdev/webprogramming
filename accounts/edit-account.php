<?php

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$id = $_GET['id'] ?? '';
$accountObj = new Account();

$account = $accountObj->getById($id);

if (!$account) {
    echo "Account not found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $lastName = $username = $password = $role = $isStaff = $isAdmin = '';
    $firstNameErr = $lastNameErr = $usernameErr = $roleErr = '';

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
    } else if ($accountObj->usernameExist($username, $id)) {
        $usernameErr = 'Username already exists.';
    }

    if (empty($role)) {
        $roleErr = 'Role is required.';
    }

    // If there are validation errors, return them as JSON
    if (!empty($firstNameErr) || !empty($lastNameErr) || !empty($usernameErr) || !empty($roleErr)) {
        echo json_encode([
            'status' => 'error',
            'firstNameErr' => $firstNameErr,
            'lastNameErr' => $lastNameErr,
            'usernameErr' => $usernameErr,
            'roleErr' => $roleErr,
        ]);
        exit;
    }

    $accountObj->id = $id;
    $accountObj->first_name = $firstName;
    $accountObj->last_name = $lastName;
    $accountObj->username = $username;
    $accountObj->password = $password;
    $accountObj->role = $role;
    $accountObj->is_staff = $isStaff;
    $accountObj->is_admin = $isAdmin;

    if ($accountObj->update()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when update the account.']);
    }
    exit();
}
?>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Update Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" id="form-edit-account">
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="first-name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first-name" name="first-name"
                            value="<?= $account['first_name'] ?>" />
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label for="last-name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last-name" name="last-name"
                            value="<?= $account['last_name'] ?>">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?= $account['username'] ?>">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="password" name="password">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" name="role" id="role" class="form-control" value="<?= $account['role'] ?>" />
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is-staff" name="is-staff"
                                <?= $account['is_staff'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is-staff">Is Staff</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is-admin" name="is-admin"
                                <?= $account['is_admin'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is-admin">Is Admin</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary brand-bg-color">Update Account</button>
                </div>
            </form>
        </div>
    </div>
</div>