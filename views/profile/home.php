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
<?php $this->stop() ?>
<div class="container">
    <?php if ($alert): ?>
        <div class="mt-2 alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
            <?= $alert['message'] ?>
        </div>
    <?php endif ?>
    <div class="row mt-4">
        <?php foreach ($projects as $key => $value) { ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card hover-lift hover-shadow-xl shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex mb-4 align-items-center">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <?php if (isset($value[3]) && $value[3] == 'NOT_STARTED') { ?>
                                <span class="badge bg-light text-dark mx-1">Belum Mulai</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'IN_PROGRESS') { ?>
                                <span class="badge bg-warning mx-1">Berjalan</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'COMPLETED') { ?>
                                <span class="badge bg-success mx-1">Selesai</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'FIXING') { ?>
                                <span class="badge bg-danger mx-1">Perbaikan</span>
                                <?php }elseif (isset($value[3]) && $value[3] == 'PUBLISH') { ?>
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
                        </div>
                    </div>
                    <h5><?= $value[1] ?></h5>
                    <p class="mb-0 text-muted"><?= $value[10] ?> Anggota Project</p>
                </div>
                <a href="<?= BASE_URL ?>/project/detail/<?= $value[0] ?>" class="stretched-link"></a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>