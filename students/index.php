<?php
include "../koneksi.php";
include "../header.php";

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

if ($keyword != '') {
    $result = mysqli_query($koneksi, "SELECT students.*,classes.name AS class_name FROM students  LEFT JOIN classes ON students.class_id=classes.id
        WHERE students.name LIKE '%$keyword%'
        OR nis LIKE '%$keyword%'
        OR birthdate LIKE '%$keyword%'
        OR phone_number LIKE '%$keyword%'
        OR classes.name LIKE '%$keyword%'");
} else {
    $result = mysqli_query($koneksi, "SELECT students.*,classes.name AS class_name FROM students LEFT JOIN classes ON students.class_id=classes.id");
}
?>

<div class="container">
    <h2>Daftar Siswa</h2>
    <form method="GET" action="index.php" style="margin-bottom:30px; display:flex; gap:20px;">
        <input type= "text" name="keyword" placeholder="cari Nama / nis / birthdate / phonenumber/ classname"
        value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>"
        style="flex:1; padding:8px; border: 1px solid #cc1111ff; border-radius: 5px">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
    <a href="tambah.php" class="btn btn-primary mb-3">Tambah Siswa</a>
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Nis</th>
            <th>Birthdate</th>
            <th>Phonenumber</th>
            <th>class_name</th>
            <th>Aksi</th>
        </tr>
        <?php
            $no=1; while($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['nis']; ?></td>
            <td><?= $row['birthdate']; ?></td>
            <td><?= $row['phone_number']; ?></td>
            <td><?= $row['class_name']; ?></td>
            <td>
                <a href="edit.php?id=<?=$row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square me-2"></i>Edit</a>
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