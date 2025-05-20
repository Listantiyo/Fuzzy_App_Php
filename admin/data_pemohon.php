<?php
include "conn.php";

// Ambil semua data pengajuan dan status verifikasi
$sql = "SELECT 
            p.id AS pengajuan_id,
            u.nama_lengkap,
            p.dibuat_pada AS tanggal_pengajuan,
            IF(v.pengajuan_id IS NULL, 'Belum Diperiksa', 'Dikonfirmasi') AS status_verifikasi,
            IF(v.pengajuan_id IS NULL, 0, 1) AS bool_verifikasi
        FROM pengajuan p
        JOIN user u ON p.user_id = u.id
        LEFT JOIN verifikasi_admin v ON p.id = v.pengajuan_id
        ORDER BY p.dibuat_pada DESC";

$result = mysqli_query($conn, $sql);

?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Pemohon SKCK</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
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

        #detailModal1 .modal-body {
            overflow-y: scroll;
            max-height: 55vh;
        }
    </style>
</head>

<body>
    <nav id="sidebar" aria-label="Sidebar Menu">
        <div class="sidebar-title px-3">Menu</div>
        <div class="nav flex-column flex-md-column flex-row flex-wrap px-2" role="navigation" aria-label="Primary">
            <a href="login_admin.php" class="nav-link">Dashboard</a>
            <a href="login_admin.php?page=data_pemohon" class="nav-link active">Data Pemohon SKCK</a>
            <a href="login_admin.php?page=hitung_fuzzy" class="nav-link">Perhitungan Fuzzy</a>
            <a href="login_admin.php?page=laporan" class="nav-link">Laporan Pemohon SKCK</a>
            <a href="logout.php" class="nav-link text-danger">Logout</a>
        </div>
    </nav>

    <main id="content" role="main" tabindex="-1">
        <header class="mb-4">
            <h1 class="h3 fw-bold text-primary">Data Pengaju</h1>
        </header>

        <!-- Tabel Data Pengaju -->
        <section class="bg-white rounded shadow-sm p-4">
            <h2 class="h5 fw-semibold mb-3">Daftar Pengajuan SKCK</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Tanggal Pengajuan</th>
                            <th scope="col">Status Verifikasi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $ix = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $ix?></td>
                            <td><?php echo $row['nama_lengkap'] ?></td>
                            <td class="text-center"><?php echo $row['tanggal_pengajuan'] ?></td>
                            <td class="text-center"><span class="badge bg-<?php echo $row['bool_verifikasi'] == 1 ? 'success' : 'warning' ?>"><?php echo $row['status_verifikasi'] ?></span></td>
                            <td class="text-center">
                                <a href="login_admin.php?page=detail_pemohon&pid=<?php echo $row['pengajuan_id'] ?>" class="btn btn-info btn-sm" >🔍
                                    Detail</a>
                                <a href="admin/hapus-pengajuan.php?pid=<?php echo $row['pengajuan_id'] ?>" class="btn btn-danger btn-sm" >
                                    Hapus</a>
                            </td>
                        </tr>
                    <?php $ix++; endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Modal Template for Detail Data Pengaju -->

    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>