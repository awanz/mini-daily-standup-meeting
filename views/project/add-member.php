<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Anggota Projects</h4>
                    <div>
                        <a href="<?= BASE_URL ?>/project/member/<?= $id ?>" class="btn btn-dark my-2">List Member</a>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/project/add-member/<?= $id ?>">
                <div class="form-group my-2">
                    <label for="user_id">User</label>
                    <select class="form-select" name="user_id" required>
                        <option selected>-- Pilih Users --</option>
                        <?php foreach($users as $user): ?>
                        <option value="<?= $user[0] ?>"><?= $user[1] ?> <?= $user[2] ? '('.$user[2].')' : '' ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/project/member/<?= $id ?>" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>