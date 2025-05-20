<?php
function hitungSegitiga($x, $a, $b, $c)
{
    if ($x <= $a || $x >= $c)
        return 0;
    elseif ($x == $b)
        return 1;
    elseif ($x > $a && $x < $b)
        return ($x - $a) / ($b - $a);
    elseif ($x > $b && $x < $c)
        return ($c - $x) / ($c - $b);
    return 0;
}

function hitung_fuzzy($kriminal, $status)
{
    // Data fuzzy segitiga
    $fuzzy_sets = [
        'riwayat_kriminal' => [
            'rendah' => ['a' => 0, 'b' => 25, 'c' => 50],
            'sedang' => ['a' => 40, 'b' => 55, 'c' => 70],
            'tinggi' => ['a' => 60, 'b' => 80, 'c' => 100],
        ],
        'status_hukum' => [
            'bersih' => ['a' => 0, 'b' => 25, 'c' => 50],
            'dalam_proses' => ['a' => 40, 'b' => 55, 'c' => 70],
            'bermasalah' => ['a' => 60, 'b' => 80, 'c' => 100],
        ]
    ];

    // Nilai crisp
    $diterima = 90;
    $perlu_eval = 60;
    $ditolak = 30;

    // Aturan fuzzy
    $fuzzy_rules = [
        ['kriminal' => 'rendah', 'status' => 'bersih', 'output' => $diterima],
        ['kriminal' => 'rendah', 'status' => 'dalam_proses', 'output' => $perlu_eval],
        ['kriminal' => 'rendah', 'status' => 'bermasalah', 'output' => $ditolak],

        ['kriminal' => 'sedang', 'status' => 'bersih', 'output' => $perlu_eval],
        ['kriminal' => 'sedang', 'status' => 'dalam_proses', 'output' => $perlu_eval],
        ['kriminal' => 'sedang', 'status' => 'bermasalah', 'output' => $ditolak],

        ['kriminal' => 'tinggi', 'status' => 'bersih', 'output' => $perlu_eval],
        ['kriminal' => 'tinggi', 'status' => 'dalam_proses', 'output' => $ditolak],
        ['kriminal' => 'tinggi', 'status' => 'bermasalah', 'output' => $ditolak],
    ];

    // Derajat keanggotaan
    $μ_kriminal = [];
    foreach ($fuzzy_sets['riwayat_kriminal'] as $label => $titik) {
        $μ_kriminal[$label] = hitungSegitiga($kriminal, $titik['a'], $titik['b'], $titik['c']);
    }

    $μ_status = [];
    foreach ($fuzzy_sets['status_hukum'] as $label => $titik) {
        $μ_status[$label] = hitungSegitiga($status, $titik['a'], $titik['b'], $titik['c']);
    }

    // Inferensi & defuzzifikasi
    $numerator = 0;
    $denominator = 0;

    foreach ($fuzzy_rules as $rule) {
        $μ1 = $μ_kriminal[$rule['kriminal']] ?? 0;
        $μ2 = $μ_status[$rule['status']] ?? 0;
        $α = min($μ1, $μ2);
        $numerator += $α * $rule['output'];
        $denominator += $α;
    }

    $hasil = ($denominator == 0) ? 0 : $numerator / $denominator;

    // Kategori hasil
    if ($hasil >= 80)
        $kategori = "Diterima";
    elseif ($hasil >= 50)
        $kategori = "Butuh Evaluasi";
    else
        $kategori = "Ditolak";

    return [
        'skor' => $hasil,
        'kategori' => $kategori
    ];
}
