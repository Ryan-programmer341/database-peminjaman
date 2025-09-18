<?php
    $koneksi = mysqli_connect("localhost", "root", "", "db_library");
    if (!$koneksi) {
        die("koneksi gagal:" . mysqli_connect_error());
    }
?>