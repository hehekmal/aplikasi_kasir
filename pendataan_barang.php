
<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION["role"])){
    header("Location: login.php");
    exit;
}

// Menambahkan barang
if(isset($_POST["tambah"])){
    $nama = mysqli_real_escape_string($connect, $_POST["nama"]);
    $harga = $_POST["harga"];
    $stok = $_POST["stok"];

    mysqli_query($connect, "INSERT INTO produk (namaProduk, harga, stok)
    VALUES ('$nama', '$harga', '$stok')");

    header("Location: pendataan_barang.php");
    exit;
}

// Mengedit barang
if(isset($_POST["edit"])){
    $id = $_POST["id"];
    $nama = mysqli_real_escape_string($connect, $_POST["nama"]);
    $harga = $_POST["harga"];
    $stok = $_POST["stok"];

    mysqli_query($connect, "UPDATE produk SET
    namaProduk='$nama', harga='$harga', stok='$stok'
    WHERE produkID='$id'");

    header("Location: pendataan_barang.php");
    exit;
}

// Menghapus barang
if(isset($_POST["hapus"])){
    $id = $_POST["id"];

    mysqli_query($connect, "DELETE FROM produk
    WHERE produkID='$id'");

    header("Location: pendataan_barang.php");
    exit;
}

// Mengambil data barang untuk diedit
$dataEdit = null;

if(isset($_GET["edit"])){
    $id = (int) $_GET["edit"];

    $queryEdit = mysqli_query($connect, "SELECT * FROM produk
    WHERE produkID='$id'");

    $dataEdit = mysqli_fetch_assoc($queryEdit);
}

// Menampilkan data barang
$dataBarang = mysqli_query($connect, "SELECT * FROM produk ORDER BY produkID DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pendataan Barang</title>

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

        th, td {
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

    <h2>Pendataan Barang</h2>

    <p>Kelola data barang yang tersedia di toko.</p>

    <h3><?php echo $dataEdit ? "Edit Barang" : "Tambah Barang"; ?></h3>

    <form method="POST">

        <?php if($dataEdit) { ?>
            <input type="hidden" name="id"
            value="<?php echo $dataEdit["produkID"]; ?>">
        <?php } ?>

        <label>Nama Barang</label>
        <input type="text" name="nama"
        value="<?php echo $dataEdit ? htmlspecialchars($dataEdit["namaProduk"]) : ""; ?>"
        placeholder="Masukkan nama barang" required>

        <label>Harga</label>
        <input type="number" name="harga"
        value="<?php echo $dataEdit ? $dataEdit["harga"] : ""; ?>"
        placeholder="Masukkan harga barang" min="0" step="0.01" required>

        <label>Stok</label>
        <input type="number" name="stok"
        value="<?php echo $dataEdit ? $dataEdit["stok"] : ""; ?>"
        placeholder="Masukkan jumlah stok" min="0" required>

        <?php if($dataEdit) { ?>

            <button type="submit" name="edit">Simpan Perubahan</button>
            <a href="pendataan_barang.php">Batal</a>

        <?php } else { ?>

            <button type="submit" name="tambah">Tambah Barang</button>

        <?php } ?>

    </form>

    <h3>Daftar Barang</h3>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        while($barang = mysqli_fetch_assoc($dataBarang)){
        ?>

        <tr>
            <td><?php echo $no++; ?></td>

            <td><?php echo htmlspecialchars($barang["namaProduk"]); ?></td>

            <td>Rp <?php echo number_format($barang["harga"], 0, ",", "."); ?></td>

            <td><?php echo $barang["stok"]; ?></td>

            <td>
                <a href="pendataan_barang.php?edit=<?php echo $barang["produkID"]; ?>">Edit</a>

                <form method="POST" style="display:inline;"
                onsubmit="return confirm('Yakin ingin menghapus barang ini?')">

                    <input type="hidden" name="id"
                    value="<?php echo $barang["produkID"]; ?>">

                    <button type="submit" name="hapus">Hapus</button>

                </form>
            </td>
        </tr>

        <?php } ?>

    </table>

    <br>

    <a href="dashboard.php">Kembali ke Dashboard</a>

</div>

</body>
</html>
