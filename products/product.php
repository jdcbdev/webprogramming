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
        require_once '../classes/product.class.php';

        $productObj = new Product();

        $keyword = $category = '';
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize input from the search form
            $keyword = htmlentities($_POST['keyword']);
            $category = htmlentities($_POST['category']);
        }

        $array = $productObj->showAll($keyword, $category);
    ?>

    <form action="" method="post">
        <label for="category">Category</label>
        <select name="category" id="category">
            <option value="">All</option>
            <?php
                $categoryList = $productObj->fetchCategory();
                foreach ($categoryList as $cat){
            ?>
                <option value="<?= $cat['id'] ?>" <?= ($category == $cat['id']) ? 'selected' : '' ?>><?= $cat['name'] ?></option>
            <?php
                }
            ?>
        </select>
        <label for="keyword">Search</label>
        <input type="text" name="keyword" id="keyword" value="<?= $keyword ?>">
        <input type="submit" value="Search" name="search" id="search">
    </form>
    <table border="1">
        <tr>
            <th>No.</th>
            <th>Code</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Total Stocks</th>
            <th>Available Stocks</th>
            <th>Action</th>
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
            $available = $arr['stock_in'] - $arr['stock_out'];
        ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= $arr['code'] ?></td>
            <td><?= $arr['name'] ?></td>
            <td><?= $arr['category_name'] ?></td>
            <td><?= $arr['price'] ?></td>
            <td><?= $arr['stock_in'] ?></td>
            <td><?= $available ?></td>
            <td>
                <a href="stocks.php?id=<?= $arr['id'] ?>">Stock In/Out</a>
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