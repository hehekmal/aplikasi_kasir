<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit;
}

$pesan = "";

if (isset($_POST["daftar"])) {

    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = "petugas";

    $cek = mysqli_prepare($connect, "SELECT id FROM user WHERE username = ?");
    mysqli_stmt_bind_param($cek, "s", $username);
    mysqli_stmt_execute($cek);

    $hasil = mysqli_stmt_get_result($cek);

    if (mysqli_num_rows($hasil) > 0) {
        $pesan = "Username sudah digunakan";
    } else {
        $query = mysqli_prepare($connect, "INSERT INTO user (username, password, role) VALUES (?, ?, ?)");

        mysqli_stmt_bind_param($query, "sss", $username, $password, $role);

        if (mysqli_stmt_execute($query)) {
            $pesan = "Petugas berhasil didaftarkan";
        } else {
            $pesan = "Registrasi gagal";
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registrasi Petugas</title>
    </head>
</html>
<h2>Registrasi Petugas</h2>
<form method="POST">
    <input type="text" name="username" placeholder="username" required>
    <br><br>
    
    <input type="password" name="password" placeholder="Password" required>
    <br><br>

    <button type="submit" name="daftar">Daftar</button>
</form>

<p><?php echo $pesan; ?></p>

<a href="dashboard.php">Kembali</a>

    </html>
</body>