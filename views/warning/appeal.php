<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/warnings/appeal/<?=$this->e($warning->id ?? "")?>">
                <div class="form-group my-2">
                    <label for="fullname">Fullname</label>
                    <input type="text" class="form-control" id="fullname" disabled value="<?=$this->e($warning->fullname ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="role_name">Role</label>
                    <input type="text" class="form-control" id="role_name" disabled value="<?=$this->e($warning->role_name ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" disabled value="<?=$this->e($warning->email ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="counter">Type Pelanggaran</label>
                    <input type="text" class="form-control" id="counter" disabled value="<?= $warning->counter == 2 ? 'PEMECATAN' : 'PERINGATAN' ?>">
                </div>
                <div class="form-group my-2">
                    <label for="add_time">Penambahan Waktu (Dalam Pekan)</label>
                    <input name="add_time" type="number" class="form-control" id="add_time" value="2">
                </div>
                <div class="form-group my-2">
                    <label for="note">Catatan</label>
                    <textarea class="form-control" name="note" rows="3"></textarea>
                </div>
                <?php if (empty($user->date_end)): ?>
                <p style="color: red;">Banding tidak dapat dilakukan, Tanggal mulai magang belum diatur di profile, silahkan atur terlebih dahulu.</p>
                <?php endif ?>
                <?php if (empty($warning->is_appeal) && isset($user->date_end)): ?>
                <button type="submit" class="btn btn-dark my-2">Banding</button>
                <?php endif ?>
            </form>
        </div>
    </div>
</div>