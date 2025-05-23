<?php $this->layout('layouts/base') ?>
<?php $this->start('footAdditional') ?>
<script>
    new DataTable('#history', {
        responsive: true,
        pageLength: 31,
        lengthMenu: [5, 10, 25, 28, 29, 30, 31, 50, 1000],
        layout: {
            topStart: {
                buttons: ['pageLength','excel']
            }
        },
        order: [[0, 'desc']],
    });
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        const url = $(this).data('url');

        Swal.fire({
            title: "Yakin untuk menghapus daily?",
            text: "Data akan dihapus secara permanen!",
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
                    <h4>History Daily</h4>
                    <div><?= date('d M Y') ?></div>
                </div>
            </h5>
            <p class="card-text">Hello, <b><?=$this->e($dataUser->fullname)?></b> <u>(<?=$this->e($dataUser->email)?>)</u></p>
            <h4 class="text-dark">[MOHON BACA]</h4>
            <h6 class="card-text">[x] Pastikan email yang tercantum benar, karna info maupun teguran akan dikirim melalui e-mail.</h6>
            <h6 class="card-text">[x] Setiap bulan minimal mengisi 15 kali daily standup meeting, meskipun SAKIT, IZIN wajib isi.</h6>
            <hr>
            <?php if ($alert): ?>
            <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                <?= $alert['message'] ?>
            </div>
            <?php endif ?>
            <div class="table-responsive">
                <table id="history" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <?php if ($isAdmin): ?>
                        <th>Nama</th>
                        <?php endif ?>
                        <th>Aktifitas Kemarin</th>
                        <th>Hari ini</th>
                        <th>Permasalahan</th>
                        <?php if ($isAdmin): ?>
                        <th>Profile</th>
                        <th>Delete</th>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dailys as $daily): ?>
                    <tr>
                        <td><?=$this->e($daily[2])?></td>
                        <?php if ($isAdmin): ?>
                        <td>
                            <a href="<?= BASE_URL ?>/history/<?= $daily[7] ?>"><?= $daily[8] ?></a>
                            
                        </td>
                        <?php endif ?>
                        <td><?= $daily[3] ?></td>
                        <td><?= $daily[4] ?></td>
                        <td><?= $daily[5] ?></td>
                        <?php if ($isAdmin): ?>
                        <td>
                            <a href="<?= BASE_URL ?>/user/detail/<?= $daily[9] ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                                </svg>
                            </a>
                        </td>
                        <td>
                            
                            <?php 
                            $tanggalSekarang = new DateTime();
                            $tanggalDiberikan = new DateTime($daily[6]);
                            if ($tanggalSekarang->diff($tanggalDiberikan)->days < 30): ?>
                            <a href="#" class="btn btn-danger btn-delete" data-url="<?= BASE_URL ?>/history/delete/<?= $daily[0] ?>">
                                Delete
                            </a>
                            <?php endif ?>
                        </td>
                        <?php endif ?>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>