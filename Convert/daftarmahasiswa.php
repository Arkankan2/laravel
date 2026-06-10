<?php 
include 'Conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="filecss/hiasan.css">
    <title>Data Mahasiswa</title>
</head>

<body>

<div class="container">

    <div class="card">

        <div class="card-header">

            <h2>Data Mahasiswa</h2>

            <a href="index.html" class="btn-tambah">
                + Tambah Data
            </a>

        </div>

        <div class="table-wrapper">

            <table class="table">

                <thead>

                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Gender</th>
                        <th>Prodi</th>
                        <th>Jumlah Laporan</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php

                $query = "SELECT * FROM mahasiswa";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {

                ?>

                    <tr>

                        <td><?= $row['Nama_mahasiswa']; ?></td>

                        <td><?= $row['Nim']; ?></td>

                        <td><?= $row['jenis_kelamin']; ?></td>

                        <td><?= $row['prodi']; ?></td>

                        <td>0   </td>

                        <td>

                            <a
                            href="edit.php?id=<?= $row['id_mahasiswa']; ?>"
                            class="btn btn-edit">
                                Edit
                            </a>

                            <a
                            href="delete.php?id=<?= $row['id_mahasiswa']; ?>"
                            class="btn btn-hapus"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                Hapus
                            </a>

                        </td>

                    </tr>

                <?php

                    }

                } else {

                    echo "
                    <tr>
                        <td colspan='9'>
                            Tidak ada data mahasiswa
                        </td>
                    </tr>
                    ";

                }

                ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>