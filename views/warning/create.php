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
                    <h4>Buat Peringatan</h4>
                    <div>
                        <?= date('d M Y') ?>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/warnings/add">
                <div class="form-group my-2">
                    <label for="type">Tipe Peringatan</label>
                    <select class="form-select" name="type" required>
                        <option selected value="1">Peringatan</option>
                        <option value="2">Pemecatan</option>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="user_id">Users</label>
                    <select id="userSelect" class="form-select" name="user_id" required>
                        <option value="" selected>-- Pilih User --</option>
                        <?php foreach($users as $user): ?>
                        <option value="<?= $user[0] ?>"><?= $user[1] ?> (<?= $user[2] ?>)</option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="reason">Alasan</label><br>
                    <textarea class="form-control" name="reason" id="reason" rows="6" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-dark my-2">Kirim</button>
                <a href="<?= BASE_URL ?>/warnings" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>