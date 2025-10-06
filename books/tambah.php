<?php
  include "../koneksi.php";
  include "../header.php";


  if($_SERVER["REQUEST_METHOD"] == "POST") {
      $title = $_POST['title'];
      $sinopsis = $_POST['sinopsis'];
      $year = $_POST['year'];
      $publisher = $_POST['publisher'];
      $author = $_POST['author'];

      
      $sql = "INSERT INTO books (title, sinopsis, year, publisher, author)
      values ('$title','$sinopsis','$year','$publisher','$author')";
      mysqli_query($koneksi, $sql);


      header("Location: index.php");
  }
?>

<div class="container">
  
  <h2>Tambah Buku</h2>
  <form method="POST">
    <div class="mb-2">
      <label>Judul</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Sinopsis</label>
      <textarea name="sinopsis" class="form-control"></textarea>
    </div>
    <div class="mb-2">
      <label>Tahun</label>
      <input type="number" name="year" class="form-control">
    </div>
    <div class="mb-2">
      <label>Penerbit</label>
      <input type="text" name="publisher" class="form-control">
    </div>
    <div class="mb-2">
      <label>Pengarang</label>
      <input type="text" name="author" class="form-control">
    </div>
    <button type="Submit" class="btn btn-success">Simpan</button>
    <a href= "index.php" class="btn btn-secondary">Batal</a>
  </form>
</div>

<?php include "../footer.php"; ?>