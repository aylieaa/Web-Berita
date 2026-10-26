<?php
// File: delete_berita.php
include "koneksi.php";
include "header.php";
if (isset($_GET['id'])) {
 $id_berita = $_GET['id'];
} else {
 die("Error. No ID Selected");
}
?>
<div id="main" class="card shadow-sm p-4">
 <?php
 if (!empty($id_berita) && $id_berita != "") {
 $query = "DELETE FROM berita WHERE id_berita='$id_berita'";
 $sql = mysqli_query($conn, $query);
 if ($sql) {
 echo "<h3 class='text-success mb-3'>Berita berhasil
dihapus</h3>";
 } else {
 echo "<h3 class='text-danger mb-3'>Berita gagal
dihapus: " . mysqli_error($conn) . "</h3>";
 }
 echo "<p>Klik <a href='arsip_berita.php' class='btn btnprimary btn-sm'>di sini</a> untuk kembali ke halaman arsip
berita</p>";
 } else {
 die("Access denied");
 }
 ?>
</div>
<?php include "footer.php"; ?>