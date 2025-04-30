<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#usertable', {
        pageLength: 200,
        lengthMenu: [5, 10, 25, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'asc']],
    });
</script>
<?php $this->stop() ?>
<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Peserta Aktif Magang (Hari Ini)</h4>
                </div>
            </h5>
            <?php if ($alert): ?>
            <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                <?= $alert['message'] ?>
            </div>
            <?php endif ?>
            <div class="table-responsive">
                <table id="usertable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Fullname</th>
                            <th>Role</th>
                            <th>Mulai</th>
                            <th>Akhir</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $key => $value) { ?>
                        <tr>
                            <td><?= $value[0] ?></td>
                            <td>
                                <?= $isAdmin ? '<a href="'.BASE_URL.'/user/detail/'.$value[1].'">'.$value[3].'</a>' : $value[3] ?>
                            </td>
                            <td>
                                <?= $isAdmin ? '<a href="'.BASE_URL.'/role/detail/'.$value[2].'">'.$value[4].'</a>' : $value[4] ?>
                            </td>
                            <td><?= $value[5] ?></td>
                            <td><?= $value[6] ?></td>
                            <td><?= $value[7] ?></td>
                            <td><?= $value[8] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>