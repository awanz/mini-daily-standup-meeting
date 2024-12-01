<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/project/edit/<?= $project->id ?>">
                <div class="form-group my-2">
                    <label for="name">Name</label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Masukan nama project" value="<?=$this->e($project->name ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="status">Status</label>
                    <select class="form-select" name="status" required>
                        <option selected>-- Pilih Status --</option>
                        <option <?php if("NOT_STARTED" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="NOT_STARTED">Not Started</option>
                        <option <?php if("IN_PROGRESS" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="IN_PROGRESS">In Progress</option>
                        <option <?php if("COMPLETED" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="COMPLETED">Completed</option>
                        <option <?php if("FIXING" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="FIXING">Fixing</option>
                        <option <?php if("PUBLISH" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="PUBLISH">Publish</option>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="type">Type</label>
                    <select class="form-select" name="type" required>
                        <option selected>-- Pilih Type --</option>
                        <option <?php if("WEB" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="WEB">WEB</option>
                        <option <?php if("MOBILE" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="MOBILE">MOBILE</option>
                        <option <?php if("MEDSOS" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="MEDSOS">MEDSOS</option>
                        <option <?php if("GAME" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="GAME">GAME</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/project" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>