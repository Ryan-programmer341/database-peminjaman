<?php
include "../koneksi.php";
include "../header.php";
?>

<link link rel="stylesheet" href="../style/page.css">
<h2>Tambah Kelas Baru</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Kelas</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <button type="submit" name="submit" class="btn btn-success">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php
if (isset($_POST['submit'])) {
    $name = $_POST['name'];


    $sql = "INSERT INTO classes (name) VALUES ('$name')";
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
            alert('Data Berhasil Disimpan!');
            window.location.href = 'index.php';
            </script>";
    } else {
        echo "<script>
            alert('Error: " . mysqli_error($koneksi) . "');
        window.location.href = 'index.php';
        </script>";
    }
}
?>

<?php
    include "../footer.php";
?>