<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Detail</h4>
                    <div>
                        <a href="<?= BASE_URL ?>/hr/candidate-requests" class="btn btn-dark my-2">List</a>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form>
                <div class="form-group my-2">
                    <label for="Role">Role</label>
                    <select class="form-select" disabled>
                        <option selected value="">-- Pilih Type --</option>
                        <?php foreach($roles as $role): ?>
                        <option <?php if ($role[0] == $this->e($candidateRequest->role_id ?? "")) { echo 'selected'; } ?> value="<?= $role[0] ?>"><?= $role[1] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="pic">PIC</label>
                    <input type="text" class="form-control" disabled value="<?= $this->e($candidateRequest->fullname ?? "") ?>">
                </div>
                <div class="form-group my-2">
                    <label for="total">Total</label>
                    <input type="number" class="form-control" min="1" disabled value="<?= $this->e($candidateRequest->total ?? "") ?>">
                </div>
                <div class="form-group my-2">
                    <label for="description">Description</label><br>
                    <?= $this->e($candidateRequest->description ?? "") ?>
                </div>
                <div class="form-group my-2">
                    <label for="note">Catatan</label><br>
                    <?= $this->e($candidateRequest->note ?? "") ?>
                </div>
                <a href="<?= BASE_URL ?>/hr/candidate-requests" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>