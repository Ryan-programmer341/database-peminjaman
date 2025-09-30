<?php
include "../koneksi.php";
include "header.php";


$id = $_GET['id'];
$hasil = mysqli_query($koneksi, "SELECT * FROM borrows WHERE id=$id");
$data = mysqli_fetch_assoc($hasil);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $borrow_date = $_POST['borrow_date'];
    $return_date = $_POST['return_date'];
    $status = $_POST['status'];
    $books_id = $_POST['book_id'];


    $sql = "UPDATE borrows SET student_id='$student_id',
            borrow_date='$borrow_date', return_date='$return_date',status='$status'
            WHERE id=$id";
            mysqli_query($koneksi, $sql);

            mysqli_query($koneksi, "DELETE FROM borrow_details WHERE borrow_id='$id'");

            foreach ($books_id as $book_id) {
                $sql = "INSERT INTO borrow_details (borrow_id, book_id)
                VALUES ('$id', '$book_id')";
                mysqli_query($koneksi, $sql);

            }

            header("Location: index.php");
    }
    ?>

<div class="container mb-8">
    <h2>EDIT PINJAMAN</h2>
    <form method="POST">
        <div class="mb-2">
            <label>Nama Siswa</label>
            <select name="student_id" class="form-control">
                <?php
                $students = mysqli_query($koneksi, "SELECT * FROM students");
                while ($row = mysqli_fetch_assoc($students)) {
                    $selected = $row['id'] == $data['student_id'] ? "selected" : "";
                    echo "<option value='".$row['id']."' $selected>".$row['name']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-2">
            <label>Buku</label>
            <select class="js-example-basic-multiple form-control" name="book_id[]" class="form-control" multiple>
                <?php
                $borrow_books = [];
                $result = mysqli_query($koneksi, "SELECT book_id FROM borrow_details WHERE borrow_id='$id'");
                while ($row = mysqli_fetch_assoc($result)) {
                    $borrow_books[] = $row['book_id'];

                }

                $books = mysqli_query($koneksi, "SELECT * FROM books");
                while ($row = mysqli_fetch_assoc($books)) {
                    $selected = in_array($row['id'], $borrow_books) ? "selected" : "";
                    echo "<option value='".$row['id']."' $selected>".$row['title']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-2">
            <label>Tanggal Pinjam</label>
            <input type="date" name="borrow_date" class="form-control" value="<?= $data['borrow_date'] ?>">
        </div>

        <div class="mb-2">
            <label>Tanggal Kembali</label>
            <input type="date" name="return_date" class="form-control" value="<?= $data['return_date'] ?>">
        </div>

        <div class="mb-2">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Dipinjam" <?= $data['status']=="Dipinjam" ? "selected" : "" ?>>Dipinjam</option>
                <option value="Dikembalikan" <?= $data['status']=="Dikembalikan" ? "selected" : "" ?>>Dikembalikan</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php 
    include "../footer.php"; 
?>
