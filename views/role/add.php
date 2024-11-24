<?php $this->layout('layouts/base') ?>

<div class="container">
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
                    <input name="name" type="text" class="form-control" id="name" placeholder="Masukan nama project" required>
                </div>
                <div class="form-group my-2">
                    <label for="description">Description</label>
                    <input name="description" type="text" class="form-control" id="description" placeholder="Masukan Deskripsi" required>
                </div>
                <div class="form-group my-2">
                    <label for="url_group_wa">URL Whatsapp</label>
                    <input name="url_group_wa" type="text" class="form-control" id="url_group_wa" placeholder="Masukan Link Grup WA" required>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
            </form>
        </div>
    </div>
</div>