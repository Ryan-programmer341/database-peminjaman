<?php
    include "../koneksi.php";
    session_start();


    $username = $_POST['username'];
    $password = $_POST['password'];


    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($koneksi, $query);


    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);


    if (password_verify($password, $data['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];


        header("Location: ../index.php");
        exit;
    } else {
        header("Location: login.php?pesan=password_salah");
        exit;
    }

    } else {
        header("Location: login.php?pesan=user_tidak_ditemukan");
        exit;
    }
?>