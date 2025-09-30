<?php
    include "../koneksi.php";
    $id = $_GET['id'];

    $sql = "UPDATE borrows SET status='Dipinjam'
            WHERE id=$id";
            mysqli_query($koneksi, $sql);
    header("Location: index.php");
?>