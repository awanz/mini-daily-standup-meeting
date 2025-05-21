<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#warningTable', {
        pageLength: 50,
        lengthMenu: [50, 100, 1000],
        order: [[0, 'desc']],
    });
    new DataTable('#history', {
        pageLength: 31,
        lengthMenu: [5, 10, 25, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'desc']],
    });
    new DataTable('#projectTable', {
        pageLength: 31,
        lengthMenu: [5, 10, 25, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'asc']],
    });
    new DataTable('#tableAttendance', {
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[2, 'desc']],
    });
</script>
<?php $this->stop() ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>User Detail</h4>
                    <div>
                        <a href="<?= BASE_URL ?>/user" class="btn btn-dark my-2">List User</a>
                        <a href="<?= BASE_URL ?>/user/edit/<?= $user->id ?>" class="btn btn-warning my-2">Edit User</a>
                    </div>
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
                        <div class="form-group my-2">
                            <label for="date_end">Catatan</label><br>
                            <?= $this->e($user->notes ?? "") ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group my-2">
                            <label for="Role">Status</label>
                            <p><?= $this->e($user->status ?? "") ?> (<?= $this->e($user->is_active ?? "") ?>)</p>
                        </div>
                        <div class="form-group my-2">
                            <label for="Role">Last login</label>
                            <p><?= $this->e($user->last_login_at ?? "") ?></p>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Contract Extends</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="contractExtendTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Submit Date</th>
                            <th>Duration</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = $contractExtends->fetch_object()) { ?>
                        <tr>
                            <td><?= $data->created_at ?></td>
                            <td><?= $data->duration ?> Bulan</td>
                            <td><?= $data->description ?></td>
                            <td><?= $data->status ?></td>
                            <td>
                                <?= $data->approval_date ?> - <?= $data->approval_id ?>
                            </td>
                            
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Resign</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="contractExtendTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Submit Date</th>
                            <th>File</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = $employeeResigns->fetch_object()) { ?>
                        <tr>
                            <td><?= $data->created_at ?></td>
                            <td><a href="<?= $data->file_resign ?>" target="_BLANK">File Resign</a></td>
                            <td><?= $data->reason ?></td>
                            <td><?= $data->status ?></td>
                            <td>
                                <?= $data->approval_date ?> - <?= $data->approval_id ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Sertifikat</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="contractExtendTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Submit Date</th>
                            <th>File</th>
                            <th>Survey</th>
                            <th>Status</th>
                            <th>Certificate</th>
                            <th>Approval</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = $employeeIntershipFinalizations->fetch_object()) { ?>
                        <tr>
                            <td><?= $data->created_at ?></td>
                            <td><a href="<?= $data->file ?>" target="_BLANK">File Laporan Magang</a></td>
                            <td><?= $data->is_survey == 1 ? '<a href="https://docs.google.com/spreadsheets/d/1YheInax1JRz1Ljya3KdWZ5gH_BG18_apLG95o-0oFKc/edit?gid=1420681690#gid=1420681690" target="_BLANK">Sudah</a>' : 'Belum' ?></td>
                            <td><?= $data->status ?></td>
                            <td><?= $data->certificate ?></td>
                            <td>
                                <?= $data->approval_date ?> - <?= $data->approval_id ?>
                            </td>
                            <td>
                                <?php if (isset($data->certificate)) {  ?>
                                <form method="POST" action="<?= BASE_URL ?>/hr/certificate-print/print">
                                    <input type="hidden" name="id" value="<?= $data->id ?>">
                                    <button type="submit" class="btn btn-outline-dark mx-1 btn-sm">Cetak</button>
                                </form>
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

<?php if (count($projects) > 0) { ?>
<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Projects</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="projectTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Project</th>
                            <th>Status Keikutsertaan</th>
                            <th>Status Project</th>
                            <th>Type</th>
                            <th>Grup WA</th>
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
                                <?php if (!empty($value[4])) { ?>
                                <a target="_BLANK" href="<?= $value[4] ?>" class="btn btn-success my-1">JOIN WA</a>
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
<?php } ?>


<?php if (count($warnings) > 0) { ?>
<div class="xxx">
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
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($warnings as $key => $value) { ?>
                        <tr>
                            <td><?= $value[7] ?></td>
                            <td><?= $value[2] ?></td>
                            <td>
                                <?= $value[3] == 2 ? '<span class="badge bg-danger">PEMECATAN</span>' : '<span class="badge bg-warning">PERINGATAN</span>' ?>
                            </td>
                            <td><?= $value[5] ?></td>
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
<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Riwayat Kehadiran Meeting</h4>
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
                                echo '<span class="text-primary">Sakit</span>';
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

<?php if (count($dailys) > 0) { ?>
<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Riwayat Daily</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="history" class="table table-striped table-bordered">
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