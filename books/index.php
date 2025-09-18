
<?php 
  // untuk memasukkan file lain ke dalam file utama
  include "../koneksi.php"; 
  include "header.php";
  // bagian pencari data//
  // ngecek dulu apakah ada data keyword yang dikirim lewar url//
  // isset() cek apakah variabel sudah ada isinya
  $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
  // bagian keyword jika kita ingin mencari yang kita ada di database
  if ($keyword != '') {
    $result = mysqli_query($koneksi, "SELECT * FROM books 
        WHERE title LIKE '%$keyword%' 
        OR author LIKE '%$keyword%' 
        OR publisher LIKE '%$keyword%' 
        OR year LIKE '%$keyword%' ");
  // jika kita nggak mencari atau false maka akan else dan semua database yang ada bakalan tetap muncul semua//
  } else {
      $result = mysqli_query($koneksi, "SELECT * FROM books");
  }
?>

<link rel="stylesheet" href="../style/page.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<div class="container">
  <h2>Daftar Buku</h2>
  <form method="GET" action="index.php" style="margin-bottom:30px; display:flex; gap:20px;">
    <input type+"text" name="keyword" placeholder="cari judul / pengarang / penerbit"
    value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>"
    style="flex:1; padding:8px; border: 1px solid #cc1111ff; border-radius: 5px">
    <button type="submit" class="btn btn-primary">Cari</button>
  </form>
  <a href="tambah.php" class="btn btn-primary mb-3">Tambah Buku</a>
  <table class="table table-bordered">
    <tr>
      <th>No</th>
      <th>Judul</th>
      <th>Pengarang</th>
      <th>Penerbit</th>
      <th>Tahun</th>
      <th>Aksi</th>
    </tr>
    <?php
      // looping(mengulang) untuk ambil semua data hasil query dari database baris demi baris 
      $no=1; while($row = mysqli_fetch_assoc($result)) { 
    ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row['title'] ?></td>
      <td><?= $row['author'] ?></td>
      <td><?= $row['publisher'] ?></td>
      <td><?= $row['year'] ?></td>
      <td>
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square me-2"></i>Edit</a>
        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus bang?')"><i class="bi bi-trash"></i>Hapus</a>
      </td>
    </tr>
    <?php 
      } 
    ?>
  </table>
</div>
<?php 
  include "../footer.php"; 
?>
