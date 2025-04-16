<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        const url = $(this).data('url');

        Swal.fire({
            title: "Yakin untuk melakukan hapus?",
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
    $(document).on('click', '.active-btn', function (e) {
        e.preventDefault();
        const url = $(this).data('url');

        Swal.fire({
            title: "Yakin untuk melakukan aktifasi?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Active",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
    $(document).ready(function () {
        $('.copyEmail').on('click', function () {
            const emailText = $(this).siblings('.emailText').text();
            navigator.clipboard.writeText(emailText)
                .then(() => {
                    alert('Email copied to clipboard: ' + emailText);
                })
                .catch((err) => {
                    alert('Failed to copy email: ' + err);
                });
        });

        $('.copyPhone').on('click', function () {
            const phoneText = $(this).siblings('.phoneText').text();
            navigator.clipboard.writeText(phoneText)
                .then(() => {
                    alert('Phone copied to clipboard: ' + phoneText);
                })
                .catch((err) => {
                    alert('Failed to copy phone: ' + err);
                });
        });
    });
    new DataTable('#tableMeetings', {
        pageLength: 15,
        lengthMenu: [10, 15, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[1, 'desc']],
    });
    new DataTable('#tableDailys', {
        pageLength: 20,
        lengthMenu: [10, 20, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'desc']],
    });
    new DataTable('#tableMembers', {
        pageLength: 20,
        lengthMenu: [10, 20, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'asc']],
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
                    <h4>Detail Projects <?= $project->name ?></h4>
                    <?php if ($isAdmin || $isProjectManager): ?>
                    <div>
                        <a href="<?= BASE_URL ?>/project" class="btn btn-dark my-2">List Project</a>
                        <a href="<?= BASE_URL ?>/project/edit/<?= $project->id ?>" class="btn btn-warning my-2">Edit Project</a>
                    </div>
                    <?php endif ?>
                </div>
            </h5>
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                        <b>Deskripsi Project</b>
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                    <div class="accordion-body">
                        <?php if (!empty($project->description)) { ?>
                            <?=$this->e($project->description ?? "")?>
                        <?php }else { ?>
                            Hello Kawan
                        <?php } ?>
                    </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                        <b>Detail Project</b>
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                    <div class="accordion-body">
                        <!-- detail -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group my-2">
                                    <label for="name">Name</label>
                                    <input disabled name="name" type="text" class="form-control" id="name" placeholder="Masukan nama project" value="<?=$this->e($project->name ?? "")?>">
                                </div>                           
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group my-2">
                                    <label for="note">Deskripsi</label><br>
                                    <?php if (!empty($project->description)) { ?>
                                    <?=$this->e($project->description ?? "")?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group my-2">
                                    <label for="status">Status</label>
                                    <select disabled class="form-select" name="status" required>
                                        <option selected>-- Pilih Status --</option>
                                        <option <?php if("NOT_STARTED" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="NOT_STARTED">Not Started</option>
                                        <option <?php if("IN_PROGRESS" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="IN_PROGRESS">In Progress</option>
                                        <option <?php if("COMPLETED" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="COMPLETED">Completed</option>
                                        <option <?php if("FIXING" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="FIXING">Fixing</option>
                                        <option <?php if("PUBLISH" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="PUBLISH">Publish</option>
                                        <option <?php if("PENDING" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="PENDING">Pending</option>
                                        <option <?php if("MAINTENANCE" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="MAINTENANCE">Maintenance</option>
                                        <option <?php if("CANCEL" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="CANCEL">Cancel</option>
                                    </select>
                                </div>                      
                            </div>
                            <div class="col-md-6">
                                <div class="form-group my-2">
                                    <label for="type">Type</label>
                                    <select disabled class="form-select" name="type" required>
                                        <option selected>-- Pilih Type --</option>
                                        <option <?php if("WEB" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="WEB">WEB</option>
                                        <option <?php if("MOBILE" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="MOBILE">MOBILE</option>
                                        <option <?php if("MEDSOS" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="MEDSOS">MEDSOS</option>
                                        <option <?php if("GAME" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="GAME">GAME</option>
                                        <option <?php if("ANIMASI" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="ANIMASI">ANIMASI</option>
                                        <option <?php if("MAJALAH" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="MAJALAH">MAJALAH</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group my-2">
                                    <label for="note">Catatan</label>
                                    <textarea disabled class="form-control" name="note" id="note" rows="4"><?=$this->e($project->note ?? "")?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php if (!empty($project->url_drive)) { ?>
                                    <a target="_BLANK" href="<?= $project->url_drive ?>" class="btn btn-primary my-1">Google Drive</a>
                                <?php } ?>
                                <?php if (!empty($project->url_figma)) { ?>
                                    <a target="_BLANK" href="<?= $project->url_figma ?>" class="btn btn-danger my-1">Figma</a>
                                <?php } ?>
                                <?php if (!empty($project->url_logo)) { ?>
                                    <a target="_BLANK" href="<?= $project->url_logo ?>" class="btn btn-info text-white my-1">Logo</a>
                                <?php } ?>
                                <?php if (!empty($project->url_repo)) { ?>
                                    <a target="_BLANK" href="<?= $project->url_repo ?>" class="btn btn-dark my-1">Repository</a>
                                <?php } ?>
                                <?php if (!empty($project->url_group_wa)) { ?>
                                    <a target="_BLANK" href="<?= $project->url_group_wa ?>" class="btn btn-success my-1">Whatsapp Group</a>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- detail -->
                    </div>
                    </div>
                </div>

                <?php if ($project->embeded): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                        <b>Embeded Shortcut</b>
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                    <div class="accordion-body">
                        <?= $project->embeded ?>
                    </div>
                    </div>
                </div>
                <?php endif ?>
                <!-- <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-meetingsColls">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseMeetingsColls" aria-expanded="false" aria-controls="panelsStayOpen-collapseMeetingsColls">
                            <b>Log Meetings</b>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseMeetingsColls" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-meetingsColls">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table id="history" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal</th>
                                        <th>Waktu Mulai</th>
                                        <th>Waktu Berakhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($meetings as $meeting): ?>
                                    <tr>
                                        <td><?=$this->e($meeting[1])?></td>
                                        <td><?=$this->e($meeting[2])?></td>
                                        <td><?=$this->e($meeting[3])?></td>
                                        <td><?=$this->e($meeting[4])?></td>
                                        <td><?=$this->e($meeting[5])?></td>
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Riwayat Kehadiran Meeting</h4>
                    <?php if ($isAdmin || $isProjectManager): ?>
                    <div>
                        <a href="<?= BASE_URL ?>/project/meeting-attendance/download/<?= $id ?>" class="btn btn-primary my-2">Download</a>
                        <a href="<?= BASE_URL ?>/project/meeting-attendance/<?= $id ?>" class="btn btn-success my-2">Absensi</a>
                    </div>
                    <?php endif ?>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="tableMeetings" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Waktu Mulai</th>
                        <th>Waktu Berakhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($meetings as $meeting): ?>
                    <tr>
                        <td>
                            <a href="<?= BASE_URL ?>/project/meeting-attendance-detail/<?= $meeting[0] ?>"><?=$this->e($meeting[1])?></a>
                        </td>
                        <td><?=$this->e($meeting[3])?></td>
                        <td><?=$this->e($meeting[4])?></td>
                        <td><?=$this->e($meeting[5])?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Anggota Projects <?= $project->name ?></h4>
                    <?php if ($isAdmin || $isProjectManager): ?>
                    <div>
                        <a href="<?= BASE_URL ?>/project/add-member/<?= $id ?>" class="btn btn-dark my-2">Tambah Anggota</a>
                    </div>
                    <?php endif ?>
                </div>
            </h5>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Anggota Grid</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Anggota List</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Anggota Keseluruhan</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php foreach ($users as $key => $value) {?>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= $value[1] ?> 
                                        <?php if ($value[10] == 'ACTIVED') { ?><small class="badge bg-primary rounded-pill">Aktif</small><?php } ?>
                                        <?php if ($value[10] == 'NONACTIVED') { ?><small class="badge bg-danger rounded-pill">Tidak Aktif</small><?php } ?>
                                    </h5>
                                    <h6><?= $value[7] ?></h6>
                                    <p class="card-text">
                                        E-mail: 
                                        <span class="emailText"><?= htmlspecialchars($value[2]) ?></span>
                                        <?php if (!empty($value[2])) { ?>
                                            <span class="pe-auto badge bg-dark copyEmail" style="cursor: pointer;">Copy</span>
                                        <?php } else { echo "-"; } ?><br>
                                        No HP: 
                                        <?php if (!empty($value[3])) { ?>
                                            <span class="phoneText"><?= htmlspecialchars($value[3]) ?></span>
                                            <a href="https://wa.me/<?= htmlspecialchars($value[3]) ?>" target="_blank" class="pe-auto badge bg-success" style="cursor: pointer;">Chat</a>
                                            <span class="pe-auto badge bg-dark copyPhone" style="cursor: pointer;">Copy</span>
                                        <?php } else { echo "-"; } ?><br>
                                        <span class="<?php if ($value[8]) { ?>text-dark bg-warning<?php } ?>">
                                            Kontrak: <?= $value[4] ?> - <?= $value[5] ?><br>
                                        </span>
                                        Catatan: <?= $value[9] ?><br>
                                    </p>

                                </div>
                                <?php if ($isAdmin || $isProjectManager): ?>
                                <div class="card-footer d-flex justify-content-between">
                                    <small class="text-muted">Total Daily Bulan Ini: <a href="<?= BASE_URL ?>/history/<?= $value[2] ?>"><?= $value[6] == 99 ? '' : $value[6] ?></a> </small>
                                    <small class="text-muted">
                                        <a href="<?= BASE_URL ?>/project/detail/note/<?= $value[11] ?>">Catatan</a>  
                                        <?php if ($value[10] == 'ACTIVED') { ?><a href="#" class="delete-btn" data-url="<?= BASE_URL ?>/project/nonactive-member/<?= $value[11] ?>">Nonactive</a><?php } ?> 
                                        <?php if ($value[10] == 'NONACTIVED' && !empty($value[12])) { ?><a href="#" class="active-btn" data-url="<?= BASE_URL ?>/project/active-member/<?= $value[11] ?>">Active</a><?php } ?> 
                                    </small>
                                </div>
                                <?php endif ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="table-responsive">
                        <table id="tableMembers" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Kontrak Berakhir</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach($users as $user): ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <th><?=$this->e($user[1])?></th>
                                    <td><?=$this->e($user[7])?></td>
                                    <td><?=$this->e($user[2])?></td>
                                    <td style="text-align: left;"><a href="https://wa.me/<?= htmlspecialchars($user[3]) ?>" target="_blank"><?=$this->e($user[3])?></a></td>
                                    <td><?=$this->e($user[5])?></td>
                                    <td><?= $user[10] == 'ACTIVED' ? 'Aktif' : 'Tidak aktif' ?></td>
                                    <td><?= !empty($user[9]) ? $user[9] : '-' ?>
                                        <a href="<?= BASE_URL ?>/project/detail/note/<?= $value[11] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <?php $no++; endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
                    <div class="table-responsive">
                        <table id="tableMembers" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach($projectUsersAll as $user): ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/user/detail/<?= $user[7] ?>" target="_BLANK"><?=$this->e($user[1])?></a>    
                                    </td>
                                    <td><?=$this->e($user[2])?></td>
                                    <td><?=$this->e($user[5])?></td>
                                    <td>
                                        <?php if ($user[3] == 'ACTIVED') { ?><a href="#" class="delete-btn" data-url="<?= BASE_URL ?>/project/nonactive-member/<?= $user[0] ?>">Aktif</a><?php } ?> 
                                        <?php if ($user[3] == 'NONACTIVED') { ?>Tidak aktif<?php } ?> 
                                    </td>
                                    <td>
                                        <?=$this->e($user[4])?>
                                        <a href="<?= BASE_URL ?>/project/detail/note/<?= $user[0] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <?php $no++; endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
            </div>
            
        </div>
    </div>
</div>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Daily Standup Meeting (10 Hari Terakhir)</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="tableDailys" class="table table-striped table-bordered">
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

