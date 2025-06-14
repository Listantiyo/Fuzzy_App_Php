-- CREATE TABLE user (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     username VARCHAR(50) NOT NULL,
--     password VARCHAR(50) NOT NULL,
--     nama_lengkap VARCHAR(100),
--     nik VARCHAR(20),
--     tempat_lahir VARCHAR(50),
--     tanggal_lahir DATE,
--     jenis_kelamin ENUM('L', 'P'),
--     agama VARCHAR(20),
--     warganegara ENUM('WNI', 'WNA'),
--     status_perkawinan VARCHAR(50),
--     pekerjaan VARCHAR(100),
--     alamat TEXT,
--     no_hp VARCHAR(20),
--     email VARCHAR(100),
--     foto_ktp VARCHAR(255),
--     dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admin (username, password, nama_lengkap)
VALUES (
    'admin', 
    'admin123', 
    'Admin Desa'
);

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    no_hp VARCHAR(20),
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE user
ADD COLUMN agama VARCHAR(20) AFTER nama_lengkap,
ADD COLUMN kebangsaan VARCHAR(30) AFTER agama,
ADD COLUMN no_ktp VARCHAR(30) AFTER kebangsaan,
ADD COLUMN tempat_lahir VARCHAR(50) AFTER no_ktp,
ADD COLUMN tanggal_lahir DATE AFTER tempat_lahir,
ADD COLUMN jenis_kelamin ENUM('L', 'P') AFTER tanggal_lahir;


INSERT INTO user (username, password, nama_lengkap, email, no_hp, agama, kebangsaan, no_ktp, tempat_lahir, tanggal_lahir, jenis_kelamin)
VALUES (
    'user', 
    'user123', 
    'Admin Desa', 
    'user@mail.co', 
    '081234567890',
    'islam',
    'WNI',
    '1464313216846',
    'Kokii',
    '2025-05-05',
    'L'
);


CREATE TABLE pengajuan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    jenis_pendaftaran VARCHAR(50),
    jenis_keperluan VARCHAR(100),
    detail_keperluan TEXT,
    tingkat_wewenang VARCHAR(100),
    foto_diri VARCHAR(255),
    status_verifikasi ENUM('belum', 'sudah') DEFAULT 'belum',
    status_hitung ENUM('belum', 'sudah') DEFAULT 'belum',
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE keluarga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT,
    hubungan ENUM('ayah', 'ibu'),
    nama VARCHAR(100),
    umur INT,
    agama VARCHAR(20),
    warganegara ENUM('WNI', 'WNA'),
    pekerjaan VARCHAR(100),
    serumah ENUM('ya', 'tidak'),
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id)
);

CREATE TABLE pendidikan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT,
    lokasi_sekolah ENUM('dalam negeri', 'luar negeri'),
    tingkat VARCHAR(50),
    nama_institusi VARCHAR(100),
    tahun_lulus INT(4),
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id)
);

CREATE TABLE pidana (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT,
    pernah_terlibat ENUM('ya', 'tidak'),
    detail_perkara TEXT,
    keputusan TEXT,
    sedang_proses ENUM('ya', 'tidak'),
    kasus_sedang_diproses TEXT,
    sampai_mana TEXT,
    pelanggaran_norma ENUM('ya', 'tidak'),
    detail_norma TEXT,
    proses_norma TEXT,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id)
);

CREATE TABLE fisik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT,
    tinggi INT,
    berat INT,
    tanda_istimewa TEXT,
    warna_kulit VARCHAR(50),
    bentuk_muka VARCHAR(50),
    jenis_rambut VARCHAR(50),
    punya_sidik_jari ENUM('ya', 'tidak'),
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id)
);

CREATE TABLE lampiran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT,
    foto_kk VARCHAR(255),
    foto_akta_ijazah VARCHAR(255),
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id)
);

CREATE TABLE keterangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT,
    riwayat_pekerjaan TEXT,
    negara_dikunjungi TEXT,
    hobi TEXT,
    alamat_mudah_dihubungi TEXT,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id)
);

CREATE TABLE verifikasi_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT,
    riwayat_kriminal VARCHAR(100),
    status_hukum VARCHAR(100),
    tanggal_verifikasi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id)
);

ALTER TABLE verifikasi_admin
ADD COLUMN berada_indonesia_dari DATE AFTER status_hukum,
ADD COLUMN berada_indonesia_sampai DATE AFTER berada_indonesia_dari,
ADD COLUMN berlaku_dari DATE AFTER berada_indonesia_sampai,
ADD COLUMN berlaku_sampai DATE AFTER berlaku_dari,
ADD COLUMN dicetak_di VARCHAR(100) AFTER berlaku_sampai,
ADD COLUMN tanggal_cetak DATE AFTER dicetak_di;

ALTER TABLE verifikasi_admin
MODIFY COLUMN berada_indonesia_dari VARCHAR(20),
MODIFY COLUMN berada_indonesia_sampai VARCHAR(20),
MODIFY COLUMN berlaku_dari VARCHAR(20),
MODIFY COLUMN berlaku_sampai VARCHAR(20),
MODIFY COLUMN tanggal_cetak VARCHAR(20);

CREATE TABLE fuzzy_hasil (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  pengajuan_id INT,
  nama VARCHAR(100),
  kriminal FLOAT,
  status FLOAT,
  nilai_akhir FLOAT,
  keputusan VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

