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
                    <h4>Edit Data Kandidat</h4>
                    <div>
                        <a href="<?= BASE_URL ?>/hr/candidate-requests/candidate/<?= $id ?>" class="btn btn-dark my-2">List</a>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/hr/candidate-requests/candidate/edit/<?= $candidate->candidate_request_id ?>/<?= $candidate->id ?>">
                <div class="form-group my-2">
                    <label for="Role">Role</label>
                    <select id="roleSelect" class="form-select" name="role_id" disabled>
                        <option selected value="">-- Pilih Type --</option>
                        <?php foreach($roles as $role): ?>
                        <option <?php if ($role[0] == $candidateRequest->role_id) { ?> selected <?php } ?> value="<?= $role[0] ?>"><?= $role[1] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="nik">NIK</label>
                    <input name="nik" type="number" class="form-control" min="1000000000000000" value="<?= $candidate->nik ?>" required>
                </div>
                <div class="form-group my-2">
                    <label for="fullname">Fullname</label>
                    <input name="fullname" type="text" class="form-control" required value="<?= $candidate->fullname ?>">
                </div>
                <div class="form-group my-2">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" required value="<?= $candidate->email ?>">
                </div>
                <div class="form-group my-2">
                    <label for="phone">Phone (Diawali 62)</label>
                    <input name="phone" type="number" class="form-control" required value="<?= $candidate->phone ?? '62' ?>">
                </div>
                <div class="form-group my-2">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="4"><?= $candidate->description ?></textarea>
                </div>
                <div class="form-group my-2">
                    <label for="status">Status</label>
                    <select id="status" class="form-select" name="status">
                        <option value="">-- Pilih Status --</option>
                        <option <?php if ($candidate->status == "SCREENING") { ?> selected <?php } ?> value="SCREENING">SCREENING</option>
                        <option <?php if ($candidate->status == "INTERVIEW") { ?> selected <?php } ?> value="INTERVIEW">INTERVIEW</option>
                        <option <?php if ($candidate->status == "HIRED") { ?> selected <?php } ?> value="HIRED">HIRED</option>
                        <option <?php if ($candidate->status == "MARK") { ?> selected <?php } ?> value="MARK">MARK</option>
                        <option <?php if ($candidate->status == "REJECTED") { ?> selected <?php } ?> value="REJECTED">REJECTED</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/hr/candidate-requests/candidate/<?= $candidate->candidate_request_id ?>" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>