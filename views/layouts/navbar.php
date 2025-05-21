<nav class="navbar bg-dark border-bottom border-body navbar-expand-lg bg-body-tertiary sticky-top" data-bs-theme="dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="<?= BASE_URL ?>/home">Daily</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= BASE_URL ?>/home">Home</a>
                </li>
                <?php if ($dataUser->access != 'VOLUNTEER'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/history">History</a>
                </li>
                <?php endif ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/profile">Profile</a>
                </li>
                <?php if ($isProjectManager || $isAdmin): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Projects
                    </a>
                    <ul class="dropdown-menu">
                        <?php if ($isAdmin): ?>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/project/add">Tambah Project</a></li>
                        <?php endif ?>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/project">List Project</a></li>
                    </ul>
                </li>
                <?php endif ?>
                <?php if ($isAdmin || $isHR): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Human Resources
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/hr/candidate-requests">Request Candidates</a></li>
                        <li><a class="dropdown-item" href="#">Candidates</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <!-- <li><a class="dropdown-item" href="<?= BASE_URL ?>/warnings">Peringatan</a></li> -->
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/hr/contract-extend">Perpanjang Magang</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/hr/resigns">Pengajuan Resign</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/hr/finalizations">Pengajuan Sertifikat</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/hr/certificate-print">Cetak Sertifikat</a></li>
                        <!-- <li><a class="dropdown-item" href="#">Surveys</a></li>
                        <li><a class="dropdown-item" href="#">Review</a></li>
                        <li><a class="dropdown-item" href="#">Sertificate</a></li> -->
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/intern-finalization">Magang Selesai</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/absences-intern">Absensi Magang</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/absences-volunteer">Absensi Volunteer</a></li>
                    </ul>
                </li>
                <?php endif ?>
                <?php if ($isAdmin): ?>
                <!-- <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= BASE_URL ?>/warnings">Pelanggaran</a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Users
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/user/add">Tambah User</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/user">User Active</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/user/nonactive">User Nonactive</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Roles
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/role/add">Tambah Role</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/role">List Role</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Monitoring
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/monitoring/pic-role">PIC Role</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/monitoring/project-manager">Project Manager</a></li>
                    </ul>
                </li>
                <?php endif ?>
                <?php if ($isSuperAdmin): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Finance
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/finance/kurs-dollar">Kurs Dollar</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/finance/cashflow">Catatan Keuangan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/finance/invoice">Invoice</a></li>
                    </ul>
                </li>
                <?php endif ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administration 
                    </a>
                    <ul class="dropdown-menu">
                        <!-- <li><a class="dropdown-item" href="#">Vacuum Request</a></li> -->
                        <!-- <li><a class="dropdown-item" href="<?= BASE_URL ?>/profile/contract-extend">Pengajuan Perpanjang Magang</a></li> -->
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/profile/resign">Pengajuan Resign</a></li>
                        <!-- <li><hr class="dropdown-divider"></li> -->
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/profile/intership-finalization">Pengajuan Sertifikat</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Others
                    </a>
                    <ul class="dropdown-menu">
                        <li><a target="_BLANK" class="dropdown-item" href="https://drive.google.com/drive/folders/1B0N6Sb0V-PNBbkS_5ZdsE0Fn-oWAI4xC?usp=sharing">Assets</a></li>
                        <li><a target="_BLANK" class="dropdown-item" href="https://docs.google.com/document/d/1dGKBAH_UGVaGQGEZhPJaZOlAidyykFX6/edit?usp=sharing&ouid=117242716899239908221&rtpof=true&sd=true">Company Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a target="_BLANK" class="dropdown-item" href="https://kawankerja.id/gather">Gather</a></li>
                        <li><a target="_BLANK" class="dropdown-item" href="https://kawankerja.id/discord">Discord</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a target="_BLANK" class="dropdown-item" href="https://kawankerja.id/aturan">Aturan</a></li>
                        <li><a target="_BLANK" class="dropdown-item" href="https://kawankerja.id/guideline">Panduan</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/calendar">Kalendar 2025</a></li>
                        <?php if ($isAdmin): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a target="_BLANK" class="dropdown-item" href="https://kawankerja.id/webmail">e-Mail</a></li>
                        <?php endif ?>
                    </ul>
                </li>
                
            </ul>
            <div class="d-flex">
                <a href="<?= BASE_URL ?>/logout" class="btn btn-outline-light">Keluar</a>
            </div>
        </div>
    </div>
</nav>