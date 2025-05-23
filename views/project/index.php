<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#usertable', {
        pageLength: 50,
        lengthMenu: [10, 30, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'asc']],
    });
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        const url = $(this).data('url');

        Swal.fire({
            title: "Yakin untuk melakukan delete?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
</script>
<?php $this->stop() ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Project List</h4>
                    <?php if ($isAdmin): ?>
                    <div>
                        <a href="<?= BASE_URL ?>/project/add" class="btn btn-dark my-2">Tambah Project</a>
                    </div>
                    <?php endif ?>
                </div>
            </h5>
            <?php if ($alert): ?>
            <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                <?= $alert['message'] ?>
            </div>
            <?php endif ?>
            <div class="table-responsive">
                <table id="usertable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Total Member</th>
                            <th>PIC</th>
                            <th>Status</th>
                            <th>Type</th>
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
                            <td>
                                <?= $value[1] ?>
                                <?php if ($value[12] == 1) { ?><span class="badge bg-primary text-white">A1X</span><?php } ?>
                                <?php if ($value[11] == 1) { ?><span class="badge bg-danger">P2X</span><?php } ?>
                            </td>
                            <td><?= $value[10] ?></td>
                            <td><?= $value[2] ?></td>
                            <td><?= $value[4] ?></td>
                            <td>
                            <?php 
                                if (isset($value[3]) && $value[3] == 'NOT_STARTED') { ?><span class="badge bg-light text-dark">Belum Mulai</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'IN_PROGRESS') { ?><span class="badge bg-warning text-dark">Berjalan</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'COMPLETED') { ?><span class="badge bg-success">Selesai</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'FIXING') { ?><span class="badge bg-info">Perbaikan</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'CANCEL') { ?><span class="badge bg-danger">Batal</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'MAINTENANCE') { ?><span class="badge bg-info">Maintenance</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'PENDING') { ?><span class="badge bg-secondary">Pending</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'PUBLISH') { ?><span class="badge bg-primary">Diterbitkan</span><?php } ?>
                            </td>
                            <td>
                                <?php if (!empty($value[5])) { ?>
                                <a target="_BLANK" href="<?= $value[5] ?>" class="btn btn-dark my-1">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (!empty($value[6])) { ?>
                                <a target="_BLANK" href="<?= $value[6] ?>" class="btn btn-dark my-1">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (!empty($value[7])) { ?>
                                <a target="_BLANK" href="<?= $value[7] ?>" class="btn btn-dark my-1">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (!empty($value[8])) { ?>
                                <a target="_BLANK" href="<?= $value[8] ?>" class="btn btn-dark my-1">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (!empty($value[9])) { ?>
                                <a target="_BLANK" href="<?= $value[9] ?>" class="btn btn-dark my-1">LINK</a>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/project/meeting-attendance/<?= $value[0] ?>" class="btn btn-success my-1">Absensi</a>
                                <a href="<?= BASE_URL ?>/project/detail/<?= $value[0] ?>" class="btn btn-primary my-1">Detail</a>
                                <a href="<?= BASE_URL ?>/project/edit/<?= $value[0] ?>" class="btn btn-warning my-1">Edit</a>
                                <?php if ($isAdmin): ?>
                                <a href="#" class="btn btn-danger delete-btn my-1" data-url="<?= BASE_URL ?>/project/delete/<?= $value[0] ?>">
                                    Delete
                                </a>
                                <?php endif ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>