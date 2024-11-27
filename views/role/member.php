<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#usertable', {
        layout: {
            topStart: {
                buttons: ['excel']
            }
        },
        order: [[0, 'asc']]
    });
</script>
<?php $this->stop() ?>

<div class="container mt-4">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($users as $key => $value) { ?>
        <div class="col">
            <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><?= $value[3] ?></h5>
                <p class="card-text">
                    e-mail: <?= $value[5] ?><br>
                    No HP: <?= $value[6] ?><br>
                    Kontrak: <?= $value[8] ?> - <?= $value[9] ?>
                </p>
            </div>
            <div class="card-footer">
                <small class="text-muted">Last login <?= $value[11] ?></small>
            </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>