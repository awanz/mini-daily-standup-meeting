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
                            <th>Tanggal Approve</th>
                            <th>Nama Lengkap</th>
                            <th>Role</th>
                            <th>Tanggal Magang</th>
                            <th>Status</th>
                            <th>No Sertifikat</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($data = $finalizations->fetch_object()) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= date("Y-m-d", strtotime($data->approval_date)) ?></td>
                            <td><?= $data->fullname ?></td>
                            <td><?= $data->role_name ?></td>
                            <td><?= $data->date_start ?> - <?= $data->date_end ?></td>
                            <td><?= $data->status ?> (<?= $data->id ?>)</td>
                            <td><?= $data->certificate ?></td>
                            <td class="d-flex">
                                <?php if (isset($data->certificate)) { ?>
                                <form method="POST" action="<?= BASE_URL ?>/hr/certificate-print/print">
                                    <input type="hidden" name="id" value="<?= $data->id ?>">
                                    <button type="submit" class="btn btn-outline-dark mx-1 btn-sm">Cetak</button>
                                </form>
                                <form method="POST" action="<?= BASE_URL ?>/hr/certificate-print/send">
                                    <input type="hidden" name="id" value="<?= $data->id ?>">
                                    <button type="submit" class="btn btn-success mx-1 btn-sm">Kirim</button>
                                </form>
                                <?php }else{ ?>
                                <span>Tidak ada Sertifikat</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>