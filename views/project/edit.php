<?php $this->layout('layouts/base') ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Edit Projects <?= $project->name ?></h4>
                    <div>
                        <a href="<?= BASE_URL ?>/project" class="btn btn-dark my-2">List Project</a>
                        <a href="<?= BASE_URL ?>/project/detail/<?= $project->id ?>" class="btn btn-primary my-2">Detail Project</a>
                    </div>
                </div>
            </h5>
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
                    <label for="pic">User</label>
                    <select id="userSelect" class="form-select" name="pic" style="width: 100%;" required <?php if($isProjectManager){ ?> disabled <?php } ?>>
                        <option selected>-- Pilih PIC --</option>
                        <?php foreach($listPM as $user): ?>
                        <option <?php if($user[0] == $project->pic){ ?> selected <?php } ?> value="<?= $user[0] ?>"><?= $user[3] ?></option>
                        <?php endforeach ?>
                    </select>
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
                        <option <?php if("PENDING" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="PENDING">Pending</option>
                        <option <?php if("MAINTENANCE" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="MAINTENANCE">Maintenance</option>
                        <option <?php if("CANCEL" == $this->e($project->status ?? "")){ echo 'selected'; } ?> value="CANCEL">Cancel</option>
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
                        <option <?php if("ANIMASI" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="ANIMASI">ANIMASI</option>
                        <option <?php if("MAJALAH" == $this->e($project->type ?? "")){ echo 'selected'; } ?> value="MAJALAH">MAJALAH</option>
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
                    <label for="embeded">Embeded</label>
                    <textarea class="form-control" name="embeded" id="embeded" rows="4"><?= $project->embeded ?></textarea>
                    <small>
                    <b>contoh</b>: &lt;iframe 
                        src="https://drive.google.com/embeddedfolderview?id=<b>ID GOOGLE DRIVE</b>#grid" 
                        width="100%" 
                        height="600" 
                        frameborder="0"&gt;
                    &lt;/iframe&gt;
                    </small>
                </div>
                <div class="form-group my-2">
                    <label for="note">Catatan</label>
                    <textarea class="form-control" name="note" id="note" rows="4"><?=$this->e($project->note ?? "")?></textarea>
                </div>
                
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/project/detail/<?= $project->id ?>" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>