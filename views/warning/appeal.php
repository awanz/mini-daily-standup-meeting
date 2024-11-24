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
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" id="email" disabled placeholder="Masukan email" value="<?=$this->e($warning->email ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="counter">Type Pelanggaran</label>
                    <input name="counter" type="text" class="form-control" id="counter" disabled value="<?=$this->e($warning->counter ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="add_time">Penambahan Waktu (Dalam Pekan)</label>
                    <input name="add_time" type="number" class="form-control" id="add_time" value="2">
                </div>
                <div class="form-group my-2">
                    <label for="note">Catatan</label>
                    <textarea class="form-control" name="note" rows="3"></textarea>
                </div>
                <?php if (empty($warning->is_appeal)): ?>
                <button type="submit" class="btn btn-dark my-2">Banding</button>
                <?php endif ?>
            </form>
        </div>
    </div>
</div>