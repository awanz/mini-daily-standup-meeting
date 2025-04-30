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
            <form method="POST" action="<?= BASE_URL ?>/hr/candidate-requests/add">
                <div class="form-group my-2">
                    <label for="Role">Role</label>
                    <select id="roleSelect" class="form-select" name="role_id" disabled>
                        <option selected value="">-- Pilih Type --</option>
                        <?php foreach($roles as $role): ?>
                        <option value="<?= $role[0] ?>"><?= $role[1] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="total">Total</label>
                    <input name="total" type="number" class="form-control" min="1" required>
                </div>
                <div class="form-group my-2">
                    <label for="nik">NIK</label>
                    <input name="nik" type="number" class="form-control" required>
                </div>
                <div class="form-group my-2">
                    <label for="fullname">Fullname</label>
                    <input name="fullname" type="text" class="form-control" required>
                </div>
                <div class="form-group my-2">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>
                <div class="form-group my-2">
                    <label for="phone">Phone</label>
                    <input name="phone" type="number" class="form-control" required>
                </div>
                <div class="form-group my-2">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/hr/candidate-requests" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>