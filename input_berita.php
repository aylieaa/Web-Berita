```php
<?php
// File: input_berita.php
include "koneksi.php";
include "header.php";

// Proses simpan data berita
if (isset($_POST['Simpan'])) {
    $judul = trim($_POST['judul']);
    $kategori = (int) $_POST['kategori'];
    $headline = trim($_POST['headline']);
    $isi_berita = trim($_POST['isi']);
    $pengirim = trim($_POST['pengirim']);
    $status = $_POST['status'];

    // Validasi status berita
    if (!in_array($status, ['publish', 'draft'], true)) {
        $info = "<div class='alert alert-danger'>Status berita tidak valid.</div>";
    } else {
        // Query menggunakan prepared statement
        $query = "INSERT INTO berita
                  (id_kategori, judul, headline, isi, pengirim, tanggal, status)
                  VALUES (?, ?, ?, ?, ?, NOW(), ?)";

        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "isssss",
                $kategori,
                $judul,
                $headline,
                $isi_berita,
                $pengirim,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {
                $info = "<div class='alert alert-success'>
                            Berita berhasil ditambahkan.
                         </div>";
            } else {
                $info = "<div class='alert alert-danger'>
                            Berita gagal ditambahkan: " .
                            htmlspecialchars(mysqli_stmt_error($stmt)) .
                         "</div>";
            }

            mysqli_stmt_close($stmt);
        } else {
            $info = "<div class='alert alert-danger'>
                        Query gagal disiapkan.
                     </div>";
        }
    }
}
?>

<div id="main" class="card shadow-sm p-4">
    <h3 class="text-primary mb-3">Input Berita</h3>

    <?php
    if (isset($info)) {
        echo $info;
    }
    ?>

    <form action="" method="post" name="input">

        <div class="mb-3 row">
            <label for="judul" class="col-sm-2 col-form-label fw-bold">
                Judul Berita
            </label>

            <div class="col-sm-10">
                <input
                    type="text"
                    class="form-control"
                    name="judul"
                    id="judul"
                    maxlength="100"
                    placeholder="Input judul berita"
                    required
                >
            </div>
        </div>

        <div class="mb-3 row">
            <label for="kategori" class="col-sm-2 col-form-label fw-bold">
                Kategori
            </label>

            <div class="col-sm-10">
                <select
                    class="form-select"
                    name="kategori"
                    id="kategori"
                    required
                >
                    <option value="">Pilih Kategori</option>

                    <?php
                    $query_kategori = "
                        SELECT id_kategori, nm_kategori
                        FROM kategori
                        ORDER BY nm_kategori
                    ";

                    $sql_kategori = mysqli_query($conn, $query_kategori);

                    if ($sql_kategori) {
                        while ($hasil = mysqli_fetch_assoc($sql_kategori)) {
                            $id = (int) $hasil['id_kategori'];
                            $nama = htmlspecialchars(
                                $hasil['nm_kategori'],
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            echo "<option value='$id'>$nama</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="headline" class="col-sm-2 col-form-label fw-bold">
                Headline Berita
            </label>

            <div class="col-sm-10">
                <textarea
                    class="form-control"
                    name="headline"
                    id="headline"
                    rows="3"
                    placeholder="Input headline berita"
                    required
                ></textarea>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="isi" class="col-sm-2 col-form-label fw-bold">
                Isi Berita
            </label>

            <div class="col-sm-10">
                <textarea
                    class="form-control"
                    name="isi"
                    id="isi"
                    rows="6"
                    placeholder="Input isi berita"
                    required
                ></textarea>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="pengirim" class="col-sm-2 col-form-label fw-bold">
                Pengirim
            </label>

            <div class="col-sm-10">
                <input
                    type="text"
                    class="form-control"
                    name="pengirim"
                    id="pengirim"
                    maxlength="15"
                    placeholder="Nama pengirim"
                    required
                >
            </div>
        </div>

        <div class="mb-3 row">
            <label for="status" class="col-sm-2 col-form-label fw-bold">
                Status
            </label>

            <div class="col-sm-10">
                <select class="form-select" name="status" id="status">
                    <option value="publish">Publish</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-sm-10 offset-sm-2">
                <input
                    type="submit"
                    name="Simpan"
                    id="Simpan"
                    class="btn btn-primary"
                    value="Input Berita"
                >

                <input
                    type="reset"
                    name="reset"
                    id="reset"
                    class="btn btn-secondary"
                    value="Cancel"
                >
            </div>
        </div>

    </form>
</div>

<?php include "footer.php"; ?>
```