
<?php
    session_start();

    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header("Location: users/login.php?pesan=belum_login");
        exit;
    }


    include "includes/header.php"; 
    include "koneksi.php"; 

    if (isset($_GET['pesan']) && $_GET['pesan'] == 'akun_sukses') {
        echo "<div class='alert alert-success text-center'>Akun berhasil dibuat dan Anda telah login!</div>";
    }
?>

<div class="container mt-5">
        <h2 class="text-center mb-3 fw-bold">Selamat Datang di Web Peminjaman Buku</h2>

        <h2 class="text-center mb-4 fw-semibold">Widget Statistik</h2>


    <div class="row justify-content-center g-4">


        <div class="col-md-5 card p-3 m-2 text-center">
            <h4 class="fw-semibold"><i class="bi bi-person me-2"></i>DATA SISWA</H4>
            <?php
                $total_siswa_query = mysqli_query($koneksi,"SELECT COUNT(*) AS total_siswa FROM students");
                $total_siswa = mysqli_fetch_assoc($total_siswa_query)['total_siswa'];
            ?>
                <H2><?php echo $total_siswa; ?></h2>
                <p class ="fw-semibold">Total Siswa</p>
        </div>

        <div class="col-md-5 card p-3 m-2 text-center">
            <h4 class="fw-semibold"><i class="bi bi-cash me-2"></i>TOTAL UANG DENDA</h4>
            <?php
                $denda = mysqli_query($koneksi, "
                SELECT SUM( b.denda) AS total_uang_denda
                FROM borrows b
                JOIN students s ON b.student_id = s.id
                WHERE b.denda > 0
                ");
                $total_denda = mysqli_fetch_assoc($denda)['total_uang_denda'];
            ?>

            <H2 class="fw-bold display-6 text-success mt-3">
                <?php echo 'Rp' . number_format($total_denda, 0, ',', '.'); ?>
            </h2>
        </div>
    </div>

    <div class="row justify-content-center g-4 mt-2">

        <div class="col-md-5 card p-3 m-2 text-center">
            <h4 class="fw-semibold"><i class="bi bi-book me-2"></i>TOP 3 BUKU SERING DIPINJAM<h4>
                <?php
                $top_buku = mysqli_query($koneksi, "
                SELECT b.title AS judul_buku, COUNT(br.book_id) AS total_dipinjam
                FROM borrow_details br JOIN books b ON br.book_id = b.id
                GROUP BY br.book_id
                ORDER BY total_dipinjam DESC
                LIMIT 3
                ");
                ?>
                <table class="table table-bordered text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($top_buku)) { ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['judul_buku']); ?></td>
                                <td><?= $row['total_dipinjam']; ?>x</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
        </div>

    <div class="col-md-5 card p-3 m-2 text-center">
        <h4 class="fw-semibold"><i class="bi bi-people me-2"></i>TOP 3 SISWA PEMINJAM TERBANYAK</h4>
        <?php
        $top_siswa = mysqli_query($koneksi, "
            SELECT s.name AS nama_siswa, COUNT(b.id) AS total_pinjam
            FROM borrows b
            JOIN students s ON b.student_id = s.id
            GROUP BY b.student_id
            ORDER BY total_pinjam DESC
            LIMIT 3
        ");
        ?>
        <table class="table table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Pinjam</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($top_siswa)) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                        <td><?= $row['total_pinjam']; ?>x</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>


<?php include "includes/footer.php"; ?>