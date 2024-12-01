<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/project/add">
                <div class="form-group my-2">
                    <label for="name">Name</label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Masukan nama project" required>
                </div>
                <div class="form-group my-2">
                    <label for="status">Status</label>
                    <select class="form-select" name="status" required>
                        <option selected>-- Pilih Status --</option>
                        <option value="NOT_STARTED">Not Started</option>
                        <option value="IN_PROGRESS">In Progress</option>
                        <option value="COMPLETED">Completed</option>
                        <option value="FIXING">Fixing</option>
                        <option value="PUBLISH">Publish</option>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="type">Type</label>
                    <select class="form-select" name="type" required>
                        <option selected>-- Pilih Type --</option>
                        <option value="WEB">WEB</option>
                        <option value="MOBILE">MOBILE</option>
                        <option value="MEDSOS">MEDSOS</option>
                        <option value="GAME">GAME</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/project" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>