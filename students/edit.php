<?php
    include "../koneksi.php";
    include "header.php";

    $id = $_GET['id'];
    $result = mysqli_query($koneksi, "SELECT * FROM students WHERE id=$id");
    $data = mysqli_fetch_assoc($result);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $nis = $_POST['nis'];
        $birthdate = $_POST['birthdate'];
        $phone_number = $_POST['phone_number'];
        $class_id = $_POST['class_id'];


        $sql = "UPDATE students SET
        name='$name',
        nis='$nis',
        birthdate='$birthdate',
        phone_number='$phone_number',
        class_id='$class_id'
        WHERE id=$id";
        mysqli_query($koneksi, $sql);

        header("location: index.php");
    }
?>
<div class="container mb-8">
    <h2>Edit Data Siswa</h2>
    <form method="POST">
        <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?=$data['name'] ?>"required>
        </div>
        <div class="mb-2">
            <label>Nis</label>
            <input type="text" name="nis" class="form-control" value="<?= $data['nis'] ?>">
        </div>
        <div class="mb-2">
            <label>Birthdate</label>
            <input type="date" name="birthdate" class="form-control" value="<?= $data['birthdate'] ?>">
        </div>
        <div class="mb-2">
            <label>Phone_number</label>
            <input type="text" name="phone_number" class="form-control" value="<?= $data['phone_number'] ?>">
        </div> 
        <div class="mb-2">
            <label>Class_name</label>
            <select name="class_id" class="form-control">
                <?php
                    $kelas = mysqli_query($koneksi, "SELECT * FROM classes ");
                    while ($row = mysqli_fetch_assoc($kelas)) {
                        $selected = ($data['class_id'] == $row['id']) ? "selected" : "";
                        echo "<option value='".$row['id']."' $selected>".$row['name']."</option>";
                    }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?php 
    include "../footer.php"; 
?>