<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#finalizationTable', {
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
                    <h4>Daftar Pengajuan Penyelesaian Magang</h4>
                </div>
            </h5>
            <?php if ($alert): ?>
            <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                <?= $alert['message'] ?>
            </div>
            <?php endif ?>
            <div class="table-responsive">
                <table id="finalizationTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pengajuan</th>
                            <th>Fullname</th>
                            <th>Role</th>
                            <th>Tanggal Magang</th>
                            <th>Status</th>
                            <th>No Sertifikat</th>
                            <th>File</th>
                            <th>Survey</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($finalizations as $key => $value) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= date("Y-m-d", strtotime($value[10])) ?></td>
                            <td><?= $value[1] ?></td>
                            <td><?= $value[2] ?></td>
                            <td><?= $value[8] ?> - <?= $value[9] ?></td>
                            <td>
                                <?= $value[3] == 'REQUEST' ? '<span class="badge bg-success">REQUEST</span>': ''?>
                                <?= $value[3] == 'APPROVED' ? '<span class="badge bg-primary">APPROVED</span>': '' ?>
                                <?= $value[3] == 'REVISED' ? '<span class="badge bg-warning">REVISED</span>': '' ?>
                                <?= $value[3] == 'CANCELED' ? '<span class="badge bg-danger">CANCELED</span>': '' ?>
                            </td>
                            <td><?= $value[11] ?></td>
                            <td><a href="<?= $value[4] ?>" target="_BLANK">File Laporan Magang</a></td>
                            <td><?= $value[5] == 1 ? '<a href="https://docs.google.com/spreadsheets/d/1YheInax1JRz1Ljya3KdWZ5gH_BG18_apLG95o-0oFKc/edit?gid=1420681690#gid=1420681690" target="_BLANK">Sudah</a>' : 'Belum' ?></td>
                            <td>
                            <?php if ($value[3] == "REQUEST"): ?>
                                <form method="POST" action="<?= BASE_URL ?>/hr/finalizations/approve">
                                    <input type="hidden" name="id" value="<?= $value[0] ?>">
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                                <form method="POST" action="<?= BASE_URL ?>/hr/finalizations/revise">
                                    <input type="hidden" name="id" value="<?= $value[0] ?>">
                                    <button type="submit" class="btn btn-warning">Revise</button>
                                </form>
                                <form method="POST" action="<?= BASE_URL ?>/hr/finalizations/cancel">
                                    <input type="hidden" name="id" value="<?= $value[0] ?>">
                                    <button type="submit" class="btn btn-danger">Cancel</button>
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