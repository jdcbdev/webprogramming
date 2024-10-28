<?php
    session_start();
/*
    if(isset($_SESSION['account'])){
        if(!$_SESSION['account']['is_staff']){
            header('location: login.php');
        }
    }else{
        header('location: login.php');
    }*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <style>
        /* Styling for the search results */
        p.search {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <a href="addproduct.php">Add Product</a>
    
    <?php
        require_once '../classes/account.class.php';

        $accountObj = new Account();

        $keyword = '';
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize input from the search form
            $keyword = htmlentities($_POST['keyword']);
        }

        $array = $accountObj->showAll($keyword);
    ?>

    <form action="" method="post">
        
        <label for="keyword">Search</label>
        <input type="text" name="keyword" id="keyword" value="<?= $keyword ?>">
        <input type="submit" value="Search" name="search" id="search">
    </form>
    <table border="1">
        <tr>
            <th>No.</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Role</th>
        </tr>
        
        <?php
        $i = 1;
        if (empty($array)) {
        ?>
            <tr>
                <td colspan="7"><p class="search">No product found.</p></td>
            </tr>
        <?php
        }
        foreach ($array as $arr) {
        ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= $arr['first_name'] ?></td>
            <td><?= $arr['last_name'] ?></td>
            <td><?= $arr['username'] ?></td>
            <td><?= $arr['role'] ?></td>
            <td>
                
                <a href="editproduct.php?id=<?= $arr['id'] ?>">Edit</a>
                <?php
                    if ($_SESSION['account']['is_admin']){
                ?>
                <a href="#" class="deleteBtn" data-id="<?= $arr['id'] ?>" data-name="<?= $arr['name'] ?>">Delete</a>
                <?php
                    }
                ?>
            </td>
        </tr>
        <?php
            $i++;
        }
        ?>
    </table>
    
    <script src="./product.js"></script>
</body>
</html>
