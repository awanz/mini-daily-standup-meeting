<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Pengajuan pengunduran diri</h4>
                    <div>
                        <a href="https://docs.google.com/document/d/1LznpaBGR1f-wvkwXGACq3c1kWVNZED-W/edit?usp=sharing&ouid=117242716899239908221&rtpof=true&sd=true" target="_BLANK" class="btn bg-success text-white">Contoh Surat Resign</a>
                    </div>
                </div>
            </h5>
            <?php if ($alert): ?>
                <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                    <?= $alert['message'] ?>
                </div>
            <?php endif ?>
            <?php if (empty($resign) || (!empty($resign) && ($resign->status != "REQUEST" && $resign->status != "APPROVED"))): ?>
            <?php if (!empty($resign) && $resign->status != "REQUEST" && $resign->status != "APPROVED"){ ?>
                <b>
                    <p>Pengajuan resign terakhir telah di <?= $resign->status ?>, silahkan ajukan kembali.</p>
                </b>
            <?php } ?>
            <p><b>Contoh surat resign bisa didownload di tombol hijau samping kanan atas.</b></p>
            <form method="POST" action="<?= BASE_URL ?>/profile/resign">
                <div class="form-group my-2">
                    <label for="file_resign">Link File Dokumen Pengajuan pengunduran diri (Cukup Link File GDrive)</label><br>
                    <input name="file_resign" type="text" class="form-control" id="file_resign" placeholder="Masukan link file resign" required>
                </div>
                <div class="form-group my-2">
                    <label for="reason">Alasan mengundurkan diri</label>
                    <textarea name="reason" class="form-control" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/profile" class="btn btn-light my-2">Kembali</a>
            </form>
            <?php elseif (!empty($resign) && $resign->status == "REQUEST"): ?>
                Pengajuan sedang di proses, untuk konfirmasi bisa info di <br>
                <a href="https://chat.whatsapp.com/JXwGo9ciB5GJJHcDKNWDQW" class="btn text-white bg-success" target="_BLANK">Whatsapp Group</a>
                <table class="table table-striped table-bordered table-hover mt-2">
                    <thead >
                        <tr>
                            <th style="width: 220px;">File</th>
                            <th>Alasan</th>
                        </tr>
                    </thead >
                    <tbody>
                        <tr>
                            <td>
                                <a href="<?= $resign->file_resign ?>" class="btn btn-dark" target="_BLANK">File Dokumen Resign</a>
                            </td>
                            <td><?= $resign->reason ?></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <form method="POST" action="<?= BASE_URL ?>/profile/resign/cancel">
                    <button type="submit" class="btn btn-warning my-2">Batalkan Resign</button>
                    <a href="<?= BASE_URL ?>/profile" class="btn btn-light my-2">Kembali</a>
                </form>
            <?php elseif (!empty($resign) && $resign->status == "APPROVED"): ?>
                <p><b>Ucapan Perpisahan & Terima Kasih</b></p>

                <p>Dengan ini kami menginformasikan bahwa pengajuan pengunduran diri dari Saudara/i <b><?= $dataUser->fullname ?></b> telah diterima. Kami mengucapkan terima kasih yang sebesar-besarnya atas kontribusi, dedikasi, dan kerja samanya selama ini.</p>

                <p>Kami mendoakan yang terbaik untuk langkah karier selanjutnya. Semoga sukses dan bahagia selalu menyertai di setiap perjalanan baru yang akan ditempuh.</p>

                <p>Selamat jalan dan sampai jumpa, semoga kita bisa bertemu lagi di kesempatan yang lebih baik.</p>

                <p><b>Salam hangat, <br>
                PT KAWAN KERJA INDONESIA<b>
                <p>
            <?php endif; ?>
        </div>
    </div>
</div>