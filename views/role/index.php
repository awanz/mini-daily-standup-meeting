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
                    <h4>Role</h4>
                    <div>
                        <a href="<?= BASE_URL ?>/role/add" class="btn btn-dark my-2">Tambah Role</a>
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>PIC</th>
                            <th>Total Anggota</th>
                            <th>Whatsapp</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $key => $value) { ?>
                        <tr>
                            <td><?= $value[5] ?></td>
                            <td><?= $value[1] ?></td>
                            <td><?= $value[7] ?></td>
                            <td><?= $value[6] ?></td>
                            <td><?= $value[4] ?></td>
                            <td>
                                <?php if (isset($value[3])) { ?>
                                <a target="_BLANK" href="<?= $value[3] ?>" class="btn btn-success my-1">Group WA</a>
                                <?php } ?>
                            </td>
                            <td>    
                                <a href="<?= BASE_URL ?>/role/meeting-attendance/<?= $value[0] ?>" class="btn btn-success my-1">Absensi</a>
                                <a href="<?= BASE_URL ?>/role/detail/<?= $value[0] ?>" class="btn btn-primary my-1">Detail</a>
                                <a href="<?= BASE_URL ?>/role/edit/<?= $value[0] ?>" class="btn btn-warning my-1">Edit</a>
                                <a href="#" class="btn btn-danger delete-btn my-1" data-url="<?= BASE_URL ?>/role/delete/<?= $value[0] ?>">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>