<?php
    include "../koneksi.php";
    include "../includes/header.php";

    session_start();
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            header("Location: ../index.php");
            exit;
}

?>

<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width: 400px;">
        <div class="card-body">
            <h4 class="text-center mb-3">Login ke Sistem</h4>
            <form action="login_process.php" method="POST">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="text-center mt-3">Belum punya akun? <a href="register.php">Daftar</a></p>
        </div>
    </div>
</div>
<?php
    include "../includes/footer.php";
?>