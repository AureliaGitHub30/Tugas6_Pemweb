<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "tugas6";
$port       = 3307;

$koneksi    = mysqli_connect($host, $user, $pass, $db, $port);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
} //else {
//     echo "database terkoneksi";
// }
$NIM        = "";
$Nama       = "";
$Alamat     = "";
$sukses     = "";
$error      = "";

if(isset($_GET['op'])){
    $op = $_GET['op'];
} else {
    $op = "";
}
if($op == 'delete'){
    $id     = $_GET['id_mahasiswa'];
    $sql1   = "DELETE from data_mahasiswa where id_mahasiswa = '$id' ";
    $q1     = mysqli_query($koneksi, $sql1);
    if($q1){
        $sukses     ="Berhasil hapus data";
    } else{
        $error      = "Gagal melakukan delete data";
    }
}

if($op == 'edit'){
    $id     = $_GET['id_mahasiswa'];
    $sql1   = "SELECT * from data_mahasiswa WHERE id_mahasiswa = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    if($q1){
        $r1         = mysqli_fetch_array($q1);
        $NIM        = $r1['NIM'];
        $Nama       = $r1['Nama'];
        $Alamat     = $r1['Alamat'];
        
        if($NIM == ''){
            $error = "Data tidak ditemukan";
        }
    } else{
        $error = "Query error: " . mysqli_error($koneksi);
    }
}

if(isset($_POST['simpan'])){
    $NIM        = $_POST['NIM'];
    $Nama       = $_POST['Nama'];
    $Alamat     = $_POST['Alamat'];
    $id_mahasiswa = isset($_GET['id']) ? $_GET['id'] : null;

    if($NIM && $Nama && $Alamat){
        if($op == 'edit'){
            $sql1   = "UPDATE data_mahasiswa SET nim = '$NIM', nama = '$Nama', alamat = '$Alamat' WHERE id_mahasiswa='$id'";
            $q1     = mysqli_query($koneksi, $sql1);
            $sukses = $q1 ? "Data berhasil diupdate" : "Data gagal diupdate";

        } else{
            $sql1   = "INSERT into data_mahasiswa (NIM, Nama, Alamat) VALUES ('$NIM', '$Nama', '$Alamat')";
            $q1     = mysqli_query($koneksi, $sql1);
            $sukses = $q1 ? "Data berhasil ditambahkan" : "Data gagal ditambahkan";
        }
    } else {
        $error      = "Silakan masukkan semua data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="mx-auto">
        <!--insert data-->
        <div class="card">
            <div class="card_header">
                Create/Edit Data
            </div>
            <div class="card_body">
                <?php
                if($error){
                ?>
                    <div class="alert alert-danger" role="alert">
                    <?php echo $error?>
                    </div>
                <?php
                    header("refresh:5; url =tugas6_pemweb.php");
                }
                ?>
                <?php
                if($sukses){
                ?>
                    <div class="alert alert-success" role="alert">
                    <?php echo $sukses?>
                    </div>
                <?php
                    header("refresh:5; url =tugas6_pemweb.php");
                }
                ?>
                <form action="" method="post">
                    <div class="mb_3row">
                        <label for="NIM" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="NIM" name="NIM" value="<?php echo $NIM ?>">
                        </div>
                    </div>
                    <div class="mb_3row">
                        <label for="Nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="Nama" name="Nama" value="<?php echo $Nama ?>">
                        </div>
                    </div>
                    <div class="mb_3row">
                        <label for="Alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="Alamat" name="Alamat" value="<?php echo $Alamat ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    </div>
                </form>
            </div> 
        </div>

        <!-- Show data-->
        <div class="card">
            <div class="card_header">
                Data Mahasiswa
            </div>
            <div class="card_body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   ="SELECT*from data_mahasiswa order by id_mahasiswa desc";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   =1;
                        while ($r2 = mysqli_fetch_array($q2)){
                            $id         =$r2['id_mahasiswa'];
                            $NIM        =$r2['NIM'];
                            $Nama       =$r2['Nama'];
                            $Alamat     =$r2['Alamat'];
                        
                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++?></th>
                                <td scope="row"><?php echo $NIM?></td>
                                <td scope="row"><?php echo $Nama?></td>
                                <td scope="row"><?php echo $Alamat?></td>
                                <td scope="row">
                                    <a href="tugas6_pemweb.php?op=edit&id_mahasiswa=<?php echo $id?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <a href="tugas6_pemweb.php?op=delete&id_mahasiswa=<?php echo $id?>" onclick="return confirm('Yakin mau deete data?')">
                                        <button type="button" class="btn btn-danger">Delete</button>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>