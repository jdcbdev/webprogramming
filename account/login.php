<?php
    require_once '../tools/functions.php';
    require_once '../classes/account.class.php';

    session_start();

    $username = $password = '';
    $accountObj = new Account();
    $loginErr = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = clean_input(($_POST['username']));
        $password = clean_input($_POST['password']);

        if($accountObj->login($username, $password)){
            $data = $accountObj->fetch($username);
            $_SESSION['account'] = $data;
            header('location: ../admin/dashboard.php');
        }else{
            $loginErr = 'Invalid username/password';
        }
    }else{
        if(isset($_SESSION['account'])){
            if($_SESSION['account']['is_staff']){
                header('location: ../admin/dashboard.php');
            }
        }
    }

    $page_title = 'Admin Login';
    require_once '../includes/head.php';
?>
<body id="login" class="authentication-bg position-relative">
    <div class="position-absolute start-0 end-0 start-0 bottom-0 w-100 h-100">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 800 800">
            <g fill-opacity="0.22">
                <circle style="fill: rgba(var(--brand-color-rgb), 0.1);" cx="400" cy="400" r="600"></circle>
                <circle style="fill: rgba(var(--brand-color-rgb), 0.2);" cx="400" cy="400" r="500"></circle>
                <circle style="fill: rgba(var(--brand-color-rgb), 0.3);" cx="400" cy="400" r="300"></circle>
                <circle style="fill: rgba(var(--brand-color-rgb), 0.4);" cx="400" cy="400" r="200"></circle>
                <circle style="fill: rgba(var(--brand-color-rgb), 0.5);" cx="400" cy="400" r="100"></circle>
            </g>
        </svg>
    </div>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative"> 
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <div class="card-header py-4 text-center bg-primary brand-bg-color">
                            <a href="index.html">
                                <span><img src="../img/logo1-whitetext.png" alt="logo" height="50"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">Sign In</h4>
                                <p class="text-muted mb-4 fs-6">Enter your username and password to access admin panel.</p>
                            </div>

                            <form action="login.php" method="post" id="login">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input class="form-control" type="text" id="username" name="username" placeholder="Enter your username" tabindex="1" value="<?= $username ?>">
                                </div>
                                <div class="mb-3">
                                    <a href="pages-recoverpw.html" class="float-end forgotpassword-link" tabindex=false>
                                        <small>Forgot your password?</small>
                                    </a>
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" tabindex="2" value="<?= $password ?>">
                                        <div class="input-group-text" data-password="false">
                                            <i class="bi bi-eye" id="togglePassword"></i>
                                            <i class="bi bi-eye-slash" id="togglePasswordSlash" style="display: none;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin" checked="">
                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>
                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary brand-bg-color" type="submit" value="Login" name="login"> Log In </button>
                                </div>
                                <?php if (!empty($loginErr)): ?>
                                    <div class="alert alert-danger text-center mt-3" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $loginErr ?>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        require_once '../includes/footer.php';
    ?>
</body>
</html>