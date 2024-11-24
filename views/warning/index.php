<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#warningTable', {
        pageLength: 50,
        lengthMenu: [50, 100, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'desc']],
    });
</script>
<?php $this->stop() ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Pelanggaran</h4>
                    <div><?= date('d M Y') ?></div>
                </div>
            </h5>
            <?php if ($alert): ?>
            <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                <?= $alert['message'] ?>
            </div>
            <?php endif ?>
            <p>Banding hanya bisa di lakukan sekali dan hanya bisa melakukan banding jika dari keluarnya pemecatan kurang dari 20 hari.</p>
            <div class="table-responsive">
                <table id="warningTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Email</th>
                            <th>Jenis</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($warnings as $key => $value) { ?>
                        <tr>
                            <td><?= $value[6] ?></td>
                            <td><?= $value[2] ?></td>
                            <td>
                                <?= $value[3] == 2 ? '<span class="badge bg-danger">PEMECATAN</span>' : '<span class="badge bg-warning">PERINGATAN</span>' ?>
                            </td>
                            <td>
                                <?php if(strtotime($value[6]) >= strtotime('-20 days') && $value[3] == 2 && empty($value[4])) { ?>
                                <a href="<?= BASE_URL ?>/warnings/appeal/<?= $value[0] ?>" class="btn btn-dark">Banding</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>