<?php
include "../koneksi.php";
include "../header.php";

function format_tanggal_indo($tanggal) {
    if (empty($tanggal) || $tanggal == '0000-00-00') {
        return '.';
    }


    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $pecah = explode('-', $tanggal);
    return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
}

$keyword = isset($_GET['keyword'])? $_GET['keyword'] : '';


if ($keyword !='') {

    $result = mysqli_query($koneksi,"SELECT borrows.id,borrows.student_id,borrows.borrow_date,
    borrows.return_date,borrows.status,students.name AS student_name,books.title AS book_title FROM borrows JOIN students ON borrows.student_id = students.id
    JOIN borrow_details ON borrows.id = borrow_details.borrow_id
    JOIN books ON borrow_details.book_id = books.id
    WHERE students.name LIKE '%$keyword%'
    OR books.title LIKE '%$keyword%'
    OR borrows.status LIKE '%$keyword%'");
} else {
    $result = mysqli_query($koneksi, "
    SELECT borrows.id,
    borrows.student_id,
    borrows.borrow_date,
    borrows.return_date,
    borrows.status,
    students.name AS student_name,
    books.title AS book_title 
    FROM 
    borrows JOIN students ON borrows.student_id = students.id
    JOIN borrow_details ON borrows.id = borrow_details.borrow_id
    JOIN books ON borrow_details.book_id = books.id
    ");
}
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=report_peminjaman.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
?>

<div class="container mt-5">
    <h2>Daftar Peminjaman</H2>
    <form method="GET" action="index.php" style="margin-bottom:30px; display:flex; gap:20px;">
        <input type="text" name="keyword" placeholder="cari Nama peminjam / tanggal pinjaman / pengembalian pinjaman"
        value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>"
        style="flex:1; padding:8px; border: 1px solid #cc1111ff; border-radius: 5px">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
    <a href="tambah.php" class="btn btn-primary mb-3">Tambah Peminjam</a>
    <a href="export_excel.php" class="btn btn-success mb-3">Export Excel</a>
    <table class="table table-bordered table-striped">
        <tr class="table-dark">
            <th>NO</th>
            <th>Nama</th>
            <th>Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Denda</th>
            <th>Status</th>
            <th style="width:20%">Aksi</th>
        </tr>
        <?php
            $nomor =1;
            while ($row = mysqli_fetch_assoc($result)) {
                $result_buku = mysqli_query($koneksi, "
                SELECT books.title as book_name 
                FROM 
                borrow_details LEFT JOIN books ON borrow_details.book_id = books.id where borrow_details.borrow_id='$row[id]'");


                $denda = 0;

                // memeriksa apakah variabel kosong apa tidak //
                if(!empty($row['return_date']) && $row['status'] != 'Dikembalikan') {
                    $hari_ini = new DateTime();
                    $tanggal_kembali = new DateTime($row['return_date']);

                // cek apakah hari ini sudah lewat dari tanggal pengembalian //
                    if ($hari_ini > $tanggal_kembali) {
                        $selisih = $tanggal_kembali->diff($hari_ini)->days;
                        $denda = $selisih * 1000;
                    }
                }
        ?>
        <tr>
            <td><?= $nomor++; ?></td>
            <td><?= ($row['student_name']) ?></td>
            <td>
                <?php 
                foreach ($result_buku as $rb){
                    echo '<span class="badge bg-primary me-2">'.htmlspecialchars ($rb['book_name']).'</span>';
                }
                ?>
            </td>
            <td><?= format_tanggal_indo ($row['borrow_date']); ?></td>
            <td><?= format_tanggal_indo ($row['return_date']) ?: '-'; ?></td>
            <?php
                $style = ($denda > 0) ? 'color: red; font-weight:bold;' : '';
            ?>
            <td style="<?= $style ?>">Rp <?= number_format($denda, 0, ',','.') ?></td>
            <td>
                <?php 
                if($row['status']=="Dipinjam"){
                
                    echo '<span class="badge bg-danger">Dipinjam</span>';
                
                } else {
                    echo '<span class="badge bg-success">Dikembalikan</span>';
                }
                ?>
                
            </td>
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