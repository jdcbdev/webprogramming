<?php
    session_start();
    /*
    if(isset($_SESSION['account'])){
        if(!$_SESSION['account']['is_staff']){
            header('location: ../account/login.php');
        }
    }else{
        header('location: ../account/login.php');
    }*/

    require_once '../includes/head.php';
?>
<body id="dashboard">
    <div class="wrapper">
        <?php
            require_once '../includes/topnav.php';
            require_once '../includes/sidebar.php';
        ?>
        <div class="content-page px-3">
            <!-- dynamic content here -->
        </div>
    </div>
    <?php
        require_once '../includes/footer.php';
    ?>
</body>
</html>