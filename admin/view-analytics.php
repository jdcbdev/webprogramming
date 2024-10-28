<?php
require_once '../classes/account.class.php';
session_start();

// Check if the request is made via AJAX
if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
    // If not an AJAX request, redirect to access denied page
    header('Location: ../account/access_forbidden.php');
    exit();
}

// Continue with the rest of the page for AJAX requests
$accountObj = new Account();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
                <div class="page-title-right">
                    <form class="d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-light" id="dash-daterange">
                            <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                <i class="bi bi-calendar3"></i>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-12 col-lg-12 col-xl-12 d-flex flex-column">
            <div class="row flex-grow-1">
            <div class="col-12 col-sm-6 col-md-6 col-xl-3 pb-4">
                <div class="card widget-flat mb-0">
                <div class="card-body">
                    <div class="float-end me-2">
                        <i class="bi bi-people fs-1  brand-color"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Customers</h5>
                    <h3 class="my-3">36,254</h3>
                    <p class="mb-0 text-muted">
                        <span class="text-success me-2"><i class="bi bi-arrow-up"></i> 5.27%</span>
                    </p>
                    <p class="mb-0 text-muted pt-2">
                        <span class="text-nowrap">Since last month</span>
                    </p>
                </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 col-xl-3 pb-4">
                <div class="card widget-flat mb-0">
                <div class="card-body">
                    <div class="float-end me-2">
                        <i class="bi bi-cart3 fs-1  brand-color"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Orders</h5>
                    <h3 class="my-3">5,543</h3>
                    <p class="mb-0 text-muted">
                        <span class="text-danger me-2"><i class="bi bi-arrow-down"></i></i> 1.08%</span>
                    </p>
                    <p class="mb-0 text-muted pt-2">
                        <span class="text-nowrap">Since last month</span>
                    </p>
                </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 col-xl-3 pb-4">
                <div class="card widget-flat mb-0">
                <div class="card-body">
                    <div class="float-end me-2">
                        <i class="bi bi-graph-up fs-1  brand-color"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Average Sales">Sales</h5>
                    <h3 class="my-3">₱6,254</h3>
                    <p class="mb-0 text-muted">
                        <span class="text-danger me-2"><i class="bi bi-arrow-down"></i></i> 7.00%</span>
                    </p>
                    <p class="mb-0 text-muted pt-2">
                        <span class="text-nowrap">Since last month</span>
                    </p>
                </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 col-xl-3 pb-4">
                <div class="card widget-flat mb-0">
                <div class="card-body">
                    <div class="float-end me-2">
                        <i class="bi bi-graph-up-arrow fs-1  brand-color"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Growth">Growth</h5>
                    <h3 class="my-3">+ 30.56%</h3>
                    <p class="mb-0 text-muted">
                        <span class="text-success me-2"><i class="bi bi-arrow-up"></i></i> 4.87%</span>
                    </p>
                    <p class="mb-0 text-muted pt-2">
                        <span class="text-nowrap">Since last month</span>
                    </p>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card card-h-100 p-3">
                <div class="d-flex card-header justify-content-between align-items-center w-100">
                    <h3 class="header-title mb-0">Sales By Month</h3>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical brand-color fs-3"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                        </div>
                    </div>
                </div>
                <canvas id="salesChart" class="card-body"></canvas>
            </div>
        </div>
        <div class="col-12">
            <div class="card p-4">
                <div class="d-flex card-header justify-content-between align-items-center w-100 px-2">
                    <h3 class="header-title mb-0">Top Selling Products</h3>
                </div>
                <div class="card-body p-1 pt-2">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <tbody>
                                <tr>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">Spicy Pepperoni Pizza</h5>
                                        <span class="text-muted font-13">07 April 2024</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">₱399.99</h5>
                                        <span class="text-muted font-13">Price</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">82</h5>
                                        <span class="text-muted font-13">Quantity</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">₱32,799.18</h5>
                                        <span class="text-muted font-13">Amount</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">Jalapeño Hot Pizza</h5>
                                        <span class="text-muted font-13">25 March 2024</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">₱499.50</h5>
                                        <span class="text-muted font-13">Price</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">37</h5>
                                        <span class="text-muted font-13">Quantity</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">₱18,481.50</h5>
                                        <span class="text-muted font-13">Amount</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">Chili Lover's Pizza</h5>
                                        <span class="text-muted font-13">17 March 2024</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">₱349.99</h5>
                                        <span class="text-muted font-13">Price</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">64</h5>
                                        <span class="text-muted font-13">Quantity</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">₱22,399.36</h5>
                                        <span class="text-muted font-13">Amount</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">Spicy BBQ Pizza</h5>
                                        <span class="text-muted font-13">12 March 2024</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">₱299.99</h5>
                                        <span class="text-muted font-13">Price</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">184</h5>
                                        <span class="text-muted font-13">Quantity</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">₱55,198.16</h5>
                                        <span class="text-muted font-13">Amount</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">Extra Hot Pizza</h5>
                                        <span class="text-muted font-13">05 March 2024</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">₱429.99</h5>
                                        <span class="text-muted font-13">Price</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">69</h5>
                                        <span class="text-muted font-13">Quantity</span>
                                    </td>
                                    <td>
                                        <h5 class="font-14 my-1 fw-normal">₱29,669.31</h5>
                                        <span class="text-muted font-13">Amount</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div> <!-- end table-responsive-->
                </div>
            </div>
        </div>
    </div>
</div>