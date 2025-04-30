<?php $this->layout('layouts/base') ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Tambah User</h4>
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
            <form method="POST" action="<?= BASE_URL ?>/user/add">
                <div class="form-group my-2">
                    <label for="Role">Role</label>
                    <select class="form-select" name="role_id" required>
                        <option selected>-- Pilih Type --</option>
                        <?php foreach($roles as $role): ?>
                        <option value="<?= $role[0] ?>"><?= $role[1] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="fullname">Fullname</label>
                    <input name="fullname" type="text" class="form-control" id="fullname" placeholder="Masukan fullname" required>
                </div>
                <div class="form-group my-2">
                    <label for="email">Email address</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="Masukan email" required>
                </div>
                <div class="form-group my-2">
                    <label for="phone">No Whatsapp (Mulai dengan 62 tanpa simbol +)</label>
                    <input name="phone" type="text" class="form-control" id="phone" placeholder="6285000000000" required>
                </div>
                <div class="form-group my-2">
                    <label for="date_start">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="date_start" name="date_start" required>
                </div>
                <div class="form-group my-2">
                    <label for="duration">Durasi Magang <b>(Secara Default Minimal 5 Bulan)</b></label>
                    <input type="text" class="form-control" id="duration" name="duration" value="5" required>
                </div>
                <!-- <div class="form-group my-2">
                    <label for="date_end">Tanggal Berakhir</label>
                    <input type="date" class="form-control" id="date_end" name="date_end" required>
                </div> -->
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/user" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>