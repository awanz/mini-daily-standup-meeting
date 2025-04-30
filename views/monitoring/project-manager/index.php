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
        order: [[0, 'asc']],
    });
</script>
<?php $this->stop() ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Monitoring Project Manager</h4>
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
                            <th>Role</th>
                            <th>Projects</th>
                            <th>Last Login</th>
                            <th>Meeting Project</th>
                            <th>Meeting Role</th>
                            <th>Meeting Total</th>
                            <th>Meeting Tidak Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = $lists->fetch_object()) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><a href="<?= BASE_URL ?>/user/detail/<?= $row->id ?>"><?= $row->fullname ?></a></td>
                            <td><?= $row->role_name ?></td>
                            <td><?= $row->projects ?></td>
                            <td><?= $row->last_login_at ?></td>
                            <td><?= $row->total_meeting_project ?></td>
                            <td><?= $row->total_meeting_role ?></td>
                            <td><?= $row->total_meetings_current_month ?></td>
                            <td><?= $row->total_meeting_absent ?></td>
                        </tr>
                        <?php $no++;} ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>