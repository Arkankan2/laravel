<?php
include 'Conn.php';

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id =  $_GET['id'];

$query = "SELECT * FROM mahasiswa WHERE id_mahasiswa = '$id'";

$result = mysqli_query($conn, $query);

$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data mahasiswa tidak ditemukan");
}

$ukm_array = explode(", ", $data['ukm']);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h3>Edit Data Mahasiswa</h3>
        </div>

        <div class="card-body">

            <form action="update.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?= $data['id_mahasiswa']; ?>">

                <!-- Nama -->
                <div class="mb-3">
                    <label class="form-label">Nama Mahasiswa</label>

                    <input type="text"
                           class="form-control"
                           name="Nama_mahasiswa"
                           required
                           value="<?= $data['Nama_mahasiswa']; ?>">
                </div>

                <!-- NIM -->
                <div class="mb-3">
                    <label class="form-label">NIM</label>

                    <input type="number"
                           class="form-control"
                           name="Nim"
                           required
                           value="<?= $data['Nim']; ?>">
                </div>

                <!-- Gender -->
                <div class="mb-3">
                    <label class="form-label d-block">Jenis Kelamin</label>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               type="radio"
                               name="jenis_Kelamin"
                               value="L"
                               <?= $data['jenis_Kelamin'] == 'L' ? 'checked' : ''; ?>>

                        <label class="form-check-label">
                            Laki-laki
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               type="radio"
                               name="jenis_Kelamin"
                               value="P"
                               <?= $data['jenis_Kelamin'] == 'P' ? 'checked' : ''; ?>>

                        <label class="form-check-label">
                            Perempuan
                        </label>
                    </div>
                </div>

                <!-- Tanggal Lahir -->
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>

                    <input type="date"
                           class="form-control"
                           name="tanggal_lahir"
                           value="<?= $data['tanggal_lahir']; ?>">
                </div>

                <!-- Agama -->
                <div class="mb-3">
                    <label class="form-label">Agama</label>

                    <select class="form-select" name="agama">

                        <option value="Islam"
                        <?= $data['agama'] == 'Islam' ? 'selected' : ''; ?>>
                            Islam
                        </option>

                        <option value="Kristen"
                        <?= $data['agama'] == 'Kristen' ? 'selected' : ''; ?>>
                            Kristen
                        </option>

                        <option value="Katolik"
                        <?= $data['agama'] == 'Katolik' ? 'selected' : ''; ?>>
                            Katolik
                        </option>

                        <option value="Hindu"
                        <?= $data['agama'] == 'Hindu' ? 'selected' : ''; ?>>
                            Hindu
                        </option>

                        <option value="Buddha"
                        <?= $data['agama'] == 'Buddha' ? 'selected' : ''; ?>>
                            Buddha
                        </option>

                    </select>
                </div>

                <!-- Prodi -->
                <div class="mb-3">
                    <label class="form-label">Program Studi</label>

                    <select class="form-select" name="prodi">

                        <option value="Ilmu Komputer"
                        <?= $data['prodi'] == 'Ilmu Komputer' ? 'selected' : ''; ?>>
                            Ilmu Komputer
                        </option>

                        <option value="Sistem Informasi"
                        <?= $data['prodi'] == 'Sistem Informasi' ? 'selected' : ''; ?>>
                            Sistem Informasi
                        </option>

                        <option value="Matematika"
                        <?= $data['prodi'] == 'Matematika' ? 'selected' : ''; ?>>
                            Matematika
                        </option>

                        <option value="Sains Data"
                        <?= $data['prodi'] == 'Sains Data' ? 'selected' : ''; ?>>
                            Sains Data
                        </option>

                    </select>
                </div>

                <!-- UKM -->
                <div class="mb-3">
                    <label class="form-label d-block">UKM</label>

                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="ukm[]"
                               value="Seni"
                               <?= in_array('Seni', $ukm_array) ? 'checked' : ''; ?>>

                        <label class="form-check-label">Seni</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="ukm[]"
                               value="olahraga"
                               <?= in_array('olahraga', $ukm_array) ? 'checked' : ''; ?>>

                        <label class="form-check-label">Olahraga</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="ukm[]"
                               value="Robotika"
                               <?= in_array('Robotika', $ukm_array) ? 'checked' : ''; ?>>

                        <label class="form-check-label">Robotika</label>
                    </div>
                </div>

                <!-- Kontak -->
                <div class="mb-3">
                    <label class="form-label">Kontak</label>

                    <input type="number"
                           class="form-control"
                           name="Kontak"
                           value="<?= $data['Kontak']; ?>">
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>

                    <input type="text"
                           class="form-control"
                           name="Sandi"
                           value="<?= $data['Sandi']; ?>">
                </div>

                <!-- Foto -->
                <div class="mb-3">
                    <label class="form-label">Foto Profil</label>

                    <input type="file"
                           class="form-control"
                           name="foto_profil">

                    <small>
                        File saat ini:
                        <?= $data['foto_profil']; ?>
                    </small>

                    <input type="hidden"
                           name="foto_lama"
                           value="<?= $data['foto_profil']; ?>">
                </div>

                <!-- Button -->
                <button type="submit" class="btn btn-success">
                    Update
                </button>

                <button type="reset" class="btn btn-secondary">
                    Reset
                </button>

            </form>

        </div>
    </div>
</div>

</body>
</html>