
<?php
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kasir</title>
</head>
<body>

<h2>Dashboard Aplikasi Kasir</h2>

<p>Selamat Datang, <?php echo $_SESSION["username"]; ?></p>

<?php if ($_SESSION["role"] == "admin") { ?>

    <h3>Anda Login sebagai Administrator</h3>
    <p>Menu Admin</p>
    <a href="registrasi.php">Registrasi Petugas</a>

<?php } else { ?>

    <h3>Anda Login sebagai Petugas</h3>
    <p>Menu Petugas</p>

<?php } ?>

<br><br>

<a href="pendataan_barang.php">Pendataan Barang</a>
<br><br>
<a href="pembelian.php">Pembelian</a>
<br><br>
<a href="logout.php">Logout</a>

</body>
</html>
