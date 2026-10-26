<?php
// File: index.php
include "koneksi.php";
include "header.php";
?>
<div id="main" class="card shadow-sm p-4">
 <h3 class="border-bottom pb-2 text-primary">Halaman Depan ~ 5
Berita Terbaru</h3>
 <?php
 // Kueri relasi tabel berita dan kategori
 $query = "SELECT A.id_berita, B.nm_kategori, A.judul,
A.headline, A.pengirim, A.tanggal
 FROM berita A, kategori B
 WHERE A.id_kategori=B.id_kategori AND
A.status='publish'
 ORDER BY A.id_berita DESC LIMIT 0,5";
 $sql = mysqli_query($conn, $query);
 if (mysqli_num_rows($sql) > 0) {
 while ($hasil = mysqli_fetch_array($sql)) {
 $id_berita = $hasil['id_berita'];
 $kategori = stripslashes($hasil['nm_kategori']);
 $judul = stripslashes($hasil['judul']);
 $headline = nl2br(stripslashes($hasil['headline']));
 $pengirim = stripslashes($hasil['pengirim']);
 $tanggal = stripslashes($hasil['tanggal']);

 // Tampilkan daftar berita
 echo "<div class='mb-4 pb-3 border-bottom'>";
 echo "<h4 class='judulberita'><a
href='berita_lengkap.php?id=$id_berita'>$judul</a></h4>";
 echo "<small class='text-muted'>Berita dikirimkan oleh
<b>$pengirim</b> pada tanggal <b>$tanggal</b> dalam kategori
<b>$kategori</b></small>";
 echo "<p class='mt-2 text-secondary'>$headline</p>";
 echo "</div>";
 }
 } else {
 echo "<div class='alert alert-info mt-3'>Belum ada berita
yang dipublish.</div>";
 }
 ?>
</div>
<?php include "footer.php"; ?>