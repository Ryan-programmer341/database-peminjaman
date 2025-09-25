<?php
    include "../koneksi.php";
    include "header.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $student_id = $_POST['student_id'];
        $book_id = $_POST['book_id'];
        $borrow_date = $_POST['borrow_date'];
        $return_date = $_POST['return_date'];


        $sql = "INSERT INTO borrows (student_id, book_id, borrow_date, return_date, status)
        VALUES ('$student_id', '$book_id', '$borrow_date', '$return_date', 'Dipinjam')";
        mysqli_query($koneksi, $sql);


        header("Location: index.php");
    }
?>


<div class="container">
    <h2>Tambah Peminjaman</h2>
    <form method="POST">
    <div class="mb-2">
        <label>Nama Siswa</label>
        <select name="student_id" class="form-control">
                <?php
                $students = mysqli_query($koneksi, "SELECT * FROM students");
                while ($row = mysqli_fetch_assoc($students)) {
                    echo "<option value='".$row['id']."'>".$row['name']."</option>";
                }
                ?>
        </select>
    </div>


    <div class="mb-2">
        <label>Buku</label>
        <select name="book_id" class="form-control">
            <?php
            $books = mysqli_query($koneksi,"SELECT *FROM books");
            while ($row = mysqli_fetch_assoc($books)) {
                echo "<option value='". $row['id']."'>".$row['title']."</option>";
            }
            ?>
        </select>
    </div>


    <div class="mb-2">
        <label>Tanggal Pinjam</label>
        <input type="date" name="borrow_date" class="form-control">
    </div>


    <div class="mb-2">
        <label>Tanggal Kembali</label>
        <input type="date" name="return_date" class="form-control">
    </div>


    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php
    include "../footer.php";
?>