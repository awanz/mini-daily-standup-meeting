<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/user/edit/<?=$this->e($user->id ?? "")?>">
                <div class="form-group my-2">
                    <label for="Role">Role</label>
                    <select class="form-select" name="role_id" required>
                        <option selected>-- Pilih Type --</option>
                        <?php foreach($roles as $role): ?>
                        <option <?php if($role[0] == $this->e($user->role_id ?? "")){ echo 'selected'; } ?> value="<?= $role[0] ?>"><?= $role[1] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="Role">Access</label>
                    <select class="form-select" name="access" required>
                        <option selected>-- Pilih Access --</option>
                        <option <?php if("USER" == $this->e($user->access ?? "")){ echo 'selected'; } ?> value="USER">USER</option>
                        <option <?php if("ADMIN" == $this->e($user->access ?? "")){ echo 'selected'; } ?> value="ADMIN">ADMIN</option>
                        <option <?php if("VOLUNTEER" == $this->e($user->access ?? "")){ echo 'selected'; } ?> value="VOLUNTEER">VOLUNTEER</option>
                        <option <?php if("MITRA" == $this->e($user->access ?? "")){ echo 'selected'; } ?> value="MITRA">MITRA</option>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="fullname">Fullname</label>
                    <input name="fullname" type="text" class="form-control" id="fullname" placeholder="Masukan fullname" value="<?=$this->e($user->fullname ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="Masukan email" value="<?=$this->e($user->email ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="phone">No Whatsapp (Mulai dengan 62 tanpa simbol +)</label>
                    <input name="phone" type="text" class="form-control" id="phone" placeholder="6285000000000" value="<?=$this->e($user->phone ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="date_start">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="date_start" name="date_start" value="<?=$this->e($user->date_start ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="date_end">Tanggal Berakhir</label>
                    <input type="date" class="form-control" id="date_end" name="date_end" value="<?=$this->e($user->date_end ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="notes">Catatan</label>
                    <textarea class="form-control" name="notes" id="notes" rows="4"><?=$this->e($user->notes ?? "")?></textarea>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/user" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
    <div class="card my-2">
        <div class="card-body">
            <p>Last login: <?=$this->e($user->last_login_at ?? "")?></p>
            <p>Created by: <?=$this->e($user->created_name ?? "-")?></p>
            <p>Created at: <?=$this->e($user->created_at ?? "-")?></p>
            <p>Updated by: <?=$this->e($user->updated_name ?? "-")?></p>
            <p>Updated at: <?=$this->e($user->updated_at ?? "-")?></p>
        </div>
    </div>
</div>