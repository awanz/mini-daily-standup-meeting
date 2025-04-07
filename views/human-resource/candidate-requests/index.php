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

<div class="container">
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
                            <th>Role</th>
                            <th>Status</th>
                            <th>Request</th>
                            <th>Seleksi</th>
                            <th>Hire</th>
                            <th>Requester</th>
                            <th>PIC</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($candidateRequests as $key => $value) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $value[1] ?></td>
                            <td>
                                <?= $value[3] == 'REQUEST' ? '<span class="badge bg-warning text-dark">REQUEST</span>': '' ?>
                                <?= $value[3] == 'OPEN' ? '<span class="badge bg-primary">OPEN</span>': '' ?>
                                <?= $value[3] == 'CANCEL' ? '<span class="badge bg-danger">CANCEL</span>': '' ?>
                                <?= $value[3] == 'DONE' ? '<span class="badge bg-success text-white">DONE</span>': '' ?>
                                
                            </td>
                            <td><?= $value[2] ?></td>
                            <td>0</td>
                            <td>0</td>
                            <td><?= $value[5] ?></td>
                            <td><?= $value[4] ?? '<span class="badge bg-danger">Belum ada PIC</span>' ?></td>
                            <td> 
                                <a href="<?= BASE_URL ?>/hr/candidate-requests/detail/<?= $value[0] ?>" class="btn btn-primary my-1">Detail</a>
                                <a href="<?= BASE_URL ?>/hr/candidate-requests/edit/<?= $value[0] ?>" class="btn btn-warning my-1">Edit</a>
                                <!-- <a href="<?= BASE_URL ?>/hr/candidate-requests/candidate/add/<?= $value[0] ?>" class="btn btn-success my-1">Add Candidate</a> -->
                            </td>
                        </tr>
                        <?php $no++;} ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>