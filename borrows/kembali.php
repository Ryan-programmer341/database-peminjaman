<?php
    include "../koneksi.php";
    $id = $_GET['id'];
    // mysqli_query($koneksi,"DELETE FROM borrows WHERE id=$id");
    $sql = "UPDATE borrows SET status='Dikembalikan'
            WHERE id=$id";
            mysqli_query($koneksi, $sql);
    header("Location: index.php");
?>