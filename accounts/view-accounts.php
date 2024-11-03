<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Accounts</h4>
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
                        require_once '../classes/account.class.php';
                        session_start();
                        $accountObj = new Account();
                        ?>
                        <div class="d-flex justify-content-center align-items-center">
                            <form class="d-flex me-2">
                                <div class="input-group w-100">
                                    <input type="text" class="form-control form-control-light" id="custom-search"
                                        placeholder="Search accounts...">
                                    <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                        <i class="bi bi-search"></i>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="page-title-right d-flex align-items-center">
                            <a id="add-account" href="#" class="btn btn-primary brand-bg-color">Add Account</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="table-products" class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th class="text-center">First Name</th>
                                    <th class="text-center">Last Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Staff</th>
                                    <th>Admin</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $array = $accountObj->showAll();
                                ?>
                                <?php foreach ($array as $arr): ?>
                                    <tr>
                                        <td class="text-start"><?= $i++ ?></td>
                                        <td><?= $arr['first_name'] ?></td>
                                        <td><?= $arr['last_name'] ?></td>
                                        <td><?= $arr['username'] ?></td>
                                        <td><?= $arr['role'] ?></td>
                                        <td><?= $arr['is_staff'] ? 'Yes' : 'No' ?></td>
                                        <td><?= $arr['is_admin'] ? 'Yes' : 'No' ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-success me-1 edit-account-btn"
                                                data-id="<?= $arr['id'] ?>">Edit</button>
                                            <button class="btn btn-sm btn-outline-danger deleteBtn delete-account-btn"
                                                data-id="<?= $arr['id'] ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>