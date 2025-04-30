<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#tableMeetings', {
        pageLength: 15,
        lengthMenu: [10, 15, 50, 1000],
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
</script>
<?php $this->stop() ?>

<div class="xxx">
    <?php if ($alert): ?>
        <div class="my-2 alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
            <?= $alert['message'] ?>
        </div>
    <?php endif ?>
</div>
<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Detail Role <?= $role->name ?></h4>
                    <?php if ($isAdmin): ?>
                    <div>
                        <a href="<?= BASE_URL ?>/role/edit/<?= $role->id ?>" class="btn btn-warning my-2">Edit Role</a>
                        <a href="<?= BASE_URL ?>/role" class="btn btn-dark my-2">List Role</a>
                    </div>
                    <?php endif ?>
                </div>
            </h5>
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                        <b>Deskripsi Role</b>
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                    <div class="accordion-body">
                        Hello Kawan
                    </div>
                    </div>
                </div>
                <?php if ($role && $role->embeded): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                        <b>Embeded Shortcut</b>
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                    <div class="accordion-body">
                        <?= $role->embeded ?>
                    </div>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Riwayat Kehadiran <?= $role->name ?></h4>
                    <?php if ($isAdmin): ?>
                    <div>
                        <a href="<?= BASE_URL ?>/role/meeting-attendance/<?= $role->id ?>" class="btn btn-success my-2">Absensi</a>
                    </div>
                    <?php endif ?>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="tableMeetings" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Waktu Mulai</th>
                        <th>Waktu Berakhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($meetings as $meeting): ?>
                    <tr>
                        <td><?=$this->e($meeting[3])?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/role/meeting-attendance-detail/<?= $meeting[0] ?>"><?=$this->e($meeting[1])?></a>
                        </td>
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

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Anggota Role <?= $role->name ?></h4>
                    
                </div>
            </h5>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Anggota Grid</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Anggota List</button>
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
                                        <a href="<?= BASE_URL ?>/user/detail/<?= $value[0] ?>"><?= $value[1] ?> </a>
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
                                        Total Daily Bulan ini: <?= $value[6] ?><br>
                                        Projects: <?= $value[10] ?><br>
                                    </p>

                                </div>
                                <?php if ($isAdmin): ?>
                                <div class="card-footer d-flex justify-content-between">
                                    <small class="text-muted">Last login <?= $value[9] ?></small>
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
                                    <th>Project</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach($users as $user): ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <th><?=$this->e($user[1])?></th>
                                    <td><?=$this->e($user[10])?></td>
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