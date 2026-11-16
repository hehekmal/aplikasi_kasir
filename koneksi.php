<?php
$connect = mysqli_connect("localhost","root","","aplikasi_kasir");

if(!$connect){
    die("Koneksi gagal: " . mysqli_connect_error());

}

?>