<?php $this->layout('layouts/base') ?>

<?php $this->start('headAdditional') ?>
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<?php $this->stop() ?>

<?php $this->start('footAdditional') ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#datetimepicker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        defaultDate: new Date()
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

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Absensi Kehadiran Project <?= $project->name ?></h4>
                    <div>
                        <a href="<?= BASE_URL ?>/project/detail/<?= $meeting->project_id ?>" class="btn btn-primary my-2">Detail Project</a>
                        <?php if ((time() - strtotime($meeting->created_at)) <= 3 * 24 * 60 * 60): ?>
                        <a href="#" class="btn btn-danger delete-btn my-1" data-url="<?= BASE_URL ?>/project/meeting-attendance-delete/<?= $meeting->id ?>">
                            Hapus Absensi
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <label for="datetimepicker" class="form-label">Tanggal dan Jam Meeting</label>
                    <div class="input-group">
                        <?= $meeting->date ?>: <?= $meeting->time_start ?> <?= $meeting->time_end ? ' - '. $meeting->time_end : '' ?>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <label for="description" class="form-label">Keterangan</label>
                    <div class="input-group">
                    <?= $meeting->description ?? '-' ?>
                    </div>
                </div>
            </div>
            <?php foreach ($meetingAttendance as $key => $value) { ?>
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" value="<?= $value[0]; ?>" disabled required>
                    </div>
                    <div class="col-md-2">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" class="form-control" value="<?= $value[1]; ?>" disabled required>
                    </div>
                    <div class="col-md-2">
                        <label for="attendances" class="form-label">Kehadiran</label>
                        <input type="text" class="form-control" value="<?= $value[2]; ?>" disabled required>
                    </div>
                    <div class="col-md-6">
                        <label for="notes" class="form-label">Catatan</label>
                        <div>
                            <?= $value[3]; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>