<?php
session_start();

$koneksi = new mysqli("localhost", "root", "", "DPU");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// ===== CEK LOGIN =====
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href = 'login.php';</script>";
    exit;
}

$username = $_SESSION['username'];
$stmt = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!isset($_GET['id'])) {
    die("ID properti tidak ditemukan di URL!");
}

$id = intval($_GET['id']);
$result = $koneksi->query("SELECT * FROM properti WHERE id = $id");
if ($result->num_rows === 0) {
    die("Properti dengan ID $id tidak ditemukan!");
}
$data = $result->fetch_assoc();

// ===== UPDATE DATA =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama            = $_POST['nama'];
    $deskripsi       = $_POST['deskripsi'] ?? '';
    $harga           = $_POST['harga'];
    $harga_setelah   = $_POST['harga_setelah'];
    $harga_sebelum   = $_POST['harga_sebelum'];
    $pajak           = $_POST['pajak'];
    $iuran           = $_POST['iuran'];
    $jenis           = $_POST['jenis'];
    $status          = $_POST['status'];
    $label           = $_POST['label'];

    // Foto Properti
    $foto_properti = $data['foto_properti'];
    if (isset($_FILES['foto-properti']) && $_FILES['foto-properti']['error'] === 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $tmp = $_FILES['foto-properti']['tmp_name'];
        $nama_file = uniqid() . '_' . basename($_FILES['foto-properti']['name']);
        $path = $upload_dir . $nama_file;
        if (move_uploaded_file($tmp, $path)) {
            $foto_properti = $path;
        }
    }

    // Alamat
    $alamat       = $_POST['alamat'];
    $negara       = $_POST['negara'];
    $provinsi     = $_POST['provinsi'];
    $kota         = $_POST['kota'];
    $kecamatan    = $_POST['kecamatan'];
    $kode_pos     = $_POST['kode_pos'];

    // Detail Fisik
    $ukuran_dalam     = $_POST['ukuran-dalam'];
    $ukuran_tanah     = $_POST['ukuran-tanah'];
    $jumlah_ruangan   = $_POST['jumlah-ruangan'];
    $jumlah_kamar     = $_POST['jumlah-kamar'];
    $jumlah_km        = $_POST['jumlah-km'];
    $garasi           = $_POST['garasi'];
    $tahun_bangun     = $_POST['tahun-bangun'];
    $tersedia_mulai   = $_POST['tersedia-mulai'];

    // Spesifikasi
    $basement           = $_POST['basement'];
    $tambahan           = $_POST['tambahan'];
    $atap               = $_POST['atap'];
    $eksterior_tambahan = $_POST['eksterior_tambahan'];
    $jenis_struktur     = $_POST['jenis-struktur'];
    $jumlah_lantai      = $_POST['jumlah-lantai'];

    // Checklist
    $interior       = isset($_POST['interior']) ? implode(', ', $_POST['interior']) : '';
    $eksterior      = isset($_POST['eksterior']) ? implode(', ', $_POST['eksterior']) : '';
    $utilitas       = isset($_POST['utilitas']) ? implode(', ', $_POST['utilitas']) : '';
    $fitur_lainnya  = isset($_POST['fitur_lainnya']) ? implode(', ', $_POST['fitur_lainnya']) : '';

    $stmt = $koneksi->prepare("UPDATE properti SET
        nama=?, deskripsi=?, harga=?, harga_setelah=?, harga_sebelum=?, pajak=?, iuran=?, jenis=?, status=?, label=?,
        foto_properti=?, alamat=?, negara=?, provinsi=?, kota=?, kecamatan=?, kode_pos=?,
        ukuran_dalam=?, ukuran_tanah=?, jumlah_ruangan=?, jumlah_kamar=?, jumlah_km=?,
        garasi=?, tahun_bangun=?, tersedia_mulai=?,
        basement=?, tambahan=?, atap=?, eksterior_tambahan=?, jenis_struktur=?, jumlah_lantai=?,
        interior=?, eksterior=?, utilitas=?, fitur_lainnya=?
        WHERE id=?");

    if (!$stmt) {
        die("Prepare failed: " . $koneksi->error);
    }

    $stmt->bind_param(
        str_repeat("s", 35) . "i", // 35 string + 1 integer untuk id
        $nama, $deskripsi, $harga, $harga_setelah, $harga_sebelum, $pajak, $iuran,
        $jenis, $status, $label, $foto_properti,
        $alamat, $negara, $provinsi, $kota, $kecamatan, $kode_pos,
        $ukuran_dalam, $ukuran_tanah, $jumlah_ruangan, $jumlah_kamar, $jumlah_km,
        $garasi, $tahun_bangun, $tersedia_mulai,
        $basement, $tambahan, $atap, $eksterior_tambahan,
        $jenis_struktur, $jumlah_lantai,
        $interior, $eksterior, $utilitas, $fitur_lainnya,
        $id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Properti berhasil diubah.'); window.location.href = 'properti-saya.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah properti: " . addslashes($stmt->error) . "');</script>";
    }

    $stmt->close();
    $koneksi->close();
}
?>




