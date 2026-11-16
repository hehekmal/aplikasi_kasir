<?php
session_start();
include "koneksi.php";

$pesan = "";

if(isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = mysqli_prepare($connect, "SELECT * FROM user WHERE username = ?");
    mysqli_stmt_bind_param($query, "s", $username);
    mysqli_stmt_execute($query);

    $hasil = mysqli_stmt_get_result($query);
    $data = mysqli_fetch_assoc($hasil);

    if($data){
        if(password_verify($password, $data["password"])){
            $_SESSION["id"]=$data["id"];
            $_SESSION["username"]=$data["username"];
            $_SESSION["role"]=$data["role"];

            header("Location: dashboard.php");
            exit;

        }else{
            $pesan="Password salah";
        }
    }else{
        $pesan="Username tidak ditemukan";
        }
    }
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <title>Login Aplikasi Kasir</title>
        </head>
    </html>

    <h2>Login Aplikasi Kasir</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <br><br>
        <input type="password" name="password" placeholder="Password" required>
        <br><br>
        <button type="submit" name="login">Login</button>
    </form>
    <p><?php echo $pesan; ?></p>
    
        </html>
    </body>

