<?php $this->layout('layouts/base') ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>Kurs Dollar USD to IDR</h4>
                    <div>
                        <a href="<?= BASE_URL ?>/finance/kurs-dollar/refresh" class="btn btn-dark my-2">HARD RESET</a>
                    </div>
                </div>
            </h5>
            <div class="row justify-content-center mb-4">
                <div class="col-md-6">
                    <div class="card shadow rounded-4">
                    <div class="card-body text-center">
                        <h5 class="card-title">Kurs Hari Ini</h5>
                        <p class="text-muted mb-1">Dari: <strong>USD</strong> &rarr; <strong>IDR</strong></p>
                        <h2 class="display-5 text-success" id="rate-display"><?php 
                            $formatRupiah = 'Rp' . number_format($usdToIdr, 2, '.', ',');

                            echo $formatRupiah; 
                            
                            ?></h2>
                        <p class="text-secondary mb-0" id="date-display">Tanggal: <?= date('d') . ' ' . date('F') . ' ' . date('Y'); ?></p>
                    </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>