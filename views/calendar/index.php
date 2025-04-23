<?php $this->layout('layouts/base') ?>
<?php $this->start('headAdditional') ?>
<style>
@media (max-width: 767.98px) {
    .small-on-mobile {
        font-size: 0.7rem; 
    }
}
@media (max-width: 1080px) {
    .small-on-mobile {
        font-size: 0.5rem; 
    }
}
</style>
<?php $this->stop() ?>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <h5 class="card-title mb-4">
                <div class="d-flex justify-content-between">
                    <h4>Kalendar 2025</h4>
                </div>
            </h5>
            <div class="row">
                <div>Kalendar ini berfungsi sebagai acuan jadwal kerja di Kawan Kerja.</div>
                <div class="mb-4">Peserta magang hanya libur pada hari libur dan <b>tidak ada cuti bersama</b>.</div>
                <?php
                $months = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                $specialDates = [
                    '2025-01-01' => 'Tahun Baru 2025 Masehi',
                    '2025-01-27' => 'Isra Mikraj Nabi Muhammad SAW',
                    '2025-01-29' => 'Tahun Baru Imlek 2576 Kongzili',
                    '2025-03-29' => 'Hari Suci Nyepi (Tahun Baru Saka 1947)',
                    '2025-03-31' => 'Idulfitri 1446 Hijriah',
                    '2025-04-01' => 'Idulfitri 1446 Hijriah',
                    '2025-04-18' => 'Wafat Yesus Kristus',
                    '2025-04-20' => 'Kebangkitan Yesus Kristus (Paskah)',
                    '2025-05-01' => 'Hari Buruh Internasional',
                    '2025-05-12' => 'Hari Raya Waisak 2569 BE',
                    '2025-05-29' => 'Kenaikan Yesus Kristus',
                    '2025-06-01' => 'Hari Lahir Pancasila',
                    '2025-06-09' => 'Iduladha 1446 Hijriah',
                    '2025-06-27' => '1 Muharam Tahun Baru Islam 1447 Hijriah',
                    '2025-08-17' => 'Proklamasi Kemerdekaan',
                    '2025-09-05' => 'Maulid Nabi Muhammad SAW',
                    '2025-12-25' => 'Kelahiran Yesus Kristus',
                ];

                $today = date('Y-m-d'); // Tanggal hari ini

                for ($month = 1; $month <= 12; $month++) {
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, 2025);
                    ?>
                    <div class="col-md-4 mb-4 small-on-mobile">
                        <div class="card">
                            <div class="card-header text-center fw-bold">
                                <?= $months[$month - 1] ?> 2025
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr class="text-center">
                                            <th class="bg-danger text-white">Ahad</th>
                                            <th>Sen</th>
                                            <th>Sel</th>
                                            <th>Rab</th>
                                            <th>Kam</th>
                                            <th>Jum</th>
                                            <th>Sab</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $firstDayOfMonth = date('w', strtotime("2025-$month-01"));
                                        $currentDay = 1;

                                        for ($row = 0; $row < 6; $row++) {
                                            echo '<tr class="text-center">';

                                            for ($col = 0; $col < 7; $col++) {
                                                if (($row === 0 && $col < $firstDayOfMonth) || $currentDay > $daysInMonth) {
                                                    echo '<td></td>';
                                                } else {
                                                    $currentDate = sprintf('2025-%02d-%02d', $month, $currentDay);
                                                    $isSunday = $col === 0;
                                                    $isSpecialDate = array_key_exists($currentDate, $specialDates);
                                                    $isToday = $currentDate === $today;

                                                    // Tentukan kelas berdasarkan kondisi
                                                    $class = '';
                                                    if ($isSunday || $isSpecialDate) {
                                                        $class .= ' bg-danger text-white';
                                                    }
                                                    if ($isToday) {
                                                        $class .= ' text-decoration-underline fw-bold';
                                                    }

                                                    echo "<td class='$class'>$currentDay</td>";
                                                    $currentDay++;
                                                }
                                            }

                                            echo '</tr>';

                                            if ($currentDay > $daysInMonth) {
                                                break;
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tampilkan keterangan libur -->
                                <ul class="mt-3 list-unstyled">
                                    <?php
                                    foreach ($specialDates as $date => $description) {
                                        if (date('Y-m', strtotime($date)) === sprintf('2025-%02d', $month)) {
                                            echo "<li><span class='fw-bold'>$date:</span> $description</li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>


        </div>
    </div>
</div>