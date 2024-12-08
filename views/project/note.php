<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Catatan <u><?= $projectUser->fullname; ?></u> di project <u><?= $projectUser->project_name; ?></u></h4>
                    <div>
                        <a href="<?= BASE_URL ?>/project/detail/<?= $projectUser->project_id ?>" class="btn btn-primary my-2">Detail Project</a>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?= BASE_URL ?>/project/member/note/<?= $projectUser->id ?>">
                <div class="form-group my-2">
                    <label for="notes">Catatan</label>
                    <textarea class="form-control" name="notes" id="notes" rows="4"><?=$this->e($projectUser->notes ?? "")?></textarea>
                </div>
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/project/detail/<?= $projectUser->project_id ?>" class="btn btn-light my-2">Kembali</a>
            </form>
        </div>
    </div>
</div>