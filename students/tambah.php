<?php
    include "../koneksi.php";
    include "../header.php";


    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['title'];
        $nis = $_POST['nis'];
        $birthdate = $_POST['year'];
        $phone_number = $_POST['phone_number'];
        $class_id = $_POST['class_id'];


        $sql = "INSERT INTO students (name, nis, birthdate, phone_number, class_id)
        values ('$name', '$nis', '$birthdate', '$phone_number', '$class_id')";
        mysqli_query($koneksi, $sql);


        header("location: index.php");
    }
?>
<div class="container">
    <h2>Tambah Siswa</h2>
    <form method="POST">
        <div class="mb-2">
            <label>Name</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>nis</label>
            <input type="number" name="nis" class="form-control">
        </div>
        <div class="mb-2">
            <label>birthdate</label>
            <input type="date" name="year" class="form-control">
        </div>
        <div class="mb-2">
            <label>phone_number</label>
            <input type="number" name="phone_number" class="form-control">
        </div>
        <div class="mb-2">
            <label for="class_id" class="form-label">Kelas</label>
            <select name="class_id" id="class_id" class="form-control" required>
                <option value="">PILIH KELAS</option>
                <?php
                    $kelas = mysqli_query($koneksi, "SELECT * FROM classes ORDER BY name ASC");
                    while ($row = mysqli_fetch_assoc($kelas)) {
                        echo "<option value='".$row['id']."'>".$row['name']."</option>";
                    }
                ?>
            </select>
        </div>
        <button type="Submit" class="btn btn-success">Simpan</button>
        <a href= "index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?php 
    include "../footer.php"; 
?> 