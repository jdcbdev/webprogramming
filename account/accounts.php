<?php
    session_start();

    if(isset($_SESSION['account'])){
        if(!$_SESSION['account']['is_staff']){
            header('location: login.php');
        }
    }else{
        header('location: login.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
</head>
<body>

    <?php
        require_once '../classes/account.class.php';
        $accountObj = new Account();
    ?>

    <table border="1">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php
            $accounts = $accountObj->getAll();
            $i = 1;
            
            foreach($accounts as $account) {
        ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= $account['first_name'] . ' ' . $account['last_name']; ?></td>
            <td><?= $account['username']; ?></td>
            <td><?= $account['role']; ?></td>
            <td>
                <button class="btn btn-sm btn-outline-success">Edit</button>
                <button class="btn btn-sm btn-outline-danger">Delete</button>
            </td>
        </tr>
        <?php $i++;  } ?>
    </table>

</body>
</html>