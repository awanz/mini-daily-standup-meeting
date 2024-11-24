<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#usertable', {
        layout: {
            topStart: {
                buttons: ['excel']
            }
        },
        order: [[0, 'asc']]
    });
</script>
<?php $this->stop() ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <p>Hello, <b><?= $dataUser->fullname ?></b></p>
            </div>
            <?php if ($alert): ?>
            <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                <?= $alert['message'] ?>
            </div>
            <?php endif ?>
            <div class="table-responsive">
                <table id="usertable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Total Daily</th>
                            <th>Send Password</th>
                            <th>Peringatan 1</th>
                            <th>Pemecatan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $key => $value) { ?>
                        <tr>
                            <td><?= $value[0] ?></td>
                            <td><?= $value[2] ?></td>
                            <td><?= $value[3] ?></td>
                            <td><?= $value[7] ?></td>
                            <td><a href="<?= BASE_URL ?>/email/credential/<?= $value[1] ?>" class="btn btn-dark">Send</a></td>
                            <td><a href="<?= BASE_URL ?>/email/peringatan/<?= $value[1] ?>" class="btn btn-dark">Send</a></td>
                            <td><a href="<?= BASE_URL ?>/email/pemecatan/<?= $value[1] ?>" class="btn btn-dark">Send</a></td>
                            <td>
                                <?php if (!empty($value[4])) { ?>
                                <a target="_BLANK" href="https://wa.me/<?= $value[4] ?>" class="btn btn-success">WA</a>
                                <?php } ?>
                                <a href="<?= BASE_URL ?>/user/edit/<?= $value[1] ?>" class="btn btn-warning">Edit</a>
                                <a href="<?= BASE_URL ?>/user/delete/<?= $value[1] ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>