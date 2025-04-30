<?php $this->layout('layouts/base') ?>
<?php $this->start('headAdditional') ?>
<style>
    table.dataTable {
        font-size: 14px;
    }
</style>
<?php $this->stop() ?>
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
        // order: [[1, 'asc']],
    });

    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        const url = $(this).data('url');

        Swal.fire({
            title: "Yakin untuk melakukan delete?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
</script>
<?php $this->stop() ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Request Candidates</h4>
                    <div>
                        <a href="<?= BASE_URL ?>/hr/candidate-requests/add" class="btn btn-dark my-2">Request</a>
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
                            <th>Pengajuan</th>
                            <th>Role</th>
                            <th>Kontrak</th>
                            <th>Status</th>
                            <th>Kuota</th>
                            <th>Seleksi</th>
                            <th>Hire</th>
                            <th>Requester</th>
                            <th>PIC</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; $currentDate = new DateTime(); while ($data = $contractExtend->fetch_object()) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= date("Y-m-d", strtotime($data->created_at)); ?></td>
                            <td><?= $data->role_name ?></td>
                            <td><?= $data->contract_date ?></td>
                            <td>
                                <?= $data->status == 'REQUEST' ? '<span class="badge bg-warning text-dark">REQUEST</span>': '' ?>
                                <?= $data->status == 'OPEN' ? '<span class="badge bg-primary">OPEN</span>': '' ?>
                                <?= $data->status == 'CANCEL' ? '<span class="badge bg-danger">CANCEL</span>': '' ?>
                                <?= $data->status == 'DONE' ? '<span class="badge bg-success text-white">DONE</span>': '' ?>
                                
                            </td>
                            <td><?= $data->total ?></td>
                            <td>0</td>
                            <td>0</td>
                            <td><?= $data->name_create ?></td>
                            <td><?= $data->name_pic ?? '<span class="badge bg-danger">Belum ada PIC</span>' ?></td>
                            <td> 
                                <?php 
                                    $batas = (new DateTime($data->updated_at))->modify('+3 days');
                                    if ($data->status == 'DONE' && $batas < $currentDate) {
                                        echo "Closed by ". $data->name_update . " at " . $data->updated_at;
                                    }else{
                                ?>
                                <?php if($data->interview_question): ?>
                                    <a href="<?= $data->interview_question ?>" class="btn btn-info my-1">Question</a>
                                <?php endif; ?>
                                <?php if($data->job_qualification): ?>
                                    <a href="<?= $data->job_qualification ?>" class="btn btn-success my-1">Qualification</a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/hr/candidate-requests/candidate/<?= $data->id ?>" class="btn btn-sm btn-dark my-1">Kandidat</a>
                                <a href="<?= BASE_URL ?>/hr/candidate-requests/detail/<?= $data->id ?>" class="btn btn-sm btn-primary my-1">Detail</a>
                                <a href="<?= BASE_URL ?>/hr/candidate-requests/edit/<?= $data->id ?>" class="btn btn-sm btn-warning my-1">Edit</a>
                                <!-- <a href="<?= BASE_URL ?>/hr/candidate-requests/candidate/add/<?= $data->id ?>" class="btn btn-success my-1">Add Candidate</a> -->
                                 <?php } ?>
                            </td>
                        </tr>
                        <?php $no++;} ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>