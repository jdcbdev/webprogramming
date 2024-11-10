<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Products</h4>
            </div>
        </div>
    </div>
    <div class="modal-container"></div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <?php
                            require_once '../classes/product.class.php';
                            session_start();
                            $productObj = new Product();
                        ?>
                        <div class="d-flex justify-content-center align-items-center">
                            <form class="d-flex me-2">
                                <div class="input-group w-100">
                                    <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search products...">
                                    <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                        <i class="bi bi-search"></i>
                                    </span>
                                </div>
                            </form>
                            <div class="d-flex align-items-center">
                                <label for="category-filter" class="me-2">Category</label>
                                <select id="category-filter" class="form-select">
                                    <option value="choose">Choose...</option>
                                    <option value="">All</option>
                                    <?php
                                        $categoryList = $productObj->fetchCategory();
                                        foreach ($categoryList as $cat) {
                                    ?>
                                        <option value="<?= $cat['name'] ?>"><?= $cat['name'] ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="page-title-right d-flex align-items-center"> 
                            <a id="add-product" href="#" class="btn btn-primary brand-bg-color">Add Product</a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="table-products" class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th class="text-start">Price</th>
                                    <th class="text-center">Total Stocks</th>
                                    <th class="text-center">Available Stocks</th>
                                    <th class="text-center">Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $array = $productObj->showAll();

                                foreach ($array as $arr) {
                                    $available = $arr['stock_in'] - $arr['stock_out'];
                                ?>
                                    <tr>
                                        <td class="text-start"><?= $i ?></td>
                                        <td><?= $arr['code'] ?></td>
                                        <td><?= $arr['name'] ?></td>
                                        <td><?= $arr['category_name'] ?></td>
                                        <td><?= number_format($arr['price'], 2) ?></td>
                                        <td class="text-center"><?= $arr['stock_in'] ?></td>
                                        <td class="text-center">
                                            <span class="
                                                <?php
                                                if ($available < 1) {
                                                    echo 'badge rounded-pill bg-danger px-3';
                                                } elseif ($available <= 5) {
                                                    echo 'badge rounded-pill bg-warning px-3'; 
                                                }
                                                ?>
                                            ">
                                                <?= $available ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <img src="<?= $arr['file_path'] ?>" alt="thumbnail" class="img-fluid product-thumbnail">
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="../stocks/stocks.php?id=<?= $arr['id'] ?>" class="btn btn-sm btn-outline-primary me-1">Stock In/Out</a>
                                            <a href="../products/editproduct.php?id=<?= $arr['id'] ?>" class="btn btn-sm btn-outline-success me-1">Edit</a>
                                            <?php if (isset($_SESSION['account']['is_admin']) && $_SESSION['account']['is_admin']) { ?>
                                                <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="<?= $arr['id'] ?>" data-name="<?= htmlspecialchars($arr['name']) ?>">Delete</button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
