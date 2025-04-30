<?php $this->layout('layouts/base') ?>
<?php $this->start('headAdditional') ?>
<style>
    .select2-container .select2-selection--single {
        height: 38px; 
        padding: 6px 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
        right: 10px;
    }
</style>
<?php $this->stop() ?>

<?php $this->start('footAdditional') ?>
<script>
    $(document).ready(function () {
        $('#roleSelect').select2({
            placeholder: "Select an option",
            allowClear: true,
            minimumResultsForSearch: 2
        });
    });
</script>
<?php $this->stop() ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Meminta Kandidat</h4>
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
            <form method="POST" action="<?= BASE_URL ?>/hr/candidate-requests/edit/<?= $candidateRequest->id ?>">
                <div class="form-group my-2">
                    <label for="Role">Role</label>
                    <select id="roleSelect" class="form-select" name="role_id" required>
                        <option selected value="">-- Pilih Type --</option>
                        <?php foreach($roles as $role): ?>
                        <option <?php if ($role[0] == $this->e($candidateRequest->role_id ?? "")) { echo 'selected'; } ?> value="<?= $role[0] ?>"><?= $role[1] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="contract_date">Tanggal Kontrak</label>
                    <input name="contract_date" type="date" class="form-control" value="<?= $candidateRequest->contract_date ?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="pic">PIC</label>
                    <select id="userSelect" class="form-select" name="pic_id" style="width: 100%;">
                        <option selected value="0">-- Pilih PIC --</option>
                        <?php foreach($listPIC as $user): ?>
                        <option <?php if($user[0] == $candidateRequest->pic_id){ ?> selected <?php } ?> value="<?= $user[0] ?>"><?= $user[3] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="pic">Status</label>
                    <select id="userSelect" class="form-select" name="status" style="width: 100%;" required>
                        <option selected>-- Pilih Status --</option>
                        <option <?php if($candidateRequest->status == 'REQUEST'){ ?> selected <?php } ?> value="REQUEST">REQUEST</option>
                        <option <?php if($candidateRequest->status == 'OPEN'){ ?> selected <?php } ?> value="OPEN">OPEN</option>
                        <option <?php if($candidateRequest->status == 'CANCEL'){ ?> selected <?php } ?> value="CANCEL">CANCEL</option>
                        <option <?php if($candidateRequest->status == 'DONE'){ ?> selected <?php } ?> value="DONE">DONE</option>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="total">Total Kuota</label>
                    <input name="total" type="number" class="form-control" min="1" value="<?= $candidateRequest->total ?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="4"><?= $candidateRequest->description ?></textarea>
                </div>
                <div class="form-group my-2">
                    <label for="note">Catatan</label>
                    <textarea class="form-control" name="note" id="note" rows="4"><?= $candidateRequest->note ?></textarea>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/hr/candidate-requests" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>