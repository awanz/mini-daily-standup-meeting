<?php $this->layout('layouts/base', ['title' => $siteTitle]) ?>

<?php $this->start('headAdditional') ?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<style>
body{
    background:#eee;
}

.img-fluid {
    max-width: 100%;
    height: auto;
}

.height-5x {
    height: 3rem!important;
}
.width-5x {
    width: 3rem!important;
}
.flex-center {
    justify-content: center!important;
}
.flex-center {
    align-items: center!important;
}
.rounded-circle {
    border-radius: 50%!important;
}

.align-items-center {
    align-items: center!important;
}
.flex-grow-1 {
    flex-grow: 1!important;
}
.d-flex {
    display: flex!important;
}

.position-relative {
    position: relative!important;
}
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(13,15,12,.075)!important;
}

.ms-n3 {
    margin-left: -1rem!important;
}
</style>
<?php $this->stop() ?>

<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#meetingTable', {
        pageLength: 25,
        lengthMenu: [5, 10, 25, 50, 100, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength']
            }
        },
        order: [[1, 'desc']],
    });
</script>
<?php $this->stop() ?>

<div class="container">
    <div class="mt-4 p-5 bg-dark text-white rounded">
    <h1>Daily</h1>
    <p>Welcome to Kawan Kerja Daily</p>
    </div>
</div>

<div class="container">
    <?php if ($alert): ?>
        <div class="mt-2 alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
            <?= $alert['message'] ?>
        </div>
    <?php endif ?>
    <?php if (count($projects) > 0) { ?>
    <div class="row mt-4">
        <?php foreach ($projects as $key => $value) { ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card hover-lift hover-shadow-xl shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex mb-4 align-items-center">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <?php if (isset($value[2]) && $value[2] == 'NOT_STARTED') { ?>
                                <span class="badge bg-light text-dark mx-1">Belum Mulai</span>
                                <?php }elseif (isset($value[2]) && $value[2] == 'IN_PROGRESS') { ?>
                                <span class="badge bg-warning mx-1">Berjalan</span>
                                <?php }elseif (isset($value[2]) && $value[2] == 'COMPLETED') { ?>
                                <span class="badge bg-success mx-1">Selesai</span>
                                <?php }elseif (isset($value[2]) && $value[2] == 'FIXING') { ?>
                                <span class="badge bg-danger mx-1">Perbaikan</span>
                                <?php }elseif (isset($value[2]) && $value[2] == 'PUBLISH') { ?>
                                <span class="badge bg-primary mx-1">Diterbitkan</span>
                            <?php } ?>
                            <?php if (isset($value[4]) && $value[4] == 'WEB') { ?>
                                <span class="badge bg-info mx-1">Website</span>
                                <?php }elseif (isset($value[4]) && $value[4] == 'MOBILE') { ?>
                                <span class="badge bg-warning mx-1">Mobile</span>
                                <?php }elseif (isset($value[4]) && $value[4] == 'MEDSOS') { ?>
                                <span class="badge bg-success mx-1">Media Social</span>
                                <?php }elseif (isset($value[4]) && $value[4] == 'GAME') { ?>
                                <span class="badge bg-primary mx-1">Game</span>
                                <?php }elseif (isset($value[4]) && $value[4] == 'ANIMASI') { ?>
                                <span class="badge bg-warning mx-1">Animasi</span>
                                <?php }elseif (isset($value[4]) && $value[4] == 'MAJALAH') { ?>
                                <span class="badge bg-dark mx-1">Majalah</span>
                            <?php } ?>
                            <?php if (isset($value[3]) && $value[3] == 'ACTIVED') { ?>
                                <span class="badge bg-success">Aktif</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'NONACTIVED') { ?>
                                <span class="badge bg-danger">Tidak Aktif</span>
                                <?php }elseif (empty($value[3])) { ?>
                                <span class="badge bg-dark text-light">Belum Bergabung</span>
                            <?php } ?>
                            <?php if ($value[6] == 1) { ?><span class="mx-1 badge bg-danger">P2X</span><?php } ?>
                            <?php if ($value[7] == 1) { ?><span class="mx-1 badge bg-primary text-white">A1X</span><?php } ?>
                        </div>
                    </div>
                    <h5><?= $value[1] ?></h5>
                    <p class="mb-0 text-muted"><?= $value[5] ?> Anggota Project</p>
                </div>
                <a href="<?= BASE_URL ?>/project/detail/<?= $value[0] ?>" class="stretched-link"></a>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>

<?php if (count($meetings) > 0) { ?>
<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Riwayat Kehadiran Meeting</h4>
                </div>
            </h5>
            <div class="table-responsive">
                <table id="meetingTable" class="table table-striped table-bordered">
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
                                <?php if ($meeting[5] == 'MEETING_PROJECT') {?>
                                <a href="<?= BASE_URL ?>/project/meeting-attendance-detail/<?= $meeting[0] ?>"><?=$this->e($meeting[1])?></a>
                                <?php } ?>
                                <?php if ($meeting[5] == 'MEETING_ROLE') {?>
                                <a href="<?= BASE_URL ?>/role/meeting-attendance-detail/<?= $meeting[0] ?>"><?=$this->e($meeting[1])?></a>
                                <?php } ?>
                            </td>
                            <td><?=$this->e($meeting[2])?></td>
                            <td><?=$this->e($meeting[3])?></td>
                            <td><?=$this->e($meeting[4])?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>