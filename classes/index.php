<?php
    include "../koneksi.php";
    include "header.php";


    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';


    if ($keyword !='') {
        $hasil = mysqli_query($koneksi, "SELECT * FROM classes WHERE name LIKE '%$keyword%' ");
    } else {
        $hasil = mysqli_query($koneksi, "SELECT * FROM classes");
    }
?>


<div class="container">
    <h2> Daftar Kelas</h2>
    <form method="GET" action="index.php" style="margin-bottom:30px; display:flex; gap:20px;">
        <input type="text" name="keyword" placeholder="cari nama kelas"
        value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>"
        style="flex:1; padding:8px; border: 1px solid #cc1111ff; border-radius: 5px">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
    <a href= "tambah.php" class="btn btn-primary mb-3"> Tambah Kelas</a>
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Nama kelas</th>
            <th>Aksi</th>
        </tr>
        <?php
            $no= 1;
            while($row = mysqli_fetch_assoc($hasil)) {
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?=$row['name'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class = "bi bi-pencil-square me-2"></i>Edit</a>
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus kelas ini?')"><i class="bi bi-trash"></i>Hapus</a>
                    </td>
                </tr>
                <?php
            }
        ?>
    </table>
</div>


    <?php
    include"../footer.php";
    ?>