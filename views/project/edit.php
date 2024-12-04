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
                <div class="form-group my-2">
                    <label for="url_group_wa">Whatsapp</label>
                    <input name="url_group_wa" type="text" class="form-control" id="url_group_wa" placeholder="Masukan url grup wa" value="<?=$this->e($project->url_group_wa ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="url_drive">Drive</label>
                    <input name="url_drive" type="text" class="form-control" id="url_drive" placeholder="Masukan url drive" value="<?=$this->e($project->url_drive ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="url_figma">Figma</label>
                    <input name="url_figma" type="text" class="form-control" id="url_figma" placeholder="Masukan url figma" value="<?=$this->e($project->url_figma ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="url_logo">Logo</label>
                    <input name="url_logo" type="text" class="form-control" id="url_logo" placeholder="Masukan url logo" value="<?=$this->e($project->url_logo ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="url_repo">Repository GIT</label>
                    <input name="url_repo" type="text" class="form-control" id="url_repo" placeholder="Masukan url repository git" value="<?=$this->e($project->url_repo ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="note">Catatan</label>
                    <textarea class="form-control" name="note" id="note" rows="4"><?=$this->e($project->note ?? "")?></textarea>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/project" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>