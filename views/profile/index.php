<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#warningTable', {
        pageLength: 50,
        lengthMenu: [50, 100, 1000],
        order: [[0, 'desc']],
    });
    new DataTable('#tableAttendance', {
        pageLength: 7,
        lengthMenu: [5, 10, 25, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'desc']],
    });
    new DataTable('#tableDaily', {
        pageLength: 31,
        lengthMenu: [5, 10, 25, 31, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'desc']],
    });
    new DataTable('#projectTable', {
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength']
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
                    <h4>Profile</h4>
                    <span><a href="<?= BASE_URL ?>/profile/change-password" class="btn btn-dark">Ganti Password</a></span>
                </div>
            </h5>
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group my-2">
                            <label for="Role">Role</label>
                            <select class="form-select" name="role_id" disabled>
                                <option selected>-- Pilih Type --</option>
                                <?php foreach ($roles as $role): ?>
                                <option <?php if ($role[0] == $this->e($user->role_id ?? "")) { echo 'selected'; } ?> value="<?= $role[0] ?>"><?= $role[1] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group my-2">
                            <label for="fullname">Fullname</label>
                            <input name="fullname" type="text" class="form-control" id="fullname" placeholder="Masukan fullname" value="<?= $this->e($user->fullname ?? "") ?>" disabled>
                        </div>
                        <div class="form-group my-2">
                            <label for="date_start">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="date_start" name="date_start" value="<?= $this->e($user->date_start ?? "") ?>" disabled>
                        </div>
                        <div class="form-group my-2">
                            <label for="phone">No Whatsapp</label>
                            <input name="phone" type="text" class="form-control" id="phone" placeholder="Masukan phone" value="<?= $this->e($user->phone ?? "") ?>" disabled>
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group my-2">
                            <label for="Role">Hak Akses</label>
                            <input type="text" class="form-control" value="<?= $this->e($user->access ?? "") ?>" disabled>
                        </div>
                        <div class="form-group my-2">
                            <label for="email">Email address</label>
                            <input name="email" type="email" class="form-control" id="email" placeholder="Masukan email" value="<?= $this->e($user->email ?? "") ?>" disabled>
                        </div>
                        <div class="form-group my-2">
                            <label for="date_end">Tanggal Berakhir</label>
                            <input type="date" class="form-control" id="date_end" name="date_end" value="<?= $this->e($user->date_end ?? "") ?>" disabled>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<?php if (count($projects) > 0) { ?>
<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Projects</h4>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="mt-2 alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <div class="table-responsive">
                <table id="projectTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Project</th>
                            <th>Status Keikutsertaan</th>
                            <th>Status Project</th>
                            <th>Type</th>
                            <th>Perlengkapan</th>
                            <th>Grup WA</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $key => $value) { ?>
                        <tr>
                            <td><?= $value[0] ?></td>
                            <td>
                                <?php if (isset($value[1]) && $value[1] == 'ACTIVED') { ?>
                                <span class="badge bg-success">Aktif</span>
                                <?php }elseif (isset($value[1]) && $value[1] == 'NONACTIVED') { ?>
                                <span class="badge bg-danger">Tidak Aktif</span>
                                <?php }elseif (empty($value[1])) { ?>
                                <span class="badge bg-dark text-light">Belum Bergabung</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (isset($value[2]) && $value[2] == 'NOT_STARTED') { ?>
                                <span class="badge bg-light text-dark">Belum Mulai</span>
                                <?php }elseif (isset($value[2]) && $value[2] == 'IN_PROGRESS') { ?>
                                <span class="badge bg-warning">Berjalan</span>
                                <?php }elseif (isset($value[2]) && $value[2] == 'COMPLETED') { ?>
                                <span class="badge bg-success">Selesai</span>
                                <?php }elseif (isset($value[2]) && $value[2] == 'FIXING') { ?>
                                <span class="badge bg-danger">Perbaikan</span>
                                <?php }elseif (isset($value[2]) && $value[2] == 'PUBLISH') { ?>
                                <span class="badge bg-primary">Diterbitkan</span>
                                <?php } ?>
                            </td>
                            <td><?= $value[3] ?></td>
                            <td>
                                <?php if ((!empty($value[4]) && $value[1] == 'ACTIVED') || $isAdmin) { ?>
                                    <?php if (!empty($value[5])) { ?>
                                    <a target="_BLANK" href="<?= $value[5] ?>" class="btn btn-primary my-1">GDrive</a>
                                    <?php } ?>
                                    <?php if (!empty($value[6])) { ?>
                                    <a target="_BLANK" href="<?= $value[6] ?>" class="btn btn-danger my-1">Figma</a>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if ((!empty($value[4]) && $value[1] == 'ACTIVED') || $isAdmin) { ?>
                                <a target="_BLANK" href="<?= $value[4] ?>" class="btn btn-success my-1">JOIN WA</a>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/project/detail/<?= $value[7] ?>" class="btn btn-dark my-1">Detail</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>


<?php if (count($warnings) > 0) { ?>
<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4 class="bg-danger text-white p-2">Pelanggaran</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="warningTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Email</th>
                            <th>Jenis Peringatan</th>
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
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if (count($meetings) > 0) { ?>
<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Riwayat Kehadiran</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="tableAttendance" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Judul Meeting</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Dari Jam</th>
                        <th>Sampai Jam</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($meetings as $meeting): ?>
                    <tr>
                        <td><?=$this->e($meeting[0])?></td>
                        <td>
                        <?php 
                        switch ($meeting[1]) {
                            case 'ABSENT':
                                echo '<span class="text-danger">Tidak hadir</span>';
                                break;
                            case 'PRESENT':
                                echo '<span class="text-success">Hadir</span>';
                                break;
                            case 'SICK':
                                echo '<span class="text-info">Sakit</span>';
                                break;
                            case 'PERMISSION':
                                echo '<span class="text-warning">Izin</span>';
                                break;
                            default:
                                echo "Tidak hadir";
                                break;
                        } 
                        ?></td>
                        <td><?= $meeting[2] ?></td>
                        <td><?= $meeting[3] ?></td>
                        <td><?= $meeting[4] ?></td>
                        <td><?= $meeting[5] ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if (count($dailys) > 0 && !$isAdmin && !$isProjectManager) { ?>
<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Riwayat Daily Standup Meeting</h4>
                    <span><a href="<?= BASE_URL ?>/home" class="btn btn-dark">Lapor</a></span>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="tableDaily" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Aktifitas Kemarin</th>
                        <th>Hari ini</th>
                        <th>Permasalahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dailys as $daily): ?>
                    <tr>
                        <td><?=$this->e($daily[2])?></td>
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
<?php } ?>