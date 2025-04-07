<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/role/edit/<?=$this->e($role->id ?? "")?>">
                <div class="form-group my-2">
                    <label for="name">Name</label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Masukan nama project" value="<?=$this->e($role->name ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="pic">User</label>
                    <select id="userSelect" class="form-select" name="pic_id" style="width: 100%;" required>
                        <option selected>-- Pilih PIC --</option>
                        <?php foreach($listPIC as $user): ?>
                        <option <?php if($user[0] == $role->pic_id){ ?> selected <?php } ?> value="<?= $user[0] ?>"><?= $user[3] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="url_group_wa">URL Whatsapp</label>
                    <input name="url_group_wa" type="text" class="form-control" id="url_group_wa" placeholder="Masukan Link Grup WA" value="<?=$this->e($role->url_group_wa ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="4"><?=$this->e($role->description ?? "")?></textarea>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/role" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>