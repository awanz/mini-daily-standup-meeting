<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
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

<div class="container">
    <?php if ($alert): ?>
        <div class="my-2 alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
            <?= $alert['message'] ?>
        </div>
    <?php endif ?>
</div>
<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Anggota Projects <?= $project->name ?></h4>
                    <div>
                        <a href="<?= BASE_URL ?>/project" class="btn btn-light my-2">List Project</a>
                        <a href="#" class="btn btn-primary my-2 disabled" disabled>Absensi</a>
                        <a href="<?= BASE_URL ?>/project/add-member/<?= $id ?>" class="btn btn-dark my-2">Tambah Member</a>
                    </div>
                </div>
            </h5>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($users as $key => $value) {?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= $value[1] ?></h5>
                            <h6><?= $value[7] ?></h6>
                            <p class="card-text">
                                e-mail: <?= $value[2] ?><br>
                                No HP: <?= $value[3] ?><br>
                                <span class="<?php if ($value[8]) { ?>text-dark bg-warning<?php } ?>">
                                    Kontrak: <?= $value[4] ?> - <?= $value[5] ?><br>
                                </span>
                                Catatan: <?= $value[9] ?><br>
                            </p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <small class="text-muted">Total Daily Bulan Ini: <a href="<?= BASE_URL ?>/history/<?= $value[2] ?>"><?= $value[6] ?></a> </small>
                            <small class="text-muted">
                                <a href="#" class="disabled">Catatan</a> - 
                                <a href="#" class="delete-btn" data-url="<?= BASE_URL ?>/project/nonactive-member/<?= $value[11] ?>">Nonactive</a>
                            </small>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Daily Standup Meeting</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="history" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Aktifitas Kemarin</th>
                        <th>Hari ini</th>
                        <th>Permasalahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dailys as $daily): ?>
                    <tr>
                        <td><?=$this->e($daily[2])?></td>
                        <th><?= $daily[8] ?></th>
                        <td><?= $daily[3] ?></td>
                        <td><?= $daily[4] ?></td>
                        <td><?= $daily[5] ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>