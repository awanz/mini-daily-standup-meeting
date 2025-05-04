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

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Kandidat <?= $candidateRequest->role_name ?></h4>
                    <div>
                        <a href="<?= BASE_URL ?>/hr/candidate-requests" class="btn btn-primary my-2">List Request</a>
                        <a href="<?= BASE_URL ?>/hr/candidate-requests/candidate/add/<?= $candidateRequest->id ?>" class="btn btn-dark my-2">Tambah</a>
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
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($data = $candidates->fetch_object()) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $data->nik ?></td>
                            <td><?= $data->fullname ?></td>
                            <td><?= $data->email ?></td>
                            <td><?= $data->phone ?></td>
                            <td><?= $data->status ?></td>
                            <td> 
                                <a href="<?= BASE_URL ?>/hr/candidate-requests/candidate/edit/<?= $data->candidate_request_id ?>/<?= $data->id ?>" class="btn btn-sm btn-warning my-1">Edit</a>
                                <a href="<?= BASE_URL ?>/hr/candidate-requests/candidate/delete/<?= $data->candidate_request_id ?>/<?= $data->id ?>" class="btn btn-sm btn-danger my-1">Delete</a>
                            </td>
                        </tr>
                        <?php $no++;} ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>