<?php
include ('koneksi.php');

$ambilPelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY ID_Pelanggan");
$ambilKasir = mysqli_query($koneksi, "SELECT * FROM kasir ORDER BY ID_Kasir");
$ambilMenu = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY ID_Item");
$tanggalSekarang = date('Y-m-d');

// Function for formatting number to Indonesian Rupiah
function ribuan($nilai)
{
    return number_format($nilai, 0, ',', '.');
}

if (isset($_POST['buatInvoice'])) {
    // Ambil data dari form
    $ID_INV = $_POST['ID_INV'];
    $Tanggal = $_POST['Tanggal'];
    $ID_Pelanggan = $_POST['ID_Pelanggan'];
    $ID_Kasir = $_POST['ID_Kasir'];
    $Jumlah_Bayar = str_replace('.', '', $_POST['Jumlah_Bayar']);

    // Simpan ke tabel_header_transaksi
    $queryInsertHeader = "INSERT INTO header (ID_INV, Tanggal, ID_Pelanggan, ID_Kasir, Jumlah_Bayar)
                      VALUES ('$ID_INV', '$Tanggal', '$ID_Pelanggan', '$ID_Kasir', '$Jumlah_Bayar')";
    $insertHeader = mysqli_query($koneksi, $queryInsertHeader);

    if ($insertHeader) {
        // Simpan ke tabel_detail_transaksi untuk setiap barang
        $rowCount = count($_POST['ID_Item']);
        $success = true;

        for ($i = 0; $i < $rowCount; $i++) {
            $ID_Item = $_POST['ID_Item'][$i];
            $Jumlah_Item = $_POST['Jumlah_Item'][$i];

            // Simpan ke tabel_detail_transaksi
            $queryInsertDetail = "INSERT INTO detail (ID_INV, ID_Item, Jumlah_Item)
                                  VALUES ('$ID_INV', '$ID_Item', '$Jumlah_Item')";
            $insertDetail = mysqli_query($koneksi, $queryInsertDetail);

            if (!$insertDetail) {
                $success = false;
                echo "Error: " . mysqli_error($koneksi);
                break; // Stop loop if one insertion fails
            }
        }

        if ($success) {
            ?>
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h6><i class="fas fa-check"></i><b> Invoice berhasil dibuat dan disimpan!</b></h6>
</div>
<?php
            header('location:invoice.php');
        } else {
            ?>
<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h6><i class="fas fa-ban"></i><b> Gagal menyimpan data detail transaksi.</b></h6>
</div>
<?php
        }
    } else {
        ?>
<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h6><i class="fas fa-ban"></i><b> Gagal menyimpan data header transaksi.</b></h6>
</div>
<?php
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

        <div class="container-fluid" id="container-wrapper">
            <form method="POST" id="buatInvoice" action="">
                <div class="container-fluid" id="container-wrapper">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="ID_INV">ID Invoice</label>
                            <input type="text" class="form-control" id="ID_INV" name="ID_INV">
                        </div>
                        <div class="form-group col-6">
                            <label for="ID_INV">Tanggal</label>
                            <input type="text" class="form-control" id="Tanggal" name="Tanggal" readonly
                                value="<?= $tanggalSekarang ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header d-flex flex-column justify-content-between px-4 pt-4 pb-0">
                                    <h6 class="mb-2 font-weight-bold text-primary">Pelanggan</h6>
                                    <select class="form-select" id="selectPelanggan"
                                        aria-label="Default select example">
                                        <option selected>Pilih Pelanggan</option>
                                        <?php while ($dataPelanggan = mysqli_fetch_assoc($ambilPelanggan)): ?>
                                        <option value="<?= $dataPelanggan['ID_Pelanggan'] ?>">
                                            <?= $dataPelanggan['Nama_Pelanggan'] ?>
                                        </option>
                                        <?php endwhile ?>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <!-- INPUT CUSTOMER -->
                                    <div id="formCustomer">
                                        <input type="hidden" class="form-control" id="ID_Pelanggan" name="ID_Pelanggan"
                                            placeholder="Masukan ID Pelanggan">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header d-flex flex-column justify-content-between px-4 pt-4 pb-0">
                                    <h6 class="mb-2 font-weight-bold text-primary">Kasir</h6>
                                    <select class="form-select" id="selectKasir" aria-label="Default select example">
                                        <option selected>Pilih Kasir</option>
                                        <?php while ($dataKasir = mysqli_fetch_assoc($ambilKasir)): ?>
                                        <option value="<?= $dataKasir['ID_Kasir'] ?>">
                                            <?= $dataKasir['Nama_Kasir'] ?>
                                        </option>
                                        <?php endwhile ?>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <!-- INPUT KASIR -->
                                    <div id="formCustomer">
                                        <input type="hidden" class="form-control" id="ID_Kasir" name="ID_Kasir"
                                            placeholder="Masukan ID Pelanggan">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TABEL PILIH MENU -->
                    <div class="table-responsive p-4 rounded bg-white">
                        <table class="table table-bordered border-primary align-items-center mb-0" id="tabelBarang">
                            <thead>
                                <tr>
                                    <th width="500">
                                        <a href="#" class="btn btn-success btn-xs add-row">
                                            <i class="fas fa-plus"></i> Product
                                        </a>
                                    </th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-group row gap-4">
                                            <div class="col-1">
                                                <a href="#" class="btn btn-danger remove-row">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            <div class="col-10">
                                                <select class="form-select select-menu"
                                                    aria-label="Default select example">
                                                    <option selected>Pilih Menu</option>
                                                    <?php while ($dataMenu = mysqli_fetch_assoc($ambilMenu)): ?>
                                                    <option class="pilihMenu" value="<?= $dataMenu['ID_Item'] ?>"
                                                        data-id-menu="<?= $dataMenu['ID_Item'] ?>"
                                                        data-nama-menu="<?= $dataMenu['Nama_Item'] ?>"
                                                        data-harga-menu="<?= $dataMenu['Harga_Satuan'] ?>">
                                                        <?= $dataMenu['Nama_Item'] ?>
                                                    </option>
                                                    <?php endwhile ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="hidden" class="form-control id-menu" name="ID_Item[]">
                                        <input type="number" class="form-control jumlah-item" name="Jumlah_Item[]"
                                            value="1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control harga-menu" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control subtotal-item" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group d-flex align-items-center justify-content-end">
                            <label class="col-2" for="Jumlah_Bayar">Jumlah Bayar</label>
                            <input type="text" class="form-control" id="Jumlah_Bayar" name="Jumlah_Bayar" width="500">
                        </div>
                        <div class="d-flex justify-content-end">
                            <strong>Total : Rp. <span class="total-harga">0</span></strong>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success" name="buatInvoice">Buat Invoice</button>
            </form>
        </div>
    </main>
    <script>
    // Function to format number as Rupiah
    function formatRupiah(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Event listener for selecting a customer
    document.getElementById('selectPelanggan').addEventListener('change', function() {
        let pelangganId = this.value;
        document.getElementById('ID_Pelanggan').value = pelangganId;
    });

    // Event listener for selecting a cashier
    document.getElementById('selectKasir').addEventListener('change', function() {
        let kasirId = this.value;
        document.getElementById('ID_Kasir').value = kasirId;
    });

    // Function to update subtotal
    function updateSubtotal(row) {
        let jumlah = parseFloat(row.querySelector('.jumlah-item').value);
        let harga = parseFloat(row.querySelector('.harga-menu').getAttribute('data-value'));
        let subtotal = jumlah * harga;
        row.querySelector('.subtotal-item').value = formatRupiah(subtotal);
        row.querySelector('.subtotal-item').setAttribute('data-value', subtotal); // Set data-value for subtotal
        updateTotal(); // Update total whenever subtotal changes
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-item').forEach(function(input) {
            let subtotal = parseFloat(input.getAttribute('data-value'));
            if (!isNaN(subtotal)) {
                total += subtotal;
            }
        });
        document.querySelector('.total-harga').textContent = formatRupiah(total);
        document.getElementById('Jumlah_Bayar').value = formatRupiah(total);
    }

    // Event listener for changing the selected menu
    document.querySelectorAll('.select-menu').forEach(function(select) {
        select.addEventListener('change', function() {
            let selectedOption = this.options[this.selectedIndex];
            let hargaMenu = selectedOption.getAttribute('data-harga-menu');
            let idMenu = selectedOption.getAttribute('data-id-menu');
            let row = this.closest('tr');

            row.querySelector('.id-menu').value = idMenu;
            row.querySelector('.harga-menu').value = formatRupiah(hargaMenu);
            row.querySelector('.harga-menu').setAttribute('data-value', hargaMenu);

            updateSubtotal(row); // Update subtotal when menu changes
        });
    });

    // Function to add event listeners to a new row
    function addRowListeners(row) {
        row.querySelector('.remove-row').addEventListener('click', function(e) {
            e.preventDefault();
            row.remove();
            updateTotal(); // Update total after removing a row
        });

        row.querySelector('.select-menu').addEventListener('change', function() {
            let selectedOption = this.options[this.selectedIndex];
            let hargaMenu = selectedOption.getAttribute('data-harga-menu');
            let idMenu = selectedOption.getAttribute('data-id-menu');
            let row = this.closest('tr');

            row.querySelector('.id-menu').value = idMenu;
            row.querySelector('.harga-menu').value = formatRupiah(hargaMenu);
            row.querySelector('.harga-menu').setAttribute('data-value', hargaMenu);

            updateSubtotal(row); // Update subtotal when menu changes
        });

        row.querySelector('.jumlah-item').addEventListener('input', function() {
            updateSubtotal(row); // Update subtotal when quantity changes
        });
    }

    // Event listener for adding a new row
    document.querySelector('.add-row').addEventListener('click', function(e) {
        e.preventDefault();
        let table = document.getElementById('tabelBarang').getElementsByTagName('tbody')[0];
        let newRow = table.insertRow();
        newRow.innerHTML = `<td>
                            <div class="form-group row gap-4">
                                <div class="col-1">
                                    <a href="#" class="btn btn-danger remove-row">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="col-10">
                                    <select class="form-select select-menu" aria-label="Default select example">
                                        <option selected>Pilih Menu</option>
                                        <?php
                                        $ambilMenu = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY ID_Item");
                                        while ($dataMenu = mysqli_fetch_assoc($ambilMenu)): ?>
                                                                                                                                                    <option class="pilihMenu" value="<?= $dataMenu['ID_Item'] ?>"
                                                                                                                                                        data-id-menu="<?= $dataMenu['ID_Item'] ?>"
                                                                                                                                                        data-nama-menu="<?= $dataMenu['Nama_Item'] ?>"
                                                                                                                                                        data-harga-menu="<?= $dataMenu['Harga_Satuan'] ?>"
                                                                                                                                                    >
                                                                                                                                                        <?= $dataMenu['Nama_Item'] ?>
                                                                                                                                                    </option>
                                        <?php endwhile ?>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="hidden" class="form-control id-menu" name="ID_Item[]">
                            <input type="number" class="form-control jumlah-item" name="Jumlah_Item[]" value="1">
                        </td>
                        <td>
                            <input type="text" class="form-control harga-menu" readonly>
                        </td>
                        <td>
                            <input type="text" class="form-control subtotal-item" readonly>
                        </td>`;
        addRowListeners(newRow);
    });

    // Initial setup to add event listeners to existing rows
    document.querySelectorAll('tbody tr').forEach(addRowListeners);
    </script>


    <?php include ('assets/js/mainjs.php'); ?>
</body>

</html>