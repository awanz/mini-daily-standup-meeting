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
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Role</h4>
                    <div><?= date('d M Y') ?></div>
                </div>
            </h5>
            <?php if ($alert): ?>
            <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                <?= $alert['message'] ?>
            </div>
            <?php endif ?>
            <div class="table-responsive">
                <table id="usertable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Whatsapp</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $key => $value) { ?>
                        <tr>
                            <td><?= $value[0] ?></td>
                            <td><?= $value[1] ?></td>
                            <td>
                                <?php if (isset($value[3])) { ?>
                                <a target="_BLANK" href="<?= $value[3] ?>" class="btn btn-dark">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/role/delete/<?= $value[0] ?>" class="btn btn-danger">Delete</a>
                                <a href="<?= BASE_URL ?>/role/member/<?= $value[0] ?>" class="btn btn-dark">Member</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>