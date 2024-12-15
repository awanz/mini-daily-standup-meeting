<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Tambah Projects</h4>
                    <div>
                        <a href="<?= BASE_URL ?>/project" class="btn btn-dark my-2">List Project</a>
                    </div>
                </div>
            </h5>
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
                        <option value="PUBLISH">Publish</option>
                        <option value="FIXING">Fixing</option>
                        <option value="PENDING">Pending</option>
                        <option value="MAINTENANCE">Maintenance</option>
                        <option value="CANCEL">Cancel</option>
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
                        <option value="ANIMASI">ANIMASI</option>
                        <option value="MAJALAH">MAJALAH</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/project" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>