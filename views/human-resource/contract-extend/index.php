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
                    <h4>Daftar Pengajuan Perpanjangan Magang</h4>
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
                            <th>Durasi</th>
                            <th>Catatan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($data = $contractExtend->fetch_object()) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $data->created_at ?></td>
                            <td><?= $data->fullname ?></td>
                            <td><?= $data->role_name ?></td>
                            <td><?= $data->duration ?> Bulan</td>
                            <td><?= $data->description ?></td>
                            <td class="d-flex">

                                <?php if ($data->status == "REQUEST"): ?>
                                    <form class="mx-1" method="POST" action="<?= BASE_URL ?>/hr/contract-extend/approve">
                                        <input type="hidden" name="id" value="<?= $data->id ?>">
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form class="mx-1" method="POST" action="<?= BASE_URL ?>/hr/contract-extend/revise">
                                        <input type="hidden" name="id" value="<?= $data->id ?>">
                                        <button type="submit" class="btn btn-sm btn-warning">Revise</button>
                                    </form>
                                <?php elseif(!empty($data->approval_id)): ?>
                                    <a href="<?= BASE_URL ?>/user/detail/<?= $data->approval_id ?>"><?= $data->approval_name ?></a>
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