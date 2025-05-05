<?php $this->layout('layouts/base') ?>

<div class="xxx">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Pengajuan perpanjang magang</h4>
                    <div>
                        <?= date('d M Y') ?>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <?php if (empty($contractExtend) || (!empty($contractExtend) && ($contractExtend->status != "REQUEST"))): ?>
            <?php if (!empty($contractExtend) && $contractExtend->status != "REQUEST" && $contractExtend->status != "APPROVED"){ ?>
                <b>
                    <p>Pengajuan perpanjangan magang terakhir telah di <?= $contractExtend->status ?>, silahkan ajukan kembali.</p>
                </b>
            <?php } ?>

            <form method="POST" action="<?= BASE_URL ?>/profile/contract-extend">
                <div class="form-group my-2">
                    <label for="duration">Perpanjang magang berapa bulan?</label>
                    <input name="duration" type="number" min="1" class="form-control" id="duration" placeholder="Masukan jumlah bulan (1 - 5)" required>
                </div>
                <div class="form-group my-2">
                    <label for="duration">Catatan (Alasan/Hal lainnya)</label><br>
                    <textarea name="description" id="description" rows="5" cols="100"></textarea>
                </div>                
                <button type="submit" class="btn btn-dark my-2">Ajukan</button>
                <a href="<?= BASE_URL ?>/profile" class="btn btn-light my-2">Profile</a>
            </form>

            <?php elseif (!empty($contractExtend) && $contractExtend->status == "REQUEST"): ?>
                Pengajuan sedang di proses, untuk konfirmasi bisa info di <br>
                <a href="https://chat.whatsapp.com/CaLBLuU8eBe60aMocF0GrL" class="btn text-white bg-success" target="_BLANK">Whatsapp Group</a><br>
                <p><b>Lalu dibaca bagian deskripsi grup whatsapp untuk prosedur konfirmasinya.</b></p>
                <table class="table table-striped table-bordered table-hover mt-2">
                    <thead >
                        <tr>
                            <th style="width: 300px;">Durasi</th>
                            <th>Catatan</th>
                        </tr>
                    </thead >
                    <tbody>
                        <tr>
                            <td><?= $contractExtend->duration ?> Bulan</td>
                            <td><?= $contractExtend->description ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>