<?php
include ('koneksi.php');

if (!isset($_GET['id'])) {
    echo '<script>alert("Data Tidak Di Temukan");history.go(-1);</script>';
    exit; // Pastikan untuk keluar dari skrip jika id tidak ada
}

$getID = $_GET['id'];

$data = mysqli_query($koneksi, "SELECT * FROM kasir WHERE ID_Kasir = 'KSR1'");
$hasil = mysqli_fetch_assoc($data);

function ribuan($nilai)
{
    return number_format($nilai, 0, ',', '.');
}

$queryDetail = "SELECT th.*, td.*, tp.*, tk.*, tm.* 
                FROM header th
                JOIN detail td ON th.ID_INV = td.ID_INV
                JOIN menu tm ON tm.ID_Item = td.ID_Item
                JOIN pelanggan tp ON th.ID_Pelanggan = tp.ID_Pelanggan 
                JOIN kasir tk ON th.ID_Kasir = tk.ID_Kasir
                WHERE th.ID_INV = '$getID'
                ORDER BY th.ID_INV ASC";
$hasilDetail = mysqli_query($koneksi, $queryDetail);
if (!$hasilDetail) {
    die("Query Detail Error: " . mysqli_error($koneksi));
}

$cekHeader = mysqli_query($koneksi, "SELECT th.*, tp.*, tk.* 
                                     FROM header th
                                     JOIN pelanggan tp ON th.ID_Pelanggan = tp.ID_Pelanggan 
                                     JOIN kasir tk ON th.ID_Kasir = tk.ID_Kasir
                                     WHERE th.ID_INV = '$getID'");
if (!$cekHeader) {
    die("Query Header Error: " . mysqli_error($koneksi));
}

$dataHeader = mysqli_fetch_assoc($cekHeader);
if (!$dataHeader) {
    echo '<script>alert("Data Tidak Di Temukan");history.go(-1);</script>';
    exit;
}

$tanggal_obj = DateTime::createFromFormat('Y-m-d', $dataHeader['Tanggal']);
$tanggal_id = $tanggal_obj->format('Ymd');
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
                            Invoice
                        </li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Buat Invoice</h6>
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

        <div class="container-fluid bg-white rounded py-4" id="container-wrapper">
            <div class="text-right d-flex justify-content-end">
                <a href="invoice.php">
                    <button class="btn btn-primary mb-2">
                        <i class="fa fa-arrow-left fa-xs"></i> Kembali
                    </button>
                </a>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <h6 class="mb-1 text-xs font-weight-bold mb-0 px-3"><strong>Invoice :
                            <?= $dataHeader['Tanggal'] ?>/<?= $dataHeader['ID_INV'] ?></strong>
                    </h6>
                    <p class=" mb-0 text-xs font-weight-bold mb-0 px-3">Tanggal : <?= $dataHeader['Tanggal'] ?></p>
                </div>
                <div class="col-sm-6">
                    <p class="text-xs font-weight-bold mb-0 px-3">Kasir : <?= $dataHeader['Nama_Kasir'] ?></p>
                    <p class="text-xs font-weight-bold mb-0 px-3">Pelanggan : <?= $dataHeader['Nama_Pelanggan'] ?></p>
                </div>
            </div>
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NO</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Nama Menu</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Jumlah</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Harga</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                SUbtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $totalKeseluruhan = 0;
                        $jumlahBayar = 0;
                        while ($detailInvoice = mysqli_fetch_array($hasilDetail)) {
                            $subtotal = $detailInvoice['Jumlah_Item'] * $detailInvoice['Harga_Satuan'];
                            $totalKeseluruhan += $subtotal;
                            $jumlahBayar = $dataHeader['Jumlah_Bayar'];
                            ?>
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-3"><?= $no++ ?></p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-3"><?= $detailInvoice['Nama_Item'] ?></p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-3"><?= $detailInvoice['Jumlah_Item'] ?></p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-3">Rp.
                                        <?= ribuan($detailInvoice['Harga_Satuan']) ?>
                                    </p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-3">Rp. <?= ribuan($subtotal) ?></p>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex align-items-end flex-column">
                <p class="text-xs font-weight-bold mb-3 px-3"> Total :
                    Rp. <?= ribuan($totalKeseluruhan) ?>
                </p>
                <p class="text-xs font-weight-bold mb-3 px-3">
                    Jumlah Bayar : Rp. <?= ribuan($jumlahBayar) ?>
                </p>
                <p class="text-xs font-weight-bold mb-3 px-3">
                    Kembali : Rp. <?= ribuan($jumlahBayar - $totalKeseluruhan) ?>
                </p>
            </div>
    </main>
    <?php include ("assets/js/mainjs.php") ?>
</body>