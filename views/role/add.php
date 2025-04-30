<?php $this->layout('layouts/base') ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/role/add">
                <div class="form-group my-2">
                    <label for="name">Name</label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Masukan nama role" required>
                </div>
                <div class="form-group my-2">
                    <label for="role_code">Role Code</label>
                    <input name="role_code" type="text" class="form-control" id="role_code" placeholder="Masukan code role" required>
                </div>
                <div class="form-group my-2">
                    <label for="url_group_wa">URL Whatsapp</label>
                    <input name="url_group_wa" type="text" class="form-control" id="url_group_wa" placeholder="Masukan Link Grup WA Role" required>
                </div>
                <div class="form-group my-2">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/role" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>