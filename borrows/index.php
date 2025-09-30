<?php
include "../koneksi.php";
include "header.php";


$keyword = isset($_GET['keyword'])? $_GET['keyword'] : '';


if ($keyword !='') {

    $result = mysqli_query($koneksi,"SELECT borrows.id,borrows.student_id,borrows.borrow_date
    borrows.return_date,borrows.status,student.name AS student_name,books.title AS book_title FROM borrows JOIN students ON borrows.student_id = students.id
    WHERE students.name LIKE '%$keyword%'
    OR books.title LIKE '%$keyword%'
    OR borrows.status LIKE '%$keyword%'");
} else {
    $result = mysqli_query($koneksi, "
    SELECT borrows.id,borrows.student_id,
    borrows.borrow_date,
    borrows.return_date,borrows.status,
    students.name AS student_name
    FROM 
    borrows JOIN students ON borrows.student_id = students.id");
}
?>


<div class="container">
    <h2>DAFTAR PEMINJAMAN</H2>
    <form method="GET" action="index.php" style="margin-bottom:30px; display:flex; gap:20px;">
        <input type="text" name="keyword" placeholder="cari Nama peminjam / tanggal pinjaman / pengembalian pinjaman"
        value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>"
        style="flex:1; padding:8px; border: 1px solid #cc1111ff; border-radius: 5px">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
    <a href="tambah.php" class="btn btn-primary mb-3">Tambah Peminjam</a>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
            $nomor =1;
            while ($row = mysqli_fetch_assoc($result)) {
                $result_buku = mysqli_query($koneksi, "
                SELECT books.title as book_name 
                FROM 
                borrow_details LEFT JOIN books ON borrow_details.book_id = books.id where borrow_details.borrow_id='$row[id]'");
        ?>
        <tr>
            <td><?= $nomor++; ?></td>
            <td><?= $row['student_name'] ?></td>
            <td>
                <?php 
                foreach ($result_buku as $rb){
                    echo $rb['book_name'].", ";
                }
                ?>
            </td>
            <td><?= $row['borrow_date'] ?></td>
            <td><?= $row['return_date'] ?: '-' ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <a href="edit.php?id=<?=$row['id'] ?>" class="btn btn-warning btn-sm"><i class = "bi bi-pencil-square me-2"></i>Edit</a>
                <a href="hapus.php?id=<?=$row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus?')"><i class="bi bi-trash me-2"></i>Hapus</a>
                <?php
                if($row['status']=="Dipinjam"){?>
                
                <a href="kembali.php?id=<?=$row['id'] ?>" class="btn btn-danger btn-sm">Kembalikan</a>
                <?php
                }
                ?>
                <!-- <a href="pinjam.php?id=<?=$row['id'] ?>" class="btn btn-danger btn-sm">Dipinjam</a> -->
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