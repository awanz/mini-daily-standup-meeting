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
        $('#userSelect').select2({
            placeholder: "Select an option",
            allowClear: true,
            minimumResultsForSearch: 2
        });
    });
</script>
<?php $this->stop() ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Tambah Anggota Project <?= $project->name ?></h4>
                    <div>
                        <a href="<?= BASE_URL ?>/project/detail/<?= $id ?>" class="btn btn-primary my-2">Detail Project</a>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/project/add-member/<?= $id ?>">
                <div class="row">
                    <div class="form-group my-2 col-12 col-md-6">
                        <label for="user_id">User</label>
                        <select id="userSelect" class="form-select" name="user_id" style="width: 100%;" required>
                            <option selected>-- Pilih Users --</option>
                            <?php foreach($users as $user): ?>
                            <option value="<?= $user[0] ?>"><?= $user[1] ?> <?= $user[2] ? '('.$user[2].')' : '' ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/project/detail/<?= $id ?>" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>