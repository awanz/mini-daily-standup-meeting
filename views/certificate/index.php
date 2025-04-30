<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $finalization->fullname ?? '-' ?> - Sertifikat Magang Kawan Kerja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <meta name="robots" content="noindex, nofollow">
  <link href="https://kawankerja.id/assets/favicon/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180" />
  <link href="https://kawankerja.id/assets/favicon/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png" />
  <link href="https://kawankerja.id/assets/favicon/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png" />
  <link href="https://kawankerja.id/assets/favicon/favicon.ico" rel="icon" type="image/ico" />
  <link href="https://kawankerja.id/assets/favicon/site.webmanifest" rel="manifest" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
      color: #212529;
    }

    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .card-header {
      background-color: #ffffff;
      font-weight: 600;
      font-size: 1.2rem;
      border-bottom: 1px solid #dee2e6;
    }

    table thead {
      background-color: #e9ecef;
    }

    table tbody tr:hover {
      background-color: #f1f3f5;
    }

    embed {
      border-radius: 0.5rem;
      border: 1px solid #dee2e6;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    h2 {
      font-weight: bold;
      color: #343a40;
    }

    .btn-download {
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="text-center mt-4">Sertifikat <?= $finalization->fullname ?? '-' ?></h2>
  <p class="text-center"><?= $finalization->certificate ?? '-' ?></p>
  <div class="row g-4">
    
    <!-- PDF Sertifikat -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          Sertifikat Peserta
        </div>
        
        <div class="card-body">
          <a href="<?= BASE_URL ?>/certificate-pdf/MDAxNi9LS0kvQUtOLUlUUy9JVi8yMDI1" download class="btn btn-outline-dark btn-download">
            <i class="bi bi-download me-2"></i>Download Sertifikat
          </a>
          <embed src="<?= BASE_URL ?>/certificate-pdf/<?= base64_encode($finalization->certificate) ?? '-' ?>" type="application/pdf" width="100%" height="500px" />
        </div>
      </div>
    </div>

    <!-- Tabel Kehadiran -->
    <div class="col-md-6">
        <div class="card mb-2">
            <div class="card-header">
            Data Magang
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Nama Lengkap</div>
                    <div class="col-sm-8"><?= $finalization->fullname ?? '-' ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Role / Posisi</div>
                    <div class="col-sm-8"><?= $finalization->role_name ?? '-' ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Tanggal Mulai Magang</div>
                    <div class="col-sm-8"><?= $finalization->date_start ?? '-' ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Tanggal Selesai Magang</div>
                    <div class="col-sm-8"><?= $finalization->date_end ?? '-' ?></div>
                </div>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-header">
            Data Kehadiran Meeting
            </div>
            <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Hadir</td>
                    <td><?= $finalization->total_meeting_present ?? '-' ?></td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td><?= $finalization->total_meeting_permission ?? '-' ?></td>
                </tr>
                <tr>
                    <td>Sakit</td>
                    <td><?= $finalization->total_meeting_sick ?? '-' ?></td>
                </tr>
                <tr>
                    <td>Absen</td>
                    <td><?= $finalization->total_meeting_absent ?? '-' ?></td>
                </tr>
                </tbody>
            </table>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-header">
            Data Logbook
            </div>
            <div class="card-body">
            <p>Tercatat ada <b style="font-size: 20px"><?= $totaldaily->total_daily ?? '0' ?></b> logbook yang diisi.</p>
            </div>
        </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
