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
                        <option <?php if("HR" == $this->e($user->access ?? "")){ echo 'selected'; } ?> value="HR">HR</option>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="fullname">Fullname</label>
                    <input name="fullname" type="text" class="form-control" id="fullname" placeholder="Masukan fullname" value="<?=$this->e($user->fullname ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="email">Email address</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="Masukan email" value="<?=$this->e($user->email ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="phone">No Whatsapp</label>
                    <input name="phone" type="text" class="form-control" id="phone" placeholder="Masukan phone" value="<?=$this->e($user->phone ?? "")?>">
                </div>
                <div class="form-group my-2">
                    <label for="date_start">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="date_start" name="date_start" value="<?=$this->e($user->date_start ?? "")?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="date_end">Tanggal Berakhir</label>
                    <input type="date" class="form-control" id="date_end" name="date_end" value="<?=$this->e($user->date_end ?? "")?>" required>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
            </form>
        </div>
    </div>
</div>