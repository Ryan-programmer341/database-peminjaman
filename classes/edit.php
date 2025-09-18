<?php
    include "../koneksi.php";
    include "header.php";


    $id = $_GET['id'];
    $hasil = mysqli_query($koneksi, "SELECT * FROM classes WHERE id=$id");
    $data = mysqli_fetch_assoc($hasil);
?>
<div class="container mb-8">
<h2>EDIT KELAS</h2>
        <form method="POST">
            <div class="mb-2">
                <label>Nama Kelas</label>
                <input type="text" name="name" class="form-control" value="<?= $data['name']?>">
            </div>


            <button type= "submit" class="btn btn-success">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
</div>


<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];


        mysqli_query($koneksi, "UPDATE classes SET name='$name' WHERE id=$id");
        header("location: index.php");
        exit;
    }


    include "../footer.php";
?>