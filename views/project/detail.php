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
                        <a href="<?= BASE_URL ?>/project/edit/<?= $project->id ?>" class="btn btn-warning my-2">Edit Project</a>
                        <a href="<?= BASE_URL ?>/project" class="btn btn-dark my-2">List Project</a>
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
                        Hello Kawan
                    </div>
                    </div>
                </div>
                <!-- <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                        <b>Google Drive Folder Shortcut</b>
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                    <div class="accordion-body">
                        <iframe src="https://drive.google.com/embeddedfolderview?id=1XVM3PR4nnzYZlfeI9wL2AXNdGE7-GqNY#grid" width="100%" height="600" frameborder="0"></iframe>
                    </div>
                    </div>
                </div> -->
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
                    <h4>Riwayat Meeting</h4>
                    <?php if ($isAdmin || $isProjectManager): ?>
                    <div>
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
                                <a href="<?= BASE_URL ?>/project/member/note/<?= $value[11] ?>">Catatan</a> - 
                                <?php if ($value[10] == 'ACTIVED') { ?><a href="#" class="delete-btn" data-url="<?= BASE_URL ?>/project/nonactive-member/<?= $value[11] ?>">Nonactive</a><?php } ?>
                                <?php if ($value[10] == 'NONACTIVED') { ?><a href="#" class="active-btn" data-url="<?= BASE_URL ?>/project/active-member/<?= $value[11] ?>">Active</a><?php } ?>
                            </small>
                        </div>
                        <?php endif ?>
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
                    <h4>Daily Standup Meeting (5 Hari Ini Terakhir)</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
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

