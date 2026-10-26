<?php
// File: berita_lengkap.php
include "koneksi.php";
include "header.php";
if (isset($_GET['id'])) {
 $id_berita = $_GET['id'];
} else {
 die("Error. No ID selected");
}
$query = "SELECT A.id_berita, B.nm_kategori, A.judul, A.isi,
A.pengirim, A.tanggal
 FROM berita A, kategori B
 WHERE A.id_kategori=B.id_kategori &&
A.id_berita='$id_berita'";
$sql = mysqli_query($conn, $query);
$hasil = mysqli_fetch_array($sql);
if ($hasil) {
 $id_berita = $hasil['id_berita'];
 $kategori = stripslashes($hasil['nm_kategori']);
 $judul = stripslashes($hasil['judul']);
 $isi = nl2br(stripslashes($hasil['isi']));
 $pengirim = stripslashes($hasil['pengirim']);
 $tanggal = stripslashes($hasil['tanggal']);
} else {
 die("Berita tidak ditemukan.");
}
?>
<div id="main" class="card shadow-sm p-4">
 <h2 class="text-primary"><?php echo $judul; ?></h2>
 <p><small class="text-muted">Berita dikirimkan oleh <b><?php
echo $pengirim; ?></b> pada tanggal <b><?php echo $tanggal; ?></b>
dalam kategori <b><?php echo $kategori; ?></b></small></p>
 <hr>
 <div class="mt-3 fs-6 lh-base">
 <p><?php echo $isi; ?></p>
 </div>
 <div class="mt-4">
 <a href="index.php" class="btn btn-secondary btnsm">&laquo; Kembali ke Home</a>
 <a href="arsip_berita.php" class="btn btn-outline-primary
btn-sm">Lihat Arsip Berita</a>
 </div>
</div>
<?php include "footer.php"; ?>