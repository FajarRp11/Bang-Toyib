<?php
include ('koneksi.php');

function ribuan($nilai)
{
    return number_format($nilai, 0, ',', '.');
}


// Tambah data pelanggan
if (isset($_POST['tambahPelanggan'])) {
    $ID_Pelanggan = $_POST['ID_Pelanggan'];
    $Nama_Pelanggan = $_POST['Nama_Pelanggan'];

    // Cek data
    $query = "SELECT * FROM `pelanggan` WHERE `ID_Pelanggan` LIKE '$ID_Pelanggan'";
    $hasil = mysqli_query($koneksi, $query);
    $jumlah = mysqli_num_rows($hasil);

    if ($jumlah == 0) {
        $query = "INSERT INTO `pelanggan` (`ID_Pelanggan`, `Nama_Pelanggan`) VALUES ('$ID_Pelanggan', '$Nama_Pelanggan')";
        $tambah = mysqli_query($koneksi, $query);
        if ($tambah) {
            ?>
<script>
alert('Data pelanggan berhasil ditambahkan');
</script>
<?php
            header('location:pelanggan.php');
        } else {
            ?>
<script>
alert('Gagal menambahkan data pelanggan');
</script>
<?php
            header('location:pelanggan.php');
        }
    } else {
        ?>
<script>
alert('ID Pelanggan sudah ada');
</script>
<?php
        header('location:pelanggan.php');
    }
}

// Update data pelanggan
if (isset($_POST['editPelanggan'])) {
    $ID_Pelanggan = $_POST['ID_Pelanggan'];
    $Nama_Pelanggan = $_POST['Nama_Pelanggan'];

    // Query update data
    $query = "UPDATE `pelanggan` SET `Nama_Pelanggan` = '$Nama_Pelanggan' WHERE `ID_Pelanggan` = '$ID_Pelanggan'";
    $edit = mysqli_query($koneksi, $query);
    if ($edit) {
        ?>
<script>
alert('Data pelanggan berhasil diupdate');
</script>
<?php
        header('location:pelanggan.php');
    } else {
        ?>
<script>
alert('Gagal mengupdate data pelanggan');
</script>
<?php
        header('location:pelanggan.php');
    }
}

// Hapus data pelanggan
if (isset($_POST['hapusPelanggan'])) {
    $ID_Pelanggan = $_POST['ID_Pelanggan'];

    $query = "DELETE FROM `pelanggan` WHERE `ID_Pelanggan` = '$ID_Pelanggan'";
    $hapus = mysqli_query($koneksi, $query);
    if ($hapus) {
        ?>
<script>
alert('Data pelanggan berhasil dihapus');
</script>
<?php
        header('location: pelanggan.php');
    } else {
        ?>
<script>
alert('Gagal menghapus data pelanggan');
</script>
<?php
        header('location: pelanggan.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <?php include ("assets/css/maincss.php"); ?>
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include ('components/sidebar.php'); ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
            navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm">
                            <a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                            Pelanggan
                        </li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Pelanggan</h6>
                </nav>
                <div class="collapse justify-content-end navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <ul class="navbar-nav ">
                        <li class="nav-item d-flex align-items-center">
                            <p href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">Admin</span>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                            <h6>Tabel Pelanggan</h6>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal"
                                data-bs-target="#inputPelanggan">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                NO
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nama Item
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aksi
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;

                                        $hasil = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY ID_Pelanggan ASC");
                                        while ($data = mysqli_fetch_assoc($hasil)):
                                            $id = $data['ID_Pelanggan'];
                                            ?>
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm px-3"><?= $no++ ?></h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-sm px-3"><?= $data['Nama_Pelanggan'] ?></h6>
                                            </td>
                                            <td>
                                                <button type="button" class="btn bg-success text-white mb-0"
                                                    data-bs-toggle="modal" data-bs-target="#edit<?= $id ?>">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>
                                                <button type="button" class="btn bg-danger text-white mb-0"
                                                    data-bs-toggle="modal" data-bs-target="#hapus<?= $id ?>">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- MODAL DELETE PELANGGAN -->
                                        <div class="modal fade" id="hapus<?= $id ?>" data-bs-backdrop="static"
                                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus
                                                            Pelanggan</h1>
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form role="form" method="POST">
                                                            <p>Apakah anda yakin ingin menghapus
                                                                <?= $data['Nama_Pelanggan']; ?>?
                                                            </p>
                                                            <div class="mb-3">
                                                                <input id="ID_Pelanggan" name="ID_Pelanggan"
                                                                    type="hidden" class="form-control"
                                                                    placeholder="ID Item" aria-label="Email"
                                                                    aria-describedby="email-addon" value="<?= $id ?>">
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary mx-2"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <input type="submit" name="hapusPelanggan"
                                                                    class="btn bg-info text-white" value="Hapus">
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- MODAL EDIT PELANGGAN -->
                                        <div class="modal fade" id="edit<?= $id ?>" data-bs-backdrop="static"
                                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit
                                                            Pelanggan</h1>
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form role="form" method="POST">
                                                            <input id="ID_Pelanggan" name="ID_Pelanggan" type="hidden"
                                                                class="form-control" placeholder="ID Pelanggan"
                                                                aria-label="Email" aria-describedby="email-addon"
                                                                value="<?= $id ?>">
                                                            <label for="Nama_Pelanggan">Nama Pelanggan</label>
                                                            <div class="mb-3">
                                                                <input id="Nama_Pelanggan" name="Nama_Pelanggan"
                                                                    type="text" class="form-control"
                                                                    placeholder="ID Pelanggan" aria-label="Email"
                                                                    aria-describedby="email-addon"
                                                                    value="<?= $data['Nama_Pelanggan'] ?>">
                                                            </div>

                                                            <div class="d-flex justify-content-end">
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary mx-2"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <input type="submit" name="editPelanggan"
                                                                    class="btn bg-info text-white" value="Simpan">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <?php endwhile ?>
                                    </tbody>
                                </table>
                                <!-- MODAL INPUT PELANGGAN -->
                                <div class="modal fade" id="inputPelanggan" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Input Pelanggan</h1>
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal" aria-label="Close">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form role="form" method="POST">
                                                    <label for="ID_Pelanggan">ID Pelanggan</label>
                                                    <div class="mb-3">
                                                        <input id="ID_Pelanggan" name="ID_Pelanggan" type="text"
                                                            class="form-control" placeholder="ID Pelanggan"
                                                            aria-label="Email" aria-describedby="email-addon">
                                                    </div>
                                                    <label for="Nama_Pelanggan">Nama Pelanggan</label>
                                                    <div class="mb-3">
                                                        <input id="Nama_Pelanggan" name="Nama_Pelanggan" type="text"
                                                            class="form-control" placeholder="Nama Pelanggan"
                                                            aria-label="Email" aria-describedby="email-addon">
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="button" class="btn btn-outline-secondary mx-2"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                        <input type="submit" name="tambahPelanggan"
                                                            class="btn bg-info text-white" value="Tambah">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include ("assets/js/mainjs.php") ?>
</body>