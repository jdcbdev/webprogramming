<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Accounts</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once '../classes/account.class.php';
                                $accountObj = new Account();
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
