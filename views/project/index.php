<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#usertable', {
        pageLength: 30,
        lengthMenu: [10, 30, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'asc']],
    });
</script>
<?php $this->stop() ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Projects</h4>
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
                            <th>Project</th>
                            <th>PIC</th>
                            <th>Status</th>
                            <th>GDrive</th>
                            <th>Figma</th>
                            <th>Logo</th>
                            <th>Repo</th>
                            <th>WA</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $key => $value) { ?>
                        <tr>
                            <td><?= $value[2] ?></td>
                            <td><?= $value[1] ?></td>
                            <td><span class="badge bg-primary"><?= $value[4] ?></span></td>
                            <td>
                                <?php if (isset($value[6])) { ?>
                                <a target="_BLANK" href="<?= $value[6] ?>" class="btn btn-dark">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (isset($value[7])) { ?>
                                <a target="_BLANK" href="<?= $value[7] ?>" class="btn btn-dark">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (isset($value[8])) { ?>
                                <a target="_BLANK" href="<?= $value[8] ?>" class="btn btn-dark">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (isset($value[9])) { ?>
                                <a target="_BLANK" href="<?= $value[9] ?>" class="btn btn-dark">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (isset($value[10])) { ?>
                                <a target="_BLANK" href="<?= $value[10] ?>" class="btn btn-dark">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/project/delete/<?= $value[0] ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>