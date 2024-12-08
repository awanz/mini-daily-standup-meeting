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
</script>
<?php $this->stop() ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Absensi Kehadiran Projects</h4>
                    <div>
                        <a href="<?= BASE_URL ?>/project/detail/<?= $project_id ?>" class="btn btn-primary my-2">Detail Project</a>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form action="<?= BASE_URL ?>/project/meeting-attendance/<?= $project_id ?>" method="POST">
                <div class="row mb-3">
                    <div class="col-md-3 mb-2">
                        <label for="datetimepicker" class="form-label">Tanggal dan Jam Meeting</label>
                        <div class="input-group">
                            <input type="text" id="datetimepicker" name="time_start" class="form-control" placeholder="Pick a date and time" required>
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="duration" class="form-label">Durasi (Dalam Satuan Menit)</label>
                        <div class="input-group">
                            <input type="text" name="duration" class="form-control" placeholder="Masukan durasi (dalam satuan menit)">
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="description" class="form-label">Keterangan</label>
                        <div class="input-group">
                        <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <?php foreach ($projectUsers as $key => $value) { ?>
                    <div class="row mb-3">
                        <input type="hidden" name="user_ids[]" value="<?= $value[0]; ?>">
                        <div class="col-md-2">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" value="<?= $value[1]; ?>" disabled required>
                        </div>
                        <div class="col-md-2">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" value="<?= $value[2]; ?>" disabled required>
                        </div>
                        <div class="col-md-2">
                            <label for="attendances" class="form-label">Kehadiran</label>
                            <select class="form-select" name="attendances[]" required>
                                <option value="ABSENT">Tidak Hadir</option>
                                <option value="PRESENT">Hadir</option>
                                <option value="SICK">Sakit</option>
                                <option value="PERMISSION">Izin</option>
                                <option value="NONE">Tidak Wajib Ikut</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="notes" class="form-label">Catatan</label>
                            <input type="text" class="form-control" name="notes[]" value="-" required>
                        </div>
                    </div>
                <?php } ?>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= BASE_URL ?>/project/detail/<?= $project_id ?>" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>