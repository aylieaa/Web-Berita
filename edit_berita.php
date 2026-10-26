```php
<?php
// File: edit_berita.php
include "koneksi.php";

// Validasi ID berita dari URL
if (!isset($_GET['id']) || !ctype_digit($_GET['id']) || (int) $_GET['id'] < 1) {
    die("Error: ID berita tidak valid.");
}

$id_berita = (int) $_GET['id'];
$info = "";

// Proses update berita
if (isset($_POST['edit'])) {
    $id_berita = isset($_POST['hidberita'])
        ? (int) $_POST['hidberita']
        : 0;

    $judul = trim($_POST['judul'] ?? '');
    $kategori = (int) ($_POST['kategori'] ?? 0);
    $status = $_POST['status'] ?? '';
    $headline = trim($_POST['headline'] ?? '');
    $isi_berita = trim($_POST['isi'] ?? '');
    $pengirim = trim($_POST['pengirim'] ?? '');

    // Validasi input
    if (
        $id_berita < 1 ||
        $kategori < 1 ||
        $judul === '' ||
        $headline === '' ||
        $isi_berita === '' ||
        $pengirim === '' ||
        strlen($pengirim) > 60 ||
        !in_array($status, ['publish', 'draft'], true)
    ) {
        $info = "<div class='alert alert-danger'>
                    Data tidak valid. Periksa kembali formulir.
                 </div>";
    } else {
        // Pastikan kategori tersedia
        $stmt_kategori = mysqli_prepare(
            $conn,
            "SELECT id_kategori FROM kategori WHERE id_kategori = ?"
        );

        mysqli_stmt_bind_param($stmt_kategori, "i", $kategori);
        mysqli_stmt_execute($stmt_kategori);

        $hasil_kategori = mysqli_stmt_get_result($stmt_kategori);
        $kategori_ada = mysqli_num_rows($hasil_kategori) > 0;

        mysqli_stmt_close($stmt_kategori);

        if (!$kategori_ada) {
            $info = "<div class='alert alert-danger'>
                        Kategori tidak ditemukan.
                     </div>";
        } else {
            $query_update = "
                UPDATE berita
                SET id_kategori = ?,
                    judul = ?,
                    headline = ?,
                    isi = ?,
                    pengirim = ?,
                    status = ?
                WHERE id_berita = ?
            ";

            $stmt = mysqli_prepare($conn, $query_update);

            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt,
                    "isssssi",
                    $kategori,
                    $judul,
                    $headline,
                    $isi_berita,
                    $pengirim,
                    $status,
                    $id_berita
                );

                if (mysqli_stmt_execute($stmt)) {
                    $info = "<div class='alert alert-success'>
                                Berita berhasil diubah.
                             </div>";
                } else {
                    $info = "<div class='alert alert-danger'>
                                Berita gagal diubah.
                             </div>";
                }

                mysqli_stmt_close($stmt);
            } else {
                $info = "<div class='alert alert-danger'>
                            Query gagal disiapkan.
                         </div>";
            }
        }
    }
}

// Ambil data berita terbaru
$query_berita = "
    SELECT *
    FROM berita
    WHERE id_berita = ?
";

$stmt_berita = mysqli_prepare($conn, $query_berita);
mysqli_stmt_bind_param($stmt_berita, "i", $id_berita);
mysqli_stmt_execute($stmt_berita);

$hasil_query = mysqli_stmt_get_result($stmt_berita);
$berita = mysqli_fetch_assoc($hasil_query);

mysqli_stmt_close($stmt_berita);

if (!$berita) {
    die("Data berita tidak ditemukan.");
}

// Data berita untuk formulir
$id_kategori = (int) $berita['id_kategori'];
$judul = $berita['judul'];
$headline = $berita['headline'];
$isi = $berita['isi'];
$pengirim = $berita['pengirim'];
$tanggal = $berita['tanggal'];
$status = $berita['status'];

// Fungsi untuk mengamankan output HTML
function e($nilai) {
    return htmlspecialchars(
        (string) $nilai,
        ENT_QUOTES,
        'UTF-8'
    );
}

include "header.php";
?>

<div id="main" class="card shadow-sm p-4">

    <h3 class="text-primary mb-3">Edit Berita</h3>

    <?php
    if ($info !== '') {
        echo $info;
    }
    ?>

    <form action="edit_berita.php?id=<?php echo $id_berita; ?>"
          method="post"
          name="input">

        <div class="mb-3 row">
            <label for="judul"
                   class="col-sm-2 col-form-label fw-bold">
                Judul Berita
            </label>

            <div class="col-sm-10">
                <input
                    type="text"
                    class="form-control"
                    name="judul"
                    id="judul"
                    maxlength="100"
                    value="<?php echo e($judul); ?>"
                    required
                >
            </div>
        </div>

        <div class="mb-3 row">
            <label for="kategori"
                   class="col-sm-2 col-form-label fw-bold">
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
                    $query_kat = "
                        SELECT id_kategori, nm_kategori
                        FROM kategori
                        ORDER BY nm_kategori
                    ";

                    $sql_kat = mysqli_query($conn, $query_kat);

                    if ($sql_kat) {
                        while ($hasil_kat = mysqli_fetch_assoc($sql_kat)) {
                            $id_kat = (int) $hasil_kat['id_kategori'];
                            $nama_kat = e($hasil_kat['nm_kategori']);

                            $selected = (
                                $id_kat === $id_kategori
                            ) ? 'selected' : '';

                            echo "<option value='$id_kat' $selected>";
                            echo $nama_kat;
                            echo "</option>";
                        }
                    }
                    ?>

                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="headline"
                   class="col-sm-2 col-form-label fw-bold">
                Headline Berita
            </label>

            <div class="col-sm-10">
                <textarea
                    class="form-control"
                    name="headline"
                    id="headline"
                    rows="3"
                    required
                ><?php echo e($headline); ?></textarea>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="isi"
                   class="col-sm-2 col-form-label fw-bold">
                Isi Berita
            </label>

            <div class="col-sm-10">
                <textarea
                    class="form-control"
                    name="isi"
                    id="isi"
                    rows="6"
                    required
                ><?php echo e($isi); ?></textarea>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="pengirim"
                   class="col-sm-2 col-form-label fw-bold">
                Pengirim
            </label>

            <div class="col-sm-10">
                <input
                    type="text"
                    class="form-control"
                    name="pengirim"
                    id="pengirim"
                    maxlength="15"
                    value="<?php echo e($pengirim); ?>"
                    required
                >
            </div>
        </div>

        <div class="mb-3 row">
            <label for="status"
                   class="col-sm-2 col-form-label fw-bold">
                Status
            </label>

            <div class="col-sm-10">
                <select
                    class="form-select"
                    name="status"
                    id="status"
                >
                    <option value="publish"
                        <?php echo $status === 'publish'
                            ? 'selected' : ''; ?>>
                        Publish
                    </option>

                    <option value="draft"
                        <?php echo $status === 'draft'
                            ? 'selected' : ''; ?>>
                        Draft
                    </option>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-sm-10 offset-sm-2">

                <input
                    type="hidden"
                    name="hidberita"
                    value="<?php echo $id_berita; ?>"
                >

                <input
                    type="submit"
                    name="edit"
                    id="edit"
                    class="btn btn-success"
                    value="Edit Berita"
                >

                <a href="arsip_berita.php"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </div>
        </div>

    </form>

</div>

<?php include "footer.php"; ?>
```