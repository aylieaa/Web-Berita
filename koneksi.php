<?php
// File: koneksi.php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_pweb1";
// Mengkoneksikan PHP ke server MySQL
$conn = mysqli_connect($host, $user, $pass);
// Memeriksa koneksi dan memilih database db_pw1
if ($conn) {
 $pilihdb = mysqli_select_db($conn, $db);
 if (!$pilihdb) {
 die("Pemilihan database gagal: " . mysqli_error($conn));
 }
} else {
 die("Koneksi ke MySQL gagal: " . mysqli_connect_error());
}
?>