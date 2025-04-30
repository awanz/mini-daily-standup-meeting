<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#resignTable', {
        pageLength: 200,
        lengthMenu: [5, 10, 25, 50, 100, 500, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'asc']],
    });
</script>
<?php $this->stop() ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Daftar Pengajuan Pengunduran Diri</h4>
                </div>
            </h5>
            <?php if ($alert): ?>
            <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                <?= $alert['message'] ?>
            </div>
            <?php endif ?>
            <div class="table-responsive">
                <table id="resignTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pengajuan</th>
                            <th>Fullname</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>File</th>
                            <th>Reason</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($resigns as $key => $value) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= date("Y-m-d", strtotime($value[8])) ?></td>
                            <td><?= $value[1] ?></td>
                            <td><?= $value[2] ?></td>
                            <td><?= $value[3] ?></td>
                            <td><a href="<?= $value[4] ?>" target="_BLANK">File Resign</a></td>
                            <td><?= $value[5] ?></td>
                            <td>
                            <?php if ($value[3] == "REQUEST"): ?>
                                <form method="POST" action="<?= BASE_URL ?>/hr/resigns/approve">
                                    <input type="hidden" name="id" value="<?= $value[0] ?>">
                                    <button type="submit" class="btn btn-success my-2">Approve</button>
                                </form>
                                <form method="POST" action="<?= BASE_URL ?>/hr/resigns/revise">
                                    <input type="hidden" name="id" value="<?= $value[0] ?>">
                                    <button type="submit" class="btn btn-warning my-2">Revise</button>
                                </form>
                                <form method="POST" action="<?= BASE_URL ?>/hr/resigns/cancel">
                                    <input type="hidden" name="id" value="<?= $value[0] ?>">
                                    <button type="submit" class="btn btn-danger my-2">Cancel</button>
                                </form>
                            <?php elseif(!empty($value[6])): ?>
                                <a href="<?= BASE_URL ?>/user/detail/<?= $value[7] ?>"><?= $value[6] ?></a>
                            <?php endif; ?>
                            </td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>