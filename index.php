<?php include "includes/header.php"; ?>
<?php include "koneksi.php"; ?>

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
    </div>

<?php include "includes/footer.php"; ?>