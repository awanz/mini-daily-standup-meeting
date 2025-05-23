<?php $this->layout('layouts/base') ?>

<div class="xxx">
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
                    <input type="text" class="form-control" disabled value="<?= $this->e($candidateRequest->pic_name ?? "") ?>">
                </div>
                <div class="form-group my-2">
                    <label for="contract_date">Tanggal Kontrak</label>
                    <input type="text" class="form-control" disabled value="<?= $this->e($candidateRequest->contract_date ?? "") ?>">
                </div>
                <div class="form-group my-2">
                    <label for="total">Total Kuota</label>
                    <input type="number" class="form-control" min="1" disabled value="<?= $this->e($candidateRequest->total ?? "") ?>">
                </div>
                <?php if(!empty($candidateRequest->description)){ ?>
                <div class="form-group my-2">
                    <label for="description"><u>Description</u></label><br>
                    <?= $this->e($candidateRequest->description ?? "") ?>
                </div>
                <?php } ?>
                <?php if(!empty($candidateRequest->note)){ ?>
                <div class="form-group my-2">
                    <label for="note"><u>Catatan</u></label><br>
                    <?= $this->e($candidateRequest->note ?? "") ?>
                </div>
                <?php } ?>
                <a href="<?= BASE_URL ?>/hr/candidate-requests" class="btn btn-light my-2">Kembali</a>
                <hr>
                <div class="form-group my-2">
                    <p>Last update by <?= $this->e($candidateRequest->updated_name ?? "") ?> at <?= $this->e($candidateRequest->updated_at ?? "") ?></p>
                </div>
            </form>
        </div>
    </div>
</div>