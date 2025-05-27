<?php

include "conn.php";

// Ambil data utama
$q_pengajuan = mysqli_query($conn, 
"SELECT 
  COUNT(keputusan) AS total_semua,
  COUNT(CASE WHEN keputusan = 'Diterima' THEN 1 END) AS total_diterima,
  COUNT(CASE WHEN keputusan = 'Ditolak' THEN 1 END) AS total_ditolak
FROM 
  fuzzy_hasil;

");
$pengajuan = mysqli_fetch_assoc($q_pengajuan);

$sql = "
SELECT 
  fuzzy_hasil.nama,
  fuzzy_hasil.keputusan,
  pg.dibuat_pada AS tanggal_pengajuan
FROM fuzzy_hasil
JOIN pengajuan pg ON pg.id = fuzzy_hasil.pengajuan_id
ORDER BY fuzzy_hasil.created_at DESC LIMIT 5
";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard SKCK</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
            font-family: "Times New Roman", Times, serif;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 220px;
            background: #343a40;
            color: #ced4da;
            display: flex;
            flex-direction: column;
            padding: 1rem 0;
        }

        #sidebar .nav-link {
            color: #adb5bd;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        #sidebar .nav-link.active,
        #sidebar .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }

        #sidebar .sidebar-title {
            font-weight: 700;
            font-size: 1.25rem;
            color: #fff;
            margin-bottom: 1.5rem;
            padding-left: 1.25rem;
        }

        #content {
            margin-left: 220px;
            padding: 2rem 2.5rem;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            #sidebar {
                position: relative;
                width: 100%;
                height: auto;
                flex-direction: row;
                padding: 0.75rem 1rem;
                overflow-x: auto;
                white-space: nowrap;
            }

            #sidebar .sidebar-title {
                padding-left: 0;
                margin-right: 1rem;
            }

            #sidebar .nav-link {
                display: inline-block;
                padding: 0.5rem 1rem;
                margin-right: 0.75rem;
            }

            #content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <nav id="sidebar" aria-label="Sidebar Menu">
        <div class="sidebar-title px-3">Menu</div>
        <div class="nav flex-column flex-md-column flex-row flex-wrap px-2" role="navigation" aria-label="Primary">
            <a href="login_admin.php" class="nav-link active">Dashboard</a>
            <a href="login_admin.php?page=data_pemohon" class="nav-link">Data Pemohon SKCK</a>
            <a href="login_admin.php?page=hitung_fuzzy" class="nav-link">Perhitungan Fuzzy</a>
            <a href="login_admin.php?page=laporan" class="nav-link">Laporan Pemohon SKCK</a>
            <a href="login_admin.php?page=cetak" class="nav-link">Cetak</a>
            <a href="logout.php" class="nav-link text-danger">Logout</a>
        </div>
    </nav>

    <main id="content" role="main" tabindex="-1">
        <header class="mb-4">
            <h1 class="h3 fw-bold text-primary">Dashboard</h1>
        </header>

        <!-- Ringkasan cards -->
        <section class="row g-4">
            <article class="col-12 col-md-4">
                <div class="card shadow-sm text-white bg-primary h-100">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <p class="card-text fs-5 mb-2">Total Pengajuan</p>
                        <h2 class="card-title display-5 fw-bold"><?= $pengajuan['total_semua'] ?></h2>
                    </div>
                </div>
            </article>

            <article class="col-12 col-md-4">
                <div class="card shadow-sm text-white bg-success h-100">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <p class="card-text fs-5 mb-2">Pengajuan Disetujui</p>
                        <h2 class="card-title display-5 fw-bold"><?= $pengajuan['total_diterima']?></h2>
                    </div>
                </div>
            </article>

            <article class="col-12 col-md-4">
                <div class="card shadow-sm text-white bg-danger h-100">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <p class="card-text fs-5 mb-2">Pengajuan Ditolak</p>
                        <h2 class="card-title display-5 fw-bold"><?= $pengajuan['total_ditolak'] ?></h2>
                    </div>
                </div>
            </article>
        </section>

        <!-- Grafik batang -->
        <section class="mt-5 bg-white rounded shadow-sm p-4 mx-auto" style="max-width: 650px;"
            aria-label="Statistik Keputusan Pengajuan">
            <h2 class="h5 fw-semibold mb-3">Statistik Keputusan Pengajuan SKCK</h2>
            <canvas id="decisionChart" aria-describedby="chartDesc" role="img"></canvas>
            <div id="chartDesc" class="visually-hidden">
                Grafik batang yang menunjukkan jumlah pengajuan SKCK yang disetujui dan ditolak.
            </div>
        </section>

        <!-- Tabel 5 pengajuan terakhir -->
        <section class="mt-5 bg-white rounded shadow-sm p-4 mx-auto" style="max-width: 900px;"
            aria-label="5 Pengajuan Terakhir">
            <h2 class="h5 fw-semibold mb-3">5 Pengajuan Terakhir</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th scope="col">Nama Pemohon</th>
                            <th scope="col">Tanggal Pengajuan</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()):?>
                        <tr>
                            <td><?= $row['nama']?></td>
                            <td class="text-center"><?= $row['tanggal_pengajuan'] ?></td>
                            <td class="text-center"><span class="badge bg-<?= $row['keputusan'] == 'Ditolak'? 'danger' : 'success' ?>"><?= $row['keputusan']?></span></td>
                        </tr>
                        <?php endwhile?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('decisionChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Disetujui', 'Ditolak'],
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: [<?= $pengajuan['total_diterima'] ?>, <?= $pengajuan['total_ditolak'] ?>],
                        backgroundColor: ['#198754', '#dc3545'],
                        borderRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ctx.parsed.y + ' pengajuan'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 10 },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>