<?php
include "conn.php";

// Ambil semua data pengajuan dan status verifikasi
$sql = "SELECT 
            p.id AS pengajuan_id,
            p.status_hitung,
            u.nama_lengkap,
            u.id AS user_id,
            p.dibuat_pada AS tanggal_pengajuan,
            IF(v.pengajuan_id IS NULL, 'Belum Diperiksa', 'Dikonfirmasi') AS status_verifikasi,
            IF(v.pengajuan_id IS NULL, 0, 1) AS bool_verifikasi,
            v.riwayat_kriminal,
            v.status_hukum,
            v.id AS verifikasi_id
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
    <title>Perhitungan Fuzzy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
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
            cursor: pointer;
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

        .fuzzy-result {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 3px 8px rgb(0 0 0 / 0.12);
            padding: 1rem 1.5rem;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <nav id="sidebar" aria-label="Sidebar Menu">
        <div class="sidebar-title px-3">Menu</div>
        <div class="nav flex-column flex-md-column flex-row flex-wrap px-2" role="navigation" aria-label="Primary">
            <a href="login_admin.php" class="nav-link">Dashboard</a>
            <a href="login_admin.php?page=data_pemohon" class="nav-link ">Data Pemohon SKCK</a>
            <a href="login_admin.php?page=hitung_fuzzy" class="nav-link active">Perhitungan Fuzzy</a>
            <a href="login_admin.php?page=laporan" class="nav-link">Laporan Pemohon SKCK</a>
            <a href="login_admin.php?page=cetak" class="nav-link">Cetak</a>
            <a href="logout.php" class="nav-link text-danger">Logout</a>
        </div>
    </nav>
    <main id="content" role="main" tabindex="-1">
        <header class="mb-4">
            <h1 class="h3 fw-bold text-primary">Perhitungan Fuzzy</h1>
        </header>

        <section class="bg-white rounded shadow-sm p-4">
            <h2 class="h5 fw-semibold mb-3">
                Pengajuan SKCK yang Sudah Dikonfirmasi
            </h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Tanggal Pengajuan</th>
                            <th scope="col">Status Hitung</th>
                            <th scope="col">Riwayat Kriminal</th>
                            <th scope="col">Status Hukum</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pengajuanTableBody">
                        <!-- Data dapat dimuat dinamis, contoh 3 pengaju -->
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php if($row['bool_verifikasi'] == 0) continue;?>
                            <form method="post" action="admin/simpan_hitung.php" class="form-hitung">
                                <tr>
                                    <td><?= $row['nama_lengkap'] ?></td>
                                    <td class="text-center"><?= $row['tanggal_pengajuan'] ?></td>
                                    <td class="text-center status-hitung"><?= ucwords($row['status_hitung']) ?></td>
                                    <td class="text-center status-hitung">
                                        <input type="number" name="kriminal" class="form-control form-control-sm text-center" value="<?= $row['riwayat_kriminal'] ?>">
                                    </td>
                                    <td class="text-center status-hitung">
                                        <input type="number" name="status" class="form-control form-control-sm text-center" value="<?= $row['status_hukum'] ?>">
                                    </td>
                                    <td class="text-center">
                                        <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                        <input type="hidden" name="verifikasi_id" value="<?= $row['verifikasi_id'] ?>">
                                        <input type="hidden" name="pengajuan_id" value="<?= $row['pengajuan_id'] ?>">
                                        <input type="hidden" name="nama" value="<?= $row['nama_lengkap'] ?>">
                                        <button type="submit" class="btn btn-primary btn-sm">Hitung</button>
                                    </td>
                                </tr>
                            </form>

                        <?php endwhile ?>
                    </tbody>
                </table>
            </div>

            <!-- Area hasil perhitungan -->
            <div id="fuzzyResult" class="fuzzy-result visually-hidden" tabindex="0" aria-live="polite">
                <!-- Hasil akan ditampilkan di sini -->
            </div>
        </section>
    </main>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function hitungSegitiga(x, a, b, c) {
            if (x <= a || x >= c) return 0;
            else if (x === b) return 1;
            else if (x > a && x < b) return (x - a) / (b - a);
            else if (x > b && x < c) return (c - x) / (c - b);
            return 0;
        }

        const fuzzySets = {
            kriminal: {
                rendah: { a: 0, b: 25, c: 50 },
                sedang: { a: 40, b: 55, c: 70 },
                tinggi: { a: 60, b: 80, c: 100 },
            },
            status: {
                bersih: { a: 0, b: 25, c: 50 },
                proses: { a: 40, b: 55, c: 70 },
                masalah: { a: 60, b: 80, c: 100 },
            }
        };

        const fuzzyRules = [
            { kriminal: 'rendah', status: 'bersih', output: 90 },
            { kriminal: 'rendah', status: 'proses', output: 60 },
            { kriminal: 'rendah', status: 'masalah', output: 30 },
            { kriminal: 'sedang', status: 'bersih', output: 60 },
            { kriminal: 'sedang', status: 'proses', output: 60 },
            { kriminal: 'sedang', status: 'masalah', output: 30 },
            { kriminal: 'tinggi', status: 'bersih', output: 60 },
            { kriminal: 'tinggi', status: 'proses', output: 30 },
            { kriminal: 'tinggi', status: 'masalah', output: 30 },
        ];

        function fuzzyHitung(kriminal, status) {
            const derajatKriminal = {};
            const derajatStatus = {};
            for (const label in fuzzySets.kriminal) {
                const { a, b, c } = fuzzySets.kriminal[label];
                derajatKriminal[label] = hitungSegitiga(kriminal, a, b, c);
            }
            for (const label in fuzzySets.status) {
                const { a, b, c } = fuzzySets.status[label];
                derajatStatus[label] = hitungSegitiga(status, a, b, c);
            }

            let numerator = 0;
            let denominator = 0;

            fuzzyRules.forEach(rule => {
                const μ1 = derajatKriminal[rule.kriminal] || 0;
                const μ2 = derajatStatus[rule.status] || 0;
                const α = Math.min(μ1, μ2);
                numerator += α * rule.output;
                denominator += α;
            });

            const hasil = denominator === 0 ? 0 : numerator / denominator;
            let statusKeputusan = "Ditolak";
            if (hasil >= 80) statusKeputusan = "Diterima";
            else if (hasil >= 50) statusKeputusan = "Butuh Evaluasi / Dalam Proses";

            return { hasil, statusKeputusan, derajatKriminal, derajatStatus };
        }

        function tampilkanHasil(nama, kriminal, status, hasilData) {
            const { hasil, statusKeputusan, derajatKriminal, derajatStatus } = hasilData;

            // Tampilkan derajat keanggotaan kriminal
            let kriminalList = "";
            for (const key in derajatKriminal) {
                kriminalList += `<li>${key}: ${derajatKriminal[key].toFixed(2)}</li>`;
            }

            // Tampilkan derajat keanggotaan status
            let statusList = "";
            for (const key in derajatStatus) {
                statusList += `<li>${key}: ${derajatStatus[key].toFixed(2)}</li>`;
            }

            // Tampilkan detail aturan
            let aturanList = "";
            let numerator = 0;
            let denominator = 0;
            fuzzyRules.forEach(rule => {
                const μ1 = derajatKriminal[rule.kriminal] || 0;
                const μ2 = derajatStatus[rule.status] || 0;
                const α = Math.min(μ1, μ2);
                const kontribusi = α * rule.output;

                if (α > 0) {
                    aturanList += `
                        <tr>
                        <td>${rule.kriminal}</td>
                        <td>${rule.status}</td>
                        <td>${α.toFixed(2)}</td>
                        <td>${rule.output}</td>
                        <td>${kontribusi.toFixed(2)}</td>
                        </tr>
                        `;
                }

                numerator += kontribusi;
                denominator += α;
            });

            const nilaiAkhir = denominator === 0 ? 0 : numerator / denominator;

            const detailHTML = `
            <h3>Hasil Fuzzy untuk <strong>${nama}</strong></h3>
            <hr>
            <p><strong>Input:</strong></p>
            <ul>
            <li>Riwayat Kriminal: ${kriminal}</li>
            <li>Status Hukum: ${status}</li>
            </ul>

            <p><strong>Derajat Keanggotaan Riwayat Kriminal:</strong></p>
            <ul>${kriminalList}</ul>

            <p><strong>Derajat Keanggotaan Status Hukum:</strong></p>
            <ul>${statusList}</ul>

            <p><strong>Evaluasi Aturan:</strong></p>
            <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                <th>Kriminal</th>
                <th>Status</th>
                <th>α (min)</th>
                <th>Output</th>
                <th>Kontribusi (α × output)</th>
                </tr>
            </thead>
            <tbody>
                ${aturanList}
            </tbody>
            </table>

            <p><strong>Perhitungan Akhir:</strong></p>
            <ul>
            <li>Numerator (∑ α × output): ${numerator.toFixed(2)}</li>
            <li>Denominator (∑ α): ${denominator.toFixed(2)}</li>
            <li>Nilai Akhir: <strong>${nilaiAkhir.toFixed(2)}</strong></li>
            <li>Keputusan: <strong>${statusKeputusan}</strong></li>
            </ul>
            `;

            const detailEl = document.getElementById("fuzzyResult");
            detailEl.classList.remove("visually-hidden");
            detailEl.focus();
            detailEl.innerHTML = detailHTML;
        }


        document.addEventListener("DOMContentLoaded", () => {
            // Cek apakah ada parameter GET dari URL
            const params = new URLSearchParams(window.location.search);
            const nama = params.get("nama");
            const kriminal = parseFloat(params.get("kriminal"));
            const status = parseFloat(params.get("status"));

            if (nama && !isNaN(kriminal) && !isNaN(status)) {
                // Jalankan perhitungan fuzzy langsung dengan data dari URL
                const hasilData = fuzzyHitung(kriminal, status);
                tampilkanHasil(nama, kriminal, status, hasilData);
            }
        })

        // Event listener untuk tombol (kalau ada)
        //     const buttons = document.querySelectorAll(".btn-hitung");
        //     buttons.forEach((btn) => {
        //         btn.addEventListener("click", (event) => {
        //             const row = event.target.closest("tr");
        //             const nama = row.getAttribute("data-nama");
        //             const kriminal = parseFloat(row.getAttribute("data-kriminal"));
        //             const status = parseFloat(row.getAttribute("data-status"));
        //             const hasilData = fuzzyHitung(kriminal, status);
        //             tampilkanHasil(nama, kriminal, status, hasilData);
        //         });
        //     });
        // });

    </script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ambil semua form dengan class "form-hitung"
    const forms = document.querySelectorAll('.form-hitung');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const formData = new FormData(form);

            // Ambil nilai spesifik
            const riwayatKriminal = formData.get('kriminal');
            const statusHukum = formData.get('status');

            console.log(riwayatKriminal);
            console.log(statusHukum);

            if (!riwayatKriminal || !statusHukum) {
                e.preventDefault();
                alert('Semua nilai wajib diisi!');
            }
        });
    });
});
</script>



    <!-- <script>
        // Fungsi fuzzy Tsukamoto contoh sederhana:
        // Input: usia, catatan hukum (0=baik,1=ada masalah), skor evaluasi (0-100)
        // Output: nilai kelayakan (0-100) dengan status (ditolak, layak, sangat layak)
        function fuzzifikasiUsia(usia) {
            // Fungsi keanggotaan muda (20-40): linear menurun dari 40 ke 20
            let muMuda = usia <= 20 ? 1 : usia >= 40 ? 0 : (40 - usia) / 20;
            // Fungsi keanggotaan tua (30-50): linear naik dari 30 ke 50
            let muTua = usia <= 30 ? 0 : usia >= 50 ? 1 : (usia - 30) / 20;
            return { muMuda, muTua };
        }
        function fuzzifikasiCatatan(catatan) {
            // 0: baik, 1: bermasalah
            return {
                muBersih: catatan === 0 ? 1 : 0,
                muBermasalah: catatan === 1 ? 1 : 0,
            };
        }
        function fuzzifikasiSkor(skor) {
            // skor rendah <= 50, tinggi >= 70, 50-70 linear
            let muRendah = skor <= 50 ? 1 : skor >= 70 ? 0 : (70 - skor) / 20;
            let muTinggi = skor >= 70 ? 1 : skor <= 50 ? 0 : (skor - 50) / 20;
            return { muRendah, muTinggi };
        }
        function ruleEval(usia, catatan, skor) {
            // Ambil fuzzy memberships
            const usiaF = fuzzifikasiUsia(usia);
            const catatanF = fuzzifikasiCatatan(catatan);
            const skorF = fuzzifikasiSkor(skor);
            // Rule contoh (AND = min)
            // R1: Jika usia muda & catatan bersih & skor tinggi => layak tinggi
            const alpha1 = Math.min(
                usiaF.muMuda,
                catatanF.muBersih,
                skorF.muTinggi
            );
            // R2: Jika usia muda & catatan bermasalah & skor rendah => ditolak
            const alpha2 = Math.min(
                usiaF.muMuda,
                catatanF.muBermasalah,
                skorF.muRendah
            );
            // R3: Jika usia tua & catatan bersih & skor tinggi => sangat layak
            const alpha3 = Math.min(usiaF.muTua, catatanF.muBersih, skorF.muTinggi);
            // R4: Jika usia tua & catatan bermasalah & skor rendah => ditolak
            const alpha4 = Math.min(
                usiaF.muTua,
                catatanF.muBermasalah,
                skorF.muRendah
            );
            return { alpha1, alpha2, alpha3, alpha4 };
        }
        function defuzzifikasiTsukamoto(alphas) {
            // Output linear:
            // Layak tinggi = 70 + 30 * α
            // Ditolak = 30 - 30 * α
            // Sangat layak = 90 + 10 * α (contoh beda)
            // Hitung y tiap rule
            const y1 = 70 + 30 * alphas.alpha1;
            // layak tinggi
            const y2 = 30 - 30 * alphas.alpha2;
            // ditolak
            const y3 = 90 + 10 * alphas.alpha3;
            // sangat layak
            const y4 = 30 - 30 * alphas.alpha4;
            // ditolak
            const sumAlphaY =
                alphas.alpha1 * y1 +
                alphas.alpha2 * y2 +
                alphas.alpha3 * y3 +
                alphas.alpha4 * y4;
            const sumAlpha =
                alphas.alpha1 + alphas.alpha2 + alphas.alpha3 + alphas.alpha4;
            const yFinal = sumAlpha === 0 ? 0 : sumAlphaY / sumAlpha;
            return { yFinal, y1, y2, y3, y4 };
        }
        function statusKeputusan(nilai) {
            if (nilai >= 85) return "Sangat Layak";
            if (nilai >= 60) return "Layak";
            return "Ditolak";
        }
        function tampilkanHasil(row, fuzzyData) {
            // Cek jika sudah ada container hasil, hapus dulu
            let detailEl = document.getElementById("fuzzyResult");
            detailEl.classList.remove("visually-hidden");
            detailEl.focus();
            detailEl.innerHTML = ` 
            <h3>Rincian Perhitungan Fuzzy untuk <strong>
                ${fuzzyData.nama}
            </strong></h3> <hr> <h5>Input:</h5> <ul> <li>Usia: ${fuzzyData.usia
                }</li> <li>Catatan Hukum: ${fuzzyData.catatan === 0 ? "Bersih" : "Bermasalah"
                }</li> <li>Skor Evaluasi: ${fuzzyData.skor
                }</li> </ul> <h5>Fuzzifikasi:</h5> <ul> <li>Usia Muda: ${fuzzyData.fuzz.usiaF.muMuda.toFixed(
                    2
                )}</li> <li>Usia Tua: ${fuzzyData.fuzz.usiaF.muTua.toFixed(
                    2
                )}</li> <li>Catatan Bersih: ${fuzzyData.fuzz.catatanF.muBersih.toFixed(
                    2
                )}</li> <li>Catatan Bermasalah: ${fuzzyData.fuzz.catatanF.muBermasalah.toFixed(
                    2
                )}</li> <li>Skor Rendah: ${fuzzyData.fuzz.skorF.muRendah.toFixed(
                    2
                )}</li> <li>Skor Tinggi: ${fuzzyData.fuzz.skorF.muTinggi.toFixed(
                    2
                )}</li> </ul> <h5>Evaluasi Rule (α):</h5> <ul> <li>α1 (Layak Tinggi): ${fuzzyData.alphas.alpha1.toFixed(
                    2
                )}</li> <li>α2 (Ditolak): ${fuzzyData.alphas.alpha2.toFixed(
                    2
                )}</li> <li>α3 (Sangat Layak): ${fuzzyData.alphas.alpha3.toFixed(
                    2
                )}</li> <li>α4 (Ditolak): ${fuzzyData.alphas.alpha4.toFixed(
                    2
                )}</li> </ul> <h5>Output per Rule (y):</h5> <ul> <li>Layak Tinggi: ${fuzzyData.defuzz.y1.toFixed(
                    2
                )}</li> <li>Ditolak (Rule 2): ${fuzzyData.defuzz.y2.toFixed(
                    2
                )}</li> <li>Sangat Layak: ${fuzzyData.defuzz.y3.toFixed(
                    2
                )}</li> <li>Ditolak (Rule 4): ${fuzzyData.defuzz.y4.toFixed(
                    2
                )}</li> </ul> <h5>Hasil Akhir:</h5> <p>Nilai akhir: <strong>${fuzzyData.defuzz.yFinal.toFixed(
                    2
                )}</strong></p> <p>Status keputusan awal: <strong>${statusKeputusan(
                    fuzzyData.defuzz.yFinal
                )}</strong></p> `;
            // Update status hitung di tabel
            row.querySelector(".status-hitung").textContent = "Sudah Dihitung";
        }
        document.addEventListener("DOMContentLoaded", () => {
            const buttons = document.querySelectorAll(".btn-hitung");
            buttons.forEach((btn) => {
                btn.addEventListener("click", (event) => {
                    const row = event.target.closest("tr");
                    const nama = row.getAttribute("data-nama");
                    const usia = parseFloat(row.getAttribute("data-usia"));
                    const catatan = parseInt(row.getAttribute("data-catatanhukum"));
                    const skor = parseFloat(row.getAttribute("data-skor"));
                    // Fuzzifikasi
                    const usiaF = fuzzifikasiUsia(usia);
                    const catatanF = fuzzifikasiCatatan(catatan);
                    const skorF = fuzzifikasiSkor(skor);
                    // Evaluasi rule
                    const alphas = ruleEval(usia, catatan, skor);
                    // Defuzzifikasi
                    const defuzz = defuzzifikasiTsukamoto(alphas);
                    // Tampilkan hasil
                    tampilkanHasil(row, {
                        nama,
                        usia,
                        catatan,
                        skor,
                        fuzz: { usiaF, catatanF, skorF },
                        alphas,
                        defuzz,
                    });
                });
            });
        });
    </script> -->
</body>

</html>