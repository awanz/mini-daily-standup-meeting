<?php $this->layout('layouts/base') ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Ganti Password</h4>
                    <div>
                        <?= date('d M Y') ?>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/profile/change-password">
                <div class="form-group my-2">
                    <label for="password">Password Lama</label>
                    <input name="password" type="password" class="form-control" id="password" placeholder="Masukan password" required>
                </div>
                <div class="form-group my-2">
                    <label for="new_password">Password Baru</label>
                    <input name="new_password" type="password" class="form-control" id="new_password" placeholder="Masukan password baru" required>
                </div>
                
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/profile" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>