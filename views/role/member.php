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

    $(document).ready(function () {
        document.getElementById('copyEmail').addEventListener('click', function () {
            const emailText = document.getElementById('emailText').innerText;
            navigator.clipboard.writeText(emailText)
                .then(() => {
                    alert('Email copied to clipboard!');
                })
                .catch((err) => {
                    alert('Failed to copy email: ' + err);
                });
        });
        document.getElementById('copyPhone').addEventListener('click', function () {
            const phoneText = document.getElementById('phoneText').innerText;
            navigator.clipboard.writeText(phoneText)
                .then(() => {
                    alert('Phone copied to clipboard!');
                })
                .catch((err) => {
                    alert('Failed to copy phone: ' + err);
                });
        });
    });
</script>
<?php $this->stop() ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Anggota Role <?= $role->name ?></h4>
                    <div>
                        <a href="<?= BASE_URL ?>/role" class="btn btn-light my-2">List Role</a>
                        <a href="#" class="btn btn-primary my-2">Absensi</a>
                    </div>
                </div>
            </h5>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($users as $key => $value) { ?>
                <div class="col">
                    <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= $value[1] ?></h5>
                        <p class="card-text">
                            e-mail: <span id="emailText"><?= htmlspecialchars($value[2]) ?></span>
                            <?php if (!empty($value[2])) { ?>
                            <span class="pe-auto badge bg-dark" style="cursor: pointer;" id="copyEmail">Copy</span>
                            <?php } ?>
                            <br>
                            No HP: <span id="phoneText"><?= htmlspecialchars($value[3]) ?></span>
                            <?php if (!empty($value[3])) { ?>
                            <a href="https://wa.me/<?= htmlspecialchars($value[3]) ?>" target="_blank" class="pe-auto badge bg-success" style="cursor: pointer;">Chat</a>
                            <span class="pe-auto badge bg-dark" style="cursor: pointer;" id="copyPhone">Copy</span>
                            <?php } ?>
                            <br>
                            Kontrak: <?= $value[4] ?> - <?= $value[5] ?><br>
                            Total Daily Bulan ini: <?= $value[6] ?><br>
                            Projects: <?= $value[10] ?>
                        </p>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Last login <?= $value[9] ?></small>
                    </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>