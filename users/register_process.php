<?php
    include "../koneksi.php";


$nama = $_POST['nama'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);


$query = "INSERT INTO users (nama, username, password) VALUES ('$nama', '$username', '$password')";
    if (mysqli_query($koneksi, $query)) {

    $user_id = mysqli_insert_id($koneksi);
    $_SESSION['login'] = true;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['nama'] = $nama;

        header("Location: login.php?pesan=daftar_berhasil");
        exit;
    } else {
        echo "Gagal daftar: " . mysqli_error($koneksi);
    }
?>