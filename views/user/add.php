<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
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
                    <label for="phone">No Whatsapp</label>
                    <input name="phone" type="text" class="form-control" id="phone" placeholder="Masukan phone">
                </div>
                <div class="form-group my-2">
                    <label for="date_start">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="date_start" name="date_start" required>
                </div>
                <!-- <div class="form-group my-2">
                    <label for="date_end">Tanggal Berakhir</label>
                    <input type="date" class="form-control" id="date_end" name="date_end" required>
                </div> -->
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
            </form>
        </div>
    </div>
</div>