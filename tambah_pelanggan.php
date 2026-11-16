<?php
session_start();
include "koneksi.php";


if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

$pesan = "";

if (isset($_POST['tambahpelanggan'])) {
    $namaPelanggan = $_POST["namaPelanggan"];
    $alamat = $_POST["alamat"];
    $nomorTelepon = $_POST["alamat"];
    if ($namaPelanggan == "" || $alamat == "" || $nomorTelepon == "") {
        $pesan = "Nama, alamat, atau nomor tidak boleh kosong";
    } else {
        mysqli_query($connect, "insert into pelanggan (pelangganID, namaPelanggan, alamat, nomorTelepon) values ('','$namaPelanggan','$alamat','$nomorTelepon')");
        header("location: pembelian.php");
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Pelanggan</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input {
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            margin: 5px 0 10px;
        }

        button {
            padding: 10px;
            background: #011157;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #011157;
            color: white;
        }

        a {
            text-decoration: none;
            color: blue;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="tambah_pelanggan.php" method="post">
            <h2>Tambah Pelanggan</h2>
            <p>Silahkan masukkan data pelanggan</p>
            <label for="namaPelanggan">Nama Pelanggan</label>
            <input type="text" name="namaPelanggan" placeholder="Nama Pelanggan">
            <label for="alamat">Alamat</label>
            <input type="text" name="alamat" placeholder="Alamat">
            <label for="nomorTelepon">Nomor Telepon</label>
            <input type="number" name="nomorTelepon" placeholder="Nomor Telepon">
            <p class="pesan">
                <?php echo htmlspecialchars($pesan); ?>
            </p>
            <button type="submit" name="tambahpelanggan">Tambah Pelanggan</button>
        </form>
    </div>
</body>

</html>