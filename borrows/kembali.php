<?php
    include "../koneksi.php";


    $id = $_GET['id'];

    $sql ="SELECT return_date FROM borrows WHERE id=$id";
    $result = mysqli_query($koneksi, $sql);
    $data = mysqli_fetch_assoc($result);


    if (!$data) {
        die("Data peminjam tidak ditemukan!");
    }

    $tanggal_pengembalian = $data['return_date'];

    $tanggal_pengembalian = date('Y-m-d');

    $return_date = $data['return_date'];
    $tanggal_pengembalian = date('Y-m-d');


    $denda = 0;
    if(!empty($return_date)) {
        $tanggal_pengembalian_dr = new DateTime($tanggal_pengembalian);
        $tanggal_kembali_dr = new DateTime($return_date);

    if ($tanggal_pengembalian_dr > $tanggal_kembali_dr) {
        $selisih = $tanggal_kembali_dr->diff($tanggal_pengembalian_dr)->days;
        $denda = $selisih * 1000;
    }
    }

    // mysqli_query($koneksi,"DELETE FROM borrows WHERE id=$id");
    $sql = "UPDATE borrows SET status='Dikembalikan',
            tanggal_pengembalian='$tanggal_pengembalian',
            denda=$denda
            WHERE id=$id";
            mysqli_query($koneksi, $sql);
    header("Location: index.php");
?>