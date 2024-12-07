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

    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        const url = $(this).data('url');

        Swal.fire({
            title: "Yakin untuk melakukan delete?",
            text: "Data yang dihapus akan menjadi user nonactive.",
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
                    <h4>User List</h4>
                    <div>
                        <a href="<?= BASE_URL ?>/user/add" class="btn btn-dark my-2">Tambah User</a>
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
                            <th>Fullname</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Total Daily</th>
                            <th>Projects</th>
                            <th>Send Password</th>
                            <th>Peringatan 1</th>
                            <th>Pemecatan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $key => $value) { ?>
                        <tr>
                            <td><?= $value[0] ?></td>
                            <td 
                                <?php 
                                    $today = new DateTime(); 
                                    $dateEnd = $value[6] ? new DateTime($value[6]) : null;
                                ?>
                                <?php if ($value[9] == 1 && $today <= $dateEnd) { ?> style="background-color: yellow;" <?php } ?>
                                <?php if ($value[9] == 1 && $today >= $dateEnd) { ?> style="background-color: green;" <?php } ?>
                                <?php if (empty($value[5]) || empty($value[6]) || empty($value[8])) { ?> style="background-color: purple;" <?php } ?>
                                >
                                <?= $value[2] ?>
                            </td>
                            <td><?= $value[8] ?></td>
                            <td><?= $value[3] ?></td>
                            <td><?php if ($value[7] == 99) { ?><?= $value[7] ?><?php }else{ ?><a href="<?= BASE_URL ?>/history/<?= $value[3] ?>"><?= $value[7] ?></a><?php } ?></td>
                            <td><?= $value[11] ?></td>
                            <td><a href="<?= BASE_URL ?>/email/credential/<?= $value[1] ?>" class="btn btn-dark">Send</a></td>
                            <td><a href="<?= BASE_URL ?>/email/peringatan/<?= $value[1] ?>" class="btn btn-dark">Send</a></td>
                            <td><a href="<?= BASE_URL ?>/email/pemecatan/<?= $value[1] ?>" class="btn btn-dark">Send</a></td>
                            <td>
                                <?php if (!empty($value[4])) { ?>
                                <a target="_BLANK" href="https://wa.me/<?= $value[4] ?>" class="btn btn-success my-1">WA</a>
                                <?php } ?>
                                <a href="<?= BASE_URL ?>/user/detail/<?= $value[1] ?>" class="btn btn-primary my-1">Detail</a>
                                <a href="<?= BASE_URL ?>/user/edit/<?= $value[1] ?>" class="btn btn-warning my-1">Edit</a>
                                
                                <a href="#" class="btn btn-danger delete-btn my-1" data-url="<?= BASE_URL ?>/user/delete/<?= $value[1] ?>">
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