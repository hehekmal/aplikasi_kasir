<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

$pesan = "";

if (isset($_POST["beli"])) {

    $pelangganID = (int) $_POST["pelangganID"];
    $produkID = (int) $_POST["produkID"];
    $jumlah = (int) $_POST["jumlahProduk"];

    $cekPelanggan = mysqli_prepare($connect, "SELECT pelangganID FROM pelanggan WHERE pelangganID = ?");
    mysqli_stmt_bind_param($cekPelanggan, "i", $pelangganID);
    mysqli_stmt_execute($cekPelanggan);
    $hasilPelanggan = mysqli_stmt_get_result($cekPelanggan);

    $cekProduk = mysqli_prepare($connect, "SELECT * FROM produk WHERE produkID = ?");

    mysqli_stmt_bind_param($cekProduk, "i", $produkID);
    mysqli_stmt_execute($cekProduk);
    $hasilProduk = mysqli_stmt_get_result($cekProduk);
    $barang = mysqli_fetch_assoc($hasilProduk);

    if (mysqli_num_rows($hasilPelanggan) == 0) {
        $pesan = "ID pelanggan tidak ditemukan";
    } elseif (!$barang) {
        $pesan = "Barang tidak ditemukan";
    } elseif ($jumlah <= 0) {
        $pesan = "Jumlah pembelian harus lebih dari 0";
    } elseif ($barang["stok"] < $jumlah) {
        $pesan = "Stok barang tidak mencukupi";
    } else {
        $total = $barang["harga"] * $jumlah;
        mysqli_begin_transaction($connect);
        $tanggal = date("Y-m-d");

        $query1 = mysqli_prepare($connect, "INSERT INTO penjualan (tanggalPenjualan, totalHarga, pelangganID) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($query1, "sdi", $tanggal, $total, $pelangganID);

        $berhasil1 = mysqli_stmt_execute($query1);

        if ($berhasil1) {

            $penjualanID = mysqli_insert_id($connect);

            $query2 = mysqli_prepare($connect, "INSERT INTO detailpenjualan (penjualanID, produkID, jumlahProduk, subtotal) VALUES (?, ?, ?, ?)");

            mysqli_stmt_bind_param($query2, "iiid", $penjualanID, $produkID, $jumlah, $total);

            $berhasil2 = mysqli_stmt_execute($query2);


            $query3 = mysqli_prepare($connect, "UPDATE produk SET stok = stok - ? WHERE produkID = ? AND stok >= ?");
            mysqli_stmt_bind_param($query3, "iii", $jumlah, $produkID, $jumlah);

            $berhasil3 = mysqli_stmt_execute($query3);

            if (
                $berhasil2 && $berhasil3 &&
                mysqli_stmt_affected_rows($query3) == 1
            ) {

                mysqli_commit($connect);
                $pesan = "Pembelian berhasil disimpan!";
            } else {

                mysqli_rollback($connect);
                $pesan = "Pembelian gagal disimpan!";
            }
        } else {
            mysqli_rollback($connect);
            $pesan = "Transaksi gagal disimpan!";
        }
    }
}

$dataBarang = mysqli_query($connect, "SELECT * FROM produk ORDER BY namaProduk ASC");
$dataPelanggan = mysqli_query($connect, "SELECT * FROM pelanggan ORDER BY namaPelanggan ASC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Pembelian</title>
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
            max-width: 600px;
            margin: auto;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input,
        select {
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            margin-top: 5px;
        }

        .button {
            padding: 10px 20px;
            background: #011157;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
        }

        .pesan {
            margin-top: 15px;
            color: #011157;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Pembelian Barang</h2>
        <p>Silakan masukkan data pembelian</p>
        <div style="text-align: end;">
            <a href="tambah_pelanggan.php" class="button" style="font-size: 13px;">
                Tambah Pelanggan
            </a>
        </div>
        <form method="POST">
            <label>Nama Pelanggan</label>
            <!-- <input type="number" name="pelangganID"
        min="1" required> -->
            <select name="pelangganID">
                <option value="">-- Pilih Pelanggan --</option>

                <?php while ($pelanggan = mysqli_fetch_array($dataPelanggan)) { ?>

                <option value="<?php echo $pelanggan["pelangganID"]; ?>">
                    <?php echo $pelanggan["namaPelanggan"]; ?>
                </option>

                <?php } ?>

            </select>
            <label>Pilih Barang</label>
            <select name="produkID" required>
                <option value="">-- Pilih Barang --</option>

                <?php while ($barang = mysqli_fetch_assoc($dataBarang)) { ?>

                <option value="<?php echo $barang["produkID"]; ?>">
                    <?php echo htmlspecialchars($barang["namaProduk"]); ?>
                    - Rp
                    <?php echo number_format($barang["harga"], 0, ",", "."); ?>
                    (Stok:
                    <?php echo $barang["stok"]; ?>)
                </option>

                <?php } ?>

            </select>

            <label>Jumlah Pembelian</label>
            <input type="number" name="jumlahProduk" min="1" required>

            <button class="button" type="submit" name="beli">
                Simpan Pembelian
            </button>
        </form>

        <p class="pesan">
            <?php echo htmlspecialchars($pesan); ?>
        </p>

        <br>
        <a href="dashboard.php">Kembali ke Dashboard</a>

    </div>

</body>

</html>