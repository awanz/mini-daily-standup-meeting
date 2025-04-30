<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#usertable', {
        pageLength: 50,
        lengthMenu: [10, 30, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[2, 'desc']],
    });
</script>
<?php $this->stop() ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Monitoring PIC Role</h4>
                    <div>
                    </div>
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
                            <th>Nama</th>
                            <th>Total Anak Magang</th>
                            <th>Roles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($picRoles as $key => $value) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $value[1] ?></td>
                            <td><?= $value[2] ?></td>
                            <td>
                                <?php
                                    $items = explode(',', $value[3]);

                                ?>
                                <ul>
                                    <?php foreach ($items as $item): ?>
                                        <li><?= trim($item) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                        <?php $no++;} ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>