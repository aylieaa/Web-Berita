<?php
// File: arsip_berita.php
include "koneksi.php";
include "header.php";
?>
<div id="main" class="card shadow-sm p-4">
 <h3 class="text-primary mb-3">Arsip Berita</h3>
 <ol class="list-group list-group-numbered mb-3">
 <?php
 $query = "SELECT A.*, B.nm_kategori
 FROM berita A, kategori B
 WHERE A.id_kategori=B.id_kategori
 ORDER BY A.id_berita DESC";
 $sql = mysqli_query($conn, $query);
 if (mysqli_num_rows($sql) > 0) {
 while ($hasil = mysqli_fetch_array($sql)) {
 $id_berita = $hasil['id_berita'];
 $kategori = stripslashes($hasil['nm_kategori']);
 $judul = stripslashes($hasil['judul']);
 $headline = nl2br(stripslashes($hasil['headline']));
 $pengirim = stripslashes($hasil['pengirim']);
 $tanggal = stripslashes($hasil['tanggal']);
 $status = stripslashes($hasil['status']);
 echo "<li class='list-group-item d-flex justifycontent-between align-items-start border-0 border-bottom py-3'>";
 echo "<div class='ms-2 me-auto'>";
 echo "<div class='fw-bold fs-5'><a
href='berita_lengkap.php?id=$id_berita' class='text-decorationnone'>$judul</a></div>";
 echo "<small class='text-muted'>Berita dikirimkan oleh
<b>$pengirim</b> pada tanggal <b>$tanggal</b> dalam kategori
<b>$kategori</b></small><br>";
 echo "<small>Status: <span class='badge " . ($status ==
'publish' ? 'bg-success' : 'bg-warning text-dark') .
"'>$status</span></small>";
 echo "</div>";
 echo "<div class='ms-3 btn-group btn-group-sm'>";
 echo "<a href='edit_berita.php?id=$id_berita'
class='btn btn-outline-primary'>Edit</a>";
 echo "<a href='delete_berita.php?id=$id_berita'
class='btn btn-outline-danger' onclick=\"return confirm('Apakah
Anda yakin ingin menghapus berita ini?')\">Delete</a>";
 echo "</div>";
 echo "</li>";
 }
 } else {
 echo "<div class='alert alert-info mt-2'>Belum ada arsip
berita.</div>";
 }
 ?>
 </ol>
</div>
<?php include "footer.php"; ?>