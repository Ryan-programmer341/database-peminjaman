<?php
    // untuk memasukkan file lain ke dalam file utama
    include "../koneksi.php";
    include "header.php";

    // kita ingin tahu id atau baris mana yang diedit,update dan delete mana yang akan dicari di database//
    $id = $_GET['id'];
    // untuk menjalankan perintah yang dikirim ke database//
    $result = mysqli_query($koneksi, "SELECT * FROM books WHERE id=$id");
    // mengambil hasil query baris demi baris dalam bentuk array asosiatif//
    $data = mysqli_fetch_assoc($result);
    // mengecek apakah form dikirim pakai metode post.jadi blok kode ini hanya jalan kalau user mensubmit form//
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
        $sinopsis = $_POST['sinopsis'];
        $year = $_POST['year'];
        $publisher = $_POST['publisher'];
        $author = $_POST['author'];

    // menjalankan jika mau update seperti title atau sinopsis dll //
        $sql = "UPDATE books SET
        title='$title',
        sinopsis='$sinopsis',
        year='$year',
        publisher='$publisher',
        author='$author'
        WHERE id=$id";
        mysqli_query($koneksi, $sql);

    // browse akan dialihkan otomatis ke halaman index.php//
        header("Location: index.php");

    }
?>
<div class="container mb-8">
    <h2>Edit Buku</h2>
    <form method="POST">
        <div class="mb-2">
            <label>judul</label>
            <input type="text" name="title" class="form-control" value="<?=$data['title'] ?>"required>
        </div>
        <div class="mb-2">
            <label>Sinopsis</label>
            <textarea name="sinopsis" class="form-control"><?= $data['sinopsis'] ?></textarea>
        </div>
        <div class="mb-2">
            <label>Tahun</label>
            <input type="number" name="year" class="form-control" value="<?= $data['year'] ?>">
        </div>
        <div class="mb-2">
            <label>Penerbit</label>
            <input type="text" name="publisher" class="form-control" value="<?= $data['publisher'] ?>">
        </div>
        <div class="mb-2">
            <label>Pengarang</label>
            <input type="text" name="author" class="form-control" value="<?= $data['author'] ?>">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?php 
    include "../footer.php"; 
?>