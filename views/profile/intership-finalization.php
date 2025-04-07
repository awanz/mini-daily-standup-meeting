<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Pengajuan penyelesaian magang</h4>
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
            <?php if (empty($finalization) || (!empty($finalization) && ($finalization->status != "REQUEST" && $finalization->status != "APPROVED"))): ?>
            <?php if (!empty($finalization) && $finalization->status != "REQUEST" && $finalization->status != "APPROVED"){ ?>
                <b>
                    <p>Pengajuan penyelesaian magang terakhir telah di <?= $finalization->status ?>, silahkan ajukan kembali.</p>
                </b>
            <?php } ?>

            <form method="POST" action="<?= BASE_URL ?>/profile/intership-finalization">
                <div class="form-group my-2">
                    <label for="file">Link File Dokumen Laporan Magang (Cukup Link File GDrive).</label><br>
                    <label for="file">Pastikan laporan sudah direview dan diberi tanda tangan oleh leader.</label>
                    <input name="file" type="text" class="form-control" id="file" placeholder="Masukan link file laporan magang" required>
                </div>
                <div class="form-group my-2">
                    <label>Sebelum mengajukan penyelesaian magang, terlebih dahulu mengisi survey.</label><br>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSfNbi0A32vQj1S8mULM5ELZCjHwZh1gyOJDs8Gh19njCf0l9w/viewform" class="btn text-white bg-success" target="_BLANK">Survey</a>
                </div>
                
                <div class="form-group my-2">
                    <label for="survey_status">Sudah mengisi surveys?</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="survey_status" id="survey_yes" value="1" required>
                        <label class="form-check-label" for="survey_yes">Sudah</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="survey_status" id="survey_no" value="0" required>
                        <label class="form-check-label" for="survey_no">Belum</label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-dark my-2">Simpan</button>
                <a href="<?= BASE_URL ?>/profile" class="btn btn-light my-2">Kembali</a>
            </form>

            <?php elseif (!empty($finalization) && $finalization->status == "REQUEST"): ?>
                Pengajuan sedang di proses, untuk konfirmasi bisa info di <br>
                <a href="https://chat.whatsapp.com/Fw6I1j4wVsh7G8wablPBc2" class="btn text-white bg-success" target="_BLANK">Whatsapp Group</a>
                <table class="table table-striped table-bordered table-hover mt-2">
                    <thead >
                        <tr>
                            <th style="width: 300px;">File</th>
                            <th>Survey</th>
                        </tr>
                    </thead >
                    <tbody>
                        <tr>
                            <td>
                                <a href="<?= $finalization->file ?>" class="btn btn-dark" target="_BLANK">File Dokumen Laporan Magang</a>
                            </td>
                            <td><?= $finalization->is_survey == 1 ? 'Sudah isi survey' : 'Belum isi survey' ?></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <form method="POST" action="<?= BASE_URL ?>/profile/intership-finalization/cancel">
                    <button type="submit" class="btn btn-warning my-2">Batalkan Pengajuan</button>
                    <a href="<?= BASE_URL ?>/profile" class="btn btn-light my-2">Kembali</a>
                </form>
            <?php elseif (!empty($finalization) && $finalization->status == "APPROVED"): ?>
                <p><b>Ucapan Perpisahan & Terima Kasih</b></p>

                <p>Dengan ini kami menginformasikan bahwa pengajuan penyelesaian magang dari Saudara/i <b><?= $dataUser->fullname ?></b> telah diterima. Kami mengucapkan terima kasih yang sebesar-besarnya atas kontribusi, dedikasi, dan kerja samanya selama ini.</p>

                <p>Kami mendoakan yang terbaik untuk langkah karier selanjutnya. Semoga sukses dan bahagia selalu menyertai di setiap perjalanan baru yang akan ditempuh.</p>

                <p>Selamat jalan dan sampai jumpa, semoga kita bisa bertemu lagi di kesempatan yang lebih baik.</p>

                <p><b>Salam hangat, <br>
                PT KAWAN KERJA INDONESIA<b>
                <p>
            <?php endif; ?>
        </div>
    </div>
</div>