<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>PT DPU</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="img/PT. DPU.png" type="image/x-icon" />
    <!-- Font Icons css -->
    <link rel="stylesheet" href="css/font-icons.css">
    <!-- plugins css -->
    <link rel="stylesheet" href="css/plugins.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
    <style>
    input[type="date"] {
        width: 100%;
        padding: 14px 20px;
        font-size: 16px;
        font-family: inherit;
        color: #808080;
        background-color: #fff;
        border: 1px solid #dbe2ea;
        border-radius: 6px;
        box-sizing: border-box;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        position: absolute;
        right: 0;
        z-index: 1;
    }

    .input-item-date {
        position: relative;
    }

    .input-item-date::after {
        content: "\f073";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #fb412a;
        pointer-events: none;
    }
    </style>
</head>

<body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Add your site or application content here -->

<!-- Body main wrapper start -->
<div class="body-wrapper">

    <!-- HEADER AREA START (header-5) -->
    <header class="ltn__header-area ltn__header-5 ltn__header-logo-and-mobile-menu-in-mobile ltn__header-logo-and-mobile-menu ltn__header-transparent gradient-color-2">
        <!-- ltn__header-top-area start -->
        <div class="ltn__header-top-area top-area-color-white d-none">
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="ltn__top-bar-menu">
                            <ul>
                                <li><a href="mailto:info@webmail.com?Subject=Flower%20greetings%20to%20you"><i class="icon-mail"></i> info@webmail.com</a></li>
                                <li><a href="locations.html"><i class="icon-placeholder"></i> 15/A, Nest Tower, NYC</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="top-bar-right text-end">
                            <div class="ltn__top-bar-menu">
                                <ul>
                                    <li>
                                        <!-- ltn__language-menu -->
                                        <div class="ltn__drop-menu ltn__currency-menu ltn__language-menu">
                                            <ul>
                                                <li><a href="#" class="dropdown-toggle"><span class="active-currency">English</span></a>
                                                    <ul>
                                                        <li><a href="#">Arabic</a></li>
                                                        <li><a href="#">Bengali</a></li>
                                                        <li><a href="#">Chinese</a></li>
                                                        <li><a href="#">English</a></li>
                                                        <li><a href="#">French</a></li>
                                                        <li><a href="#">Hindi</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <!-- ltn__social-media -->
                                        <div class="ltn__social-media">
                                            <ul>
                                                <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                                <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                                
                                                <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                                <li><a href="#" title="Dribbble"><i class="fab fa-dribbble"></i></a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ltn__header-top-area end -->
        
        <!-- ltn__header-middle-area start -->
        <div class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-black">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="site-logo-wrap" style="display: flex; align-items: center; gap: 10px;">
                            <div class="site-logo">
                                <a href="index-5.html"><img src="img/brand-logo/DPU.png" alt="Logo" style="width: 40px; height: auto;"></a>
                            </div>
                            <div class="site-title" style="color: white; font-size: 16px; font-weight: bold;"> <a href="index-5.html"> PT DIYA PROPERTY UTAMA </a>
                            </div>
                            <div class="get-support clearfix d-none">
                                <div class="get-support-icon">
                                    <i class="icon-call"></i>
                                </div>
                                <div class="get-support-info">
                                    <h6>Get Support</h6>
                                    <h4><a href="tel:+123456789">123-456-789-10</a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col header-menu-column menu-color-white">
                        <div class="header-menu d-none d-xl-block">
                            <nav>
                                <div class="ltn__main-menu">
                                    <ul>
                                        <li><a href="dashboard.php">Dashboard</a>
                                        </li>
                                        <li><a href="properti-saya.php">Properti Saya</a>
                                        </li>
                                        <li><a href="tambah-properti.php">Tambah Properti</a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                    <div class="col ltn__header-options ltn__header-options-2 mb-sm-20">
                        <!-- user-menu -->
                        <div class="ltn__drop-menu user-menu">
                            <ul>
                                <li>
                                    <a href="#"><i class="icon-user"></i></a>
                                    <ul>
                                        <li><a href="profil.php">Profil Saya</a></li>
                                        <li><a href="ubah-password.php">Ubah Password</a></li>
                                        <li><a href="login.php">Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <!-- Mobile Menu Button -->
                        <div class="mobile-menu-toggle d-xl-none">
                            <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                                <svg viewBox="0 0 800 600">
                                    <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                    <path d="M300,320 L540,320" id="middle"></path>
                                    <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="col--- ltn__header-options ltn__header-options-2 ">
                        <!-- Mobile Menu Button -->
                        <div class="mobile-menu-toggle d-xl-none">
                            <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                                <svg viewBox="0 0 800 600">
                                    <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                    <path d="M300,320 L540,320" id="middle"></path>
                                    <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ltn__header-middle-area end -->
    </header>
    <!-- HEADER AREA END -->

    <!-- Utilize Mobile Menu Start -->
    <div id="ltn__utilize-mobile-menu" class="ltn__utilize ltn__utilize-mobile-menu">
        <div class="ltn__utilize-menu-inner ltn__scrollbar">
            <div class="ltn__utilize-menu-head">
                <div class="site-logo">
                    <a href="index.html"><img src="img/logo.png" alt="Logo"></a>
                </div>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="ltn__utilize-menu-search-form">
                <form action="#">
                    <input type="text" placeholder="Search...">
                    <button><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="ltn__utilize-menu">
                <ul>
                    <li><a href="#">Home</a>
                        <ul class="sub-menu">
                            <li><a href="index.html">Home Style 01</a></li>
                            <li><a href="index-2.html">Home Style 02</a></li>
                            <li><a href="index-3.html">Home Style 03</a></li>
                            <li><a href="index-4.html">Home Style 04</a></li>
                            <li><a href="index-5.html">Home Style 05  <span class="menu-item-badge">video</span></a></li>
                            <li><a href="index-6.html">Home Style 06</a></li>
                            <li><a href="index-7.html">Home Style 07</a></li>
                            <li><a href="index-8.html">Home Style 08</a></li>
                            <li><a href="index-9.html">Home Style 09</a></li>
                            <li><a href="index-10.html">Home Style 10 <span class="menu-item-badge">Map</span></a></li>
                            <li><a href="index-11.html">Home Style 11</a></li>
                        </ul>
                    </li>
                    <li><a href="#">About</a>
                        <ul class="sub-menu">
                            <li><a href="about.html">About</a></li>
                            <li><a href="service.html">Services</a></li>
                            <li><a href="service-details.html">Service Details</a></li>
                            <li><a href="portfolio.html">Portfolio</a></li>
                            <li><a href="portfolio-2.html">Portfolio - 02</a></li>
                            <li><a href="portfolio-details.html">Portfolio Details</a></li>
                            <li><a href="team.html">Team</a></li>
                            <li><a href="team-details.html">Team Details</a></li>
                            <li><a href="faq.html">FAQ</a></li>
                            <li><a href="locations.html">Google Map Locations</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Shop</a>
                        <ul class="sub-menu">
                            <li><a href="shop.html">Shop</a></li>
                            <li><a href="shop-grid.html">Shop Grid</a></li>
                            <li><a href="shop-left-sidebar.html">Shop Left sidebar</a></li>
                            <li><a href="shop-right-sidebar.html">Shop right sidebar</a></li>
                            <li><a href="product-details.html">Shop details </a></li>
                            <li><a href="cart.html">Cart</a></li>
                            <li><a href="wishlist.html">Wishlist</a></li>
                            <li><a href="checkout.html">Checkout</a></li>
                            <li><a href="order-tracking.html">Order Tracking</a></li>
                            <li><a href="account.html">My Account</a></li>
                            <li><a href="login.html">Sign in</a></li>
                            <li><a href="register.html">Register</a></li>
                        </ul>
                    </li>
                    <li><a href="#">News</a>
                        <ul class="sub-menu">
                            <li><a href="blog.html">News</a></li>
                            <li><a href="blog-grid.html">News Grid</a></li>
                            <li><a href="blog-left-sidebar.html">News Left sidebar</a></li>
                            <li><a href="blog-right-sidebar.html">News Right sidebar</a></li>
                            <li><a href="blog-details.html">News details</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Pages</a>
                        <ul class="sub-menu">
                            <li><a href="about.html">About</a></li>
                            <li><a href="service.html">Services</a></li>
                            <li><a href="service-details.html">Service Details</a></li>
                            <li><a href="portfolio.html">Portfolio</a></li>
                            <li><a href="portfolio-2.html">Portfolio - 02</a></li>
                            <li><a href="portfolio-details.html">Portfolio Details</a></li>
                            <li><a href="team.html">Team</a></li>
                            <li><a href="team-details.html">Team Details</a></li>
                            <li><a href="faq.html">FAQ</a></li>
                            <li><a href="history.html">History</a></li>
                            <li><a href="appointment.html">Appointment</a></li>
                            <li><a href="locations.html">Google Map Locations</a></li>
                            <li><a href="404.html">404</a></li>
                            <li><a href="contact.html">Contact</a></li>
                            <li><a href="coming-soon.html">Coming Soon</a></li>
                        </ul>
                    </li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>
            <div class="ltn__utilize-buttons ltn__utilize-buttons-2">
                <ul>
                    <li>
                        <a href="account.html" title="My Account">
                            <span class="utilize-btn-icon">
                                <i class="far fa-user"></i>
                            </span>
                            My Account
                        </a>
                    </li>
                    <li>
                        <a href="wishlist.html" title="Wishlist">
                            <span class="utilize-btn-icon">
                                <i class="far fa-heart"></i>
                                <sup>3</sup>
                            </span>
                            Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="cart.html" title="Shoping Cart">
                            <span class="utilize-btn-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <sup>5</sup>
                            </span>
                            Shoping Cart
                        </a>
                    </li>
                </ul>
            </div>
            <div class="ltn__social-media-2">
                <ul>
                    <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                    <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-black-40 bg-image "  data-bs-bg="img/header1.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title" style="color: white;">Edit Properti</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li style="color: white;"><a href="index.html" style="color: white;"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Beranda</a></li>
                                <li style="color: white;">Edit properti</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- EDIT PROPERTI AREA START -->
    <div class="ltn__appointment-area pt-115--- pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">                    
                    <form  action="edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                        <div class="ltn__tab-menu ltn__tab-menu-3 ltn__tab-menu-top-right-- text-uppercase--- text-center">
                            <div class="nav">
                                <a class="active show" data-bs-toggle="tab" href="#Step1">1. Deskripsi</a>
                                <a data-bs-toggle="tab" href="#Step2" class="">2. Media</a>
                                <a data-bs-toggle="tab" href="#Step3" class="">3. Lokasi</a>
                                <a data-bs-toggle="tab" href="#Step4" class="">4. Detail</a>
                                <a data-bs-toggle="tab" href="#Step5" class="">5. Fasilitas</a>
                            </div>
                        </div>
                        <?php
                        include 'koneksi.php';
                        $id = $_GET['id'] ?? null;

                        if ($id) {
                            $query = "SELECT * FROM properti WHERE id = $id";
                            $result = mysqli_query($koneksi, $query);
                            $data = mysqli_fetch_assoc($result);
                        } else {
                            echo "ID tidak valid.";
                            exit;
                        }
                        ?>

                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="Step1">
                                <div class="ltn__apartments-tab-content-inner">
                                    <h6>Deskripsi Properti</h6>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                   <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" placeholder="*Judul (Wajib)">
                                            </div>
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <textarea name="deskripsi" placeholder="Deskripsi"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <h6>Harga Properti</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="harga" value="<?= htmlspecialchars($data['harga']) ?>" placeholder="Harga dalam Rp (hanya angka)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="harga_setelah" value="<?= htmlspecialchars($data['harga_setelah']) ?>" placeholder="Label Setelah Harga (contoh: /bulan)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="harga_sebelum" value="<?= htmlspecialchars($data['harga_sebelum']) ?>" placeholder="Label Sebelum Harga (contoh: mulai)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="pajak" value="<?= htmlspecialchars($data['pajak']) ?>" placeholder="Tarif Pajak Tahunan">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="iuran" value="<?= htmlspecialchars($data['iuran']) ?>" placeholder="Iuran Asosiasi Pemilik Rumah (bulanan)">
                                            </div>
                                        </div>
                                    </div>

                                    <h6>Pilih Kategori</h6>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6">
                                            <div class="input-item">
                                                <select class="nice-select" name="jenis">
                                                    <?php
                                                    $jenis_opsi = ['Tidak Ada', 'Apartemen', 'Rumah', 'Industri', 'Lahan / Tanah', 'Kantor', 'Ruko / Toko', 'Vila'];
                                                    foreach ($jenis_opsi as $val) {
                                                        $selected = ($data['jenis'] == $val) ? 'selected' : '';
                                                        echo "<option $selected>$val</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="input-item">
                                                <select class="nice-select" name="status">
                                                    <?php
                                                    $status_opsi = ['Tidak Ada', 'Sewa', 'Jual'];
                                                    foreach ($status_opsi as $val) {
                                                        $selected = ($data['status'] == $val) ? 'selected' : '';
                                                        echo "<option $selected>$val</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="input-item">
                                                <select class="nice-select" name="label">
                                                    <?php
                                                    $label_opsi = ['Tidak Ada', 'Aktif', 'Penawaran Spesial', 'Penawaran Terbaru', 'Habis Terjuan'];
                                                    foreach ($label_opsi as $val) {
                                                        $selected = ($data['label'] == $val) ? 'selected' : '';
                                                        echo "<option $selected>$val</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-wrapper text-center--- mt-0">
                                        <a href="#Step2" data-target="#Step2" class="btn theme-btn-1 btn-effect-1 text-uppercase btn-next" data-bs-toggle="tab">Langkah Selanjutnya</a>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="tab-pane fade" id="Step2">
                                <div class="ltn__product-tab-content-inner">
                                    <h6>Upload Foto Properti</h6>

                                    <!-- Upload Gambar -->
                                    <input type="file" id="myFile" name="foto-properti" class="btn theme-btn-3 mb-10" accept="image/*"><br>

                                    <?php if (!empty($data['foto_properti'])): ?>
                                        <img src="<?= htmlspecialchars($data['foto_properti']) ?>" alt="Foto Properti" width="150">
                                    <?php endif; ?>

                                    <p>
                                        <small>* Hanya 1 gambar diperlukan. Ukuran minimum adalah 500x500px.</small><br>
                                        <small>* Hanya file gambar diperbolehkan (.jpg, .jpeg, .png).</small><br>
                                        <small>* File PDF tidak diperbolehkan pada bagian ini.</small>
                                    </p>

                                    <div class="btn-wrapper text-center--- mt-0">
                                        <a href="#Step1" data-target="#Step1" class="btn theme-btn-1 btn-effect-1 text-uppercase btn-prev" data-bs-toggle="tab">Langkah Sebelumnya</a>
                                        <a href="#Step3" data-target="#Step3" class="btn theme-btn-1 btn-effect-1 text-uppercase btn-next" data-bs-toggle="tab">Langkah Selanjutnya</a>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="Step3">
                                <div class="ltn__product-tab-content-inner">
                                    <h6>Lokasi Listing</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="alamat" placeholder="*Alamat" value="<?= htmlspecialchars($data['alamat'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="negara" placeholder="Negara" value="<?= htmlspecialchars($data['negara'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="provinsi" placeholder="Provinsi / Kabupaten" value="<?= htmlspecialchars($data['provinsi'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="kota" placeholder="Kota / Kabupaten" value="<?= htmlspecialchars($data['kota'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="kecamatan" placeholder="Kecamatan / Lingkungan" value="<?= htmlspecialchars($data['kecamatan'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="kode_pos" placeholder="Kode Pos" value="<?= htmlspecialchars($data['kode_pos'] ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="btn-wrapper text-center--- mt-0">
                                        <a href="#Step2" data-target="#Step2" class="btn theme-btn-1 btn-effect-1 text-uppercase btn-prev" data-bs-toggle="tab">Langkah Sebelumnya</a>
                                        <a href="#Step4" data-target="#Step4" class="btn theme-btn-1 btn-effect-1 text-uppercase btn-next" data-bs-toggle="tab">Langkah Selanjutnya</a>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="tab-pane fade" id="Step4">
                                <div class="ltn__product-tab-content-inner">
                                    <h6>Detail</h6>
                                    <div class="row">
                                        <?php function val($key) { global $data; return htmlspecialchars($data[$key] ?? ''); } ?>

                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="ukuran-dalam" placeholder="Ukuran dalam ft² (*hanya angka)" value="<?= val('ukuran_dalam') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="ukuran-tanah" placeholder="Ukuran Tanah dalam ft² (*hanya angka)" value="<?= val('ukuran_tanah') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="jumlah-ruangan" placeholder="Jumlah Ruangan (*hanya angka)" value="<?= val('jumlah_ruangan') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="jumlah-kamar" placeholder="Jumlah Kamar Tidur (*hanya angka)" value="<?= val('jumlah_kamar') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="jumlah-km" placeholder="Jumlah Kamar Mandi (*hanya angka)" value="<?= val('jumlah_km') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="id-khusus" placeholder="ID Khusus (*teks)" value="<?= val('id_khusus') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="garasi" placeholder="Garasi (*teks)" value="<?= val('garasi') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="tahun-bangun" placeholder="Tahun Dibangun (*angka)" value="<?= val('tahun_bangun') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item-date input-item-textarea">
                                                <input type="date" id="tersedia-mulai" name="tersedia-mulai" value="<?= val('tersedia_mulai') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="basement" placeholder="Basement (*teks)" value="<?= val('basement') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="tambahan" placeholder="Detail Tambahan (*teks)" value="<?= val('tambahan') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="atap" placeholder="Atap (*teks)" value="<?= val('atap') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <input type="text" name="eksterior_tambahan" placeholder="Material Eksterior (*teks)" value="<?= val('eksterior_tambahan') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item">
                                                <select class="nice-select" name="jenis-struktur">
                                                    <?php $jenis = val('jenis_struktur'); ?>
                                                    <option <?= $jenis == '' ? 'selected' : '' ?>>Jenis Struktur</option>
                                                    <option <?= $jenis == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                                                    <option <?= $jenis == 'Bata' ? 'selected' : '' ?>>Bata</option>
                                                    <option <?= $jenis == 'Kayu' ? 'selected' : '' ?>>Kayu</option>
                                                    <option <?= $jenis == 'Semen' ? 'selected' : '' ?>>Semen</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-item">
                                                <select class="nice-select" name="jumlah-lantai">
                                                    <?php $lantai = val('jumlah_lantai'); ?>
                                                    <option <?= $lantai == '' ? 'selected' : '' ?>>Jumlah Lantai</option>
                                                    <option <?= $lantai == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <option <?= $lantai == "$i" ? 'selected' : '' ?>><?= $i ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-wrapper text-center--- mt-0">
                                        <a href="#Step3" data-target="#Step3" class="btn theme-btn-1 btn-effect-1 text-uppercase btn-prev" data-bs-toggle="tab">Langkah Sebelumnya</a>
                                        <a href="#Step5" data-target="#Step5" class="btn theme-btn-1 btn-effect-1 text-uppercase btn-next" data-bs-toggle="tab">Langkah Selanjutnya</a>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="Step5">
                                <div class="ltn__product-tab-content-inner">
                                    <h6>Fasilitas dan Fitur</h6>

                                    
                                    <h6 class="mt-20">Detail Interior</h6>
                                    <div class="row">
                                        <?php
                                        $interior = ['Dapur Lengkap', 'Gym', 'Ruang Cuci', 'Ruang Media'];
                                        $selectedInterior = isset($data['interior']) ? explode(', ', $data['interior']) : [];

                                        foreach ($interior as $item) {
                                            $checked = in_array($item, $selectedInterior) ? 'checked' : '';
                                            echo '
                                            <div class="col-lg-4 col-md-6">
                                                <label class="checkbox-item">' . htmlspecialchars($item) . '
                                                    <input type="checkbox" name="interior[]" value="' . htmlspecialchars($item) . '" ' . $checked . '>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>';
                                        }
                                        ?>
                                    </div>

                                    <h6 class="mt-20">Detail Eksterior</h6>
                                    <div class="row">
                                        <?php
                                        $eksterior = ['Halaman Belakang', 'Lapangan Basket', 'Halaman Depan', 'Garasi Terhubung', 'Bak Mandi Air Hangat', 'Kolam Renang'];
                                        $selectedEksterior = isset($data['eksterior']) ? explode(', ', $data['eksterior']) : [];

                                        foreach ($eksterior as $item) {
                                            $checked = in_array($item, $selectedEksterior) ? 'checked' : '';
                                            echo '
                                            <div class="col-lg-4 col-md-6">
                                                <label class="checkbox-item">' . htmlspecialchars($item) . '
                                                    <input type="checkbox" name="eksterior[]" value="' . htmlspecialchars($item) . '" ' . $checked . '>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>';
                                        }
                                        ?>
                                    </div>


                                    <h6 class="mt-20">Utilitas</h6>
                                    <div class="row">
                                        <?php
                                        $utilitasList = [
                                            'Pendingin Sentral',
                                            'Listrik',
                                            'Pemanas',
                                            'Gas Alam',
                                            'Ventilasi',
                                            'Air Bersih'
                                        ];
                                        $selectedUtilitas = isset($data['utilitas']) ? explode(', ', $data['utilitas']) : [];

                                        foreach ($utilitasList as $item) {
                                            $checked = in_array($item, $selectedUtilitas) ? 'checked' : '';
                                            echo '
                                            <div class="col-lg-4 col-md-6">
                                                <label class="checkbox-item">' . htmlspecialchars($item) . '
                                                    <input type="checkbox" name="utilitas[]" value="' . htmlspecialchars($item) . '" ' . $checked . '>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>';
                                        }
                                        ?>
                                    </div>



                                    <h6 class="mt-20">Fitur Lainnya</h6>
                                    <div class="row">
                                        <?php
                                        $fitur_lainnya = [
                                            'Akses Kursi Roda',
                                            'Lift',
                                            'Perapian',
                                            'Detektor Asap',
                                            'Mesin Cuci & Pengering',
                                            'WiFi'
                                        ];
                                        $selectedFitur = isset($data['fitur_lainnya']) ? explode(', ', $data['fitur_lainnya']) : [];

                                        foreach ($fitur_lainnya as $item) {
                                            $checked = in_array($item, $selectedFitur) ? 'checked' : '';
                                            echo '
                                            <div class="col-lg-4 col-md-6">
                                                <label class="checkbox-item">' . htmlspecialchars($item) . '
                                                    <input type="checkbox" name="fitur_lainnya[]" value="' . htmlspecialchars($item) . '" ' . $checked . '>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>';
                                        }
                                        ?>
                                    </div>



                                    <div class="btn-wrapper text-center--- mt-30">
                                        <a href="#Step4" data-target="#Step4" class="btn theme-btn-1 btn-effect-1 text-uppercase btn-prev">Langkah Sebelumnya</a>
                                        <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- EDIT PROPERTI AREA END -->

    <!-- FOOTER AREA START -->
    <footer class="ltn__footer-area  ">
        <div class="footer-top-area  section-bg-2 plr--5">
            <div class="container-fluid">
                <div class="row justify-content-between text-md-center text-xl-start">
                    <div class="col-xl-4 col-md-12 col-sm-12 col-12">
                        <div class="footer-widget footer-about-widget">
                            <div class="footer-logo" style="display: flex; align-items:center;">
                                <div class="site-logo">
                                    <img src="img/brand-logo/DPU.png" alt="Logo" style="width: 28px; height: auto;">
                                </div>
                            </div>
                            <p>PT Diya Property Utama <br>adalah perusahaan yang bergerak<br>di bidang pengembangan properti<br>dengan komitmen tinggi untuk<br> menghadirkan hunian yang nyaman.</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-12 col-12">
                        <div class="display: flex; justify-content: center; gap: 40px; text-align: left;">
                            <h4 class="footer-title">Perusahaan</h4>
                            <div class="footer-menu">
                                <ul>
                                    <li><a href="about.php">Tentang Kami</a></li>
                                    <li><a href="service.php">Properti</a></li>
                                    <li><a href="contact.php">Kontak Kami</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-12 col-12">
                        <div class="footer-widget footer-menu-widget clearfix">
                            <h4 class="footer-title">Kontak Kami</h4>
                            <div class="footer-address">
                                <ul>
                                    <li>
                                        <div class="footer-address-icon">
                                            <i class="icon-placeholder"></i>
                                        </div>
                                        <div class="footer-address-info">
                                            <p>Jl. A. Wahab Syahranie No.Gang 9 </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="footer-address-icon">
                                            <i class="icon-call"></i>
                                        </div>
                                        <div class="footer-address-info">
                                            <p><a href="tel:+0123-456789">0822-5227-1063</a></p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="footer-address-icon">
                                            <i class="icon-mail"></i>
                                        </div>
                                        <div class="footer-address-info">
                                            <p><a href="mailto:example@example.com">example@example.com</a></p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-12 col-12">
                        <div class="footer-widget footer-menu-widget clearfix">
                            <h4 class="footer-title">Sosial Media</h4>
                            <div class="ltn__social-media mt-20">
                                <ul>
                                    <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                                    <li><a href="#" title="Youtube"><i class="fab fa-youtube"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ltn__copyright-area ltn__copyright-2 section-bg-7  plr--5">
            <div class="container-fluid ltn__border-top-2">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="ltn__copyright-design clearfix">
                            <p>All Rights Reserved @ AriFaNa <span class="current-year"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER AREA END -->

</div>
<!-- Body main wrapper end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
  
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Navigasi ke tab target saat tombol diklik
            document.querySelectorAll('.btn-next, .btn-prev').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const targetSelector = btn.getAttribute('data-target');
                const targetTab = document.querySelector(`a[href="${targetSelector}"]`);
                if (targetTab) {
                const tab = new bootstrap.Tab(targetTab);
                tab.show();
                }
            });
            });
        });
    </script>
</body>
</html>

