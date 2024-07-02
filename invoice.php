<?php
include ('koneksi.php');

$data = mysqli_query($koneksi, "SELECT * FROM kasir WHERE ID_Kasir = 'KSR1'");
$hasil = mysqli_fetch_assoc($data);


function ribuan($nilai)
{
    return number_format($nilai, 0, ',', '.');
}

$hasilTampil = mysqli_query($koneksi, "SELECT th.*, tk.*, tp.*
                                            FROM
                                                header th
                                            JOIN
                                                kasir tk ON th.ID_Kasir = tk.ID_Kasir
                                            JOIN
                                                pelanggan tp ON th.ID_Pelanggan = tp.ID_Pelanggan
                                            ORDER BY th.ID_INV ASC");

//menambah data menu
if (isset($_POST['tambahMenu'])) {
    $ID_Item = $_POST['ID_Item'];
    $Nama_Item = $_POST['Nama_Item'];
    $Harga_Satuan = $_POST['Harga_Satuan'];

    // Cek data
    $query = "SELECT * FROM `menu` WHERE `ID_Item` LIKE '$ID_Item'";
    $hasil = mysqli_query($koneksi, $query);
    $jumlah = mysqli_num_rows($hasil);

    if ($jumlah == 0) {
        $query = "INSERT INTO `menu` (`ID_Item`, `Nama_Item`, `Harga_Satuan`) VALUES ('$ID_Item', '$Nama_Item', '$Harga_Satuan')";
        $tambah = mysqli_query($koneksi, $query);
        if ($tambah) {
            ?>
<script>
alert('Data menu berhasil ditambahkan');
</script>
<?php
            header('location:menu.php');
        } else {
            ?>
<script>
alert('Gagal menambah data menu');
</script>
<?php
            header('location:menu.php');
        }
    } else {

        header('location:menu.php');
    }
}

//update Data menu
if (isset($_POST['editMenu'])) {
    $ID_Item = $_POST['ID_Item'];
    $Nama_Item = $_POST['Nama_Item'];
    $Harga_Satuan = $_POST['Harga_Satuan'];

    // Query update data
    $query = "UPDATE menu SET Nama_Item = '$Nama_Item', Harga_Satuan ='$Harga_Satuan' WHERE ID_Item = '$ID_Item'";
    $edit = mysqli_query($koneksi, $query);
    if ($edit) {
        ?>
<script>
alert('Berhasil update data menu')
</script>
<?php
        header('location:menu.php');
    } else {
        ?>
<script>
alert('Gagal update data menu')
</script>
<?php
        header('location:menu.php');
    }
}

//menghapus data menu
if (isset($_POST['hapusMenu'])) {
    $ID_Item = $_POST['ID_Item'];

    $query = "DELETE FROM `menu` WHERE ID_Item = '$ID_Item'";
    $hapus = mysqli_query($koneksi, $query);
    if ($hapus) {
        header('location: menu.php');
    } else {
        header('location: menu.php');
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
    <title>Soft UI Dashboard by Creative Tim</title>
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
                            Invoice
                        </li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Invoice</h6>
                </nav>
                <div class="collapse justify-content-end navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <ul class="navbar-nav ">
                        <li class="nav-item d-flex align-items-center">
                            <p href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none"><?= $hasil['Nama_Kasir'] ?></span>
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
                            <h6>Authors table</h6>
                            <!-- Button trigger modal -->
                            <a href="buat-invoice.php" class="btn bg-gradient-dark">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                No. Invoice
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Pelanggan
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Kasir
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aksi
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($data = mysqli_fetch_assoc($hasilTampil)) {
                                            $id = $data['ID_INV'];
                                            ?>
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm px-3">
                                                    <?= $data['Tanggal'] ?>/<?= $data['ID_INV'] ?>
                                                </h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-sm px-3"><?= $data['Tanggal'] ?></h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-sm px-3"><?= $data['Nama_Pelanggan'] ?></h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-sm px-3"><?= $data['Nama_Kasir'] ?></h6>
                                            </td>
                                            <td>
                                                <a href="detail-invoice.php?id=<?= $id ?>"
                                                    class="btn bg-info text-white mb-0">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                </a>
                                                <button type="button" class="btn bg-danger text-white mb-0"
                                                    data-bs-toggle="modal" data-bs-target="#hapus<?= $id ?>">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>

                                            </td>
                                        </tr>
                                        <!-- MODAL DELETE MENU -->
                                        <div class="modal fade" id="hapus<?= $id ?>" data-bs-backdrop="static"
                                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Menu</h5>
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form role="form" method="POST">
                                                            <p>Apakah anda yakin ingin menghapus
                                                                <?= $data['Nama_Item']; ?>?
                                                            </p>
                                                            <div class="mb-3">
                                                                <input id="ID_Item" name="ID_Item" type="hidden"
                                                                    class="form-control" placeholder="ID Item"
                                                                    aria-label="Email" aria-describedby="email-addon"
                                                                    value="<?= $id ?>">
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary mx-2"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <input type="submit" name="hapusMenu"
                                                                    class="btn bg-info text-white" value="Hapus">
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </main>
    <?php include ("assets/js/mainjs.php") ?>

</body>