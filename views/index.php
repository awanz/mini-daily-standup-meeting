<?php $this->layout('layouts/base', ['title' => $siteTitle]) ?>

<?php $this->start('footAdditional') ?>
<script>
    function changeUrl() {
        const dateInput = document.getElementById('tanggal').value;
        const currentUrl = window.location.origin + window.location.pathname;
        
        window.location.href = `<?= BASE_URL ?>` + '/home/' + dateInput;
    }
</script>
<?php $this->stop() ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Lapor Daily</h4>
                    <div><?= $this->e($date) ?></div>
                </div>
            </h5>
            <p class="card-text">Hello, <b><?=$this->e($dataUser->fullname)?></b> <u>(<?=$this->e($dataUser->email)?>)</u></p>
            <h4 class="text-dark">[Bisa baca?]</h4>
            <h6 class="card-text">Pastikan email yang tercantum benar, karna info maupun teguran akan dikirim melalui e-mail.</h6>
            <h6 class="card-text">Setiap bulan minimal mengisi 15 kali daily standup meeting, meskipun SAKIT, IZIN wajib isi.</h6>
            <hr>
            <?php if ($alert): ?>
            <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                <?= $alert['message'] ?>
            </div>
            <?php endif ?>
            <form action="" method="GET" class="my-1 d-flex" id="dateForm">
                <input type="date" class="form-control w-50 w-md-25 w-lg-25" id="tanggal" name="tanggal" 
                    value="<?= isset($date) ? $date : date('Y-m-d') ?>" 
                    max="<?= date('Y-m-d') ?>" 
                    min="<?= date('Y-m-d', strtotime('-30 day')) ?>">
                <input type="button" class="btn btn-dark mx-2" value="Ubah" onclick="changeUrl()">
            </form>
            <p>
                <small>
                    <i>
                        *tanggal hanya bisa dirubah <u>30 Hari</u> sebelum. Pastikan tanggal disamping kanan berubah, jika tidak berubah klik tombol <u>ubah</u>
                    </i>
                </small>
                
            </p>
            <form action="<?= BASE_URL ?>/home" method="post">
                <div class="form-group mb-2">
                    <label for="yesterday" class="form-label">Apa yang kamu kerjakan kemarin?</label>
                    <textarea class="form-control" name="yesterday" rows="3"><?=$this->e($daily->yesterday ?? "")?></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="today" class="form-label">Apa yang akan kamu kerjakan hari ini?</label>
                    <textarea class="form-control" name="today" rows="3"><?=$this->e($daily->today ?? "")?></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="problem" class="form-label">Apakah ada kendala yang kamu hadapi?</label>
                    <textarea class="form-control" name="problem" rows="3"><?=$this->e($daily->problem ?? "")?></textarea>
                </div>
                <input type="hidden" name="date_activity" value="<?= $this->e($date) ?>">
                <h6 class="card-subtitle mb-4 text-danger"><small><i>*Mohon isi semua bagian, jika lupa isi atau salah isi bisa info ke <u>Project Manager/Koordinator</u></i></small></h6>
                <?php if (is_null($daily)): ?>
                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-dark mt-2">Lapor!</button>
                    </div>
                </div>
                <?php endif ?>
            </form>
        </div>
    </div>
</div>