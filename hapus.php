<?php
session_start();

$koneksi = new mysqli("localhost", "root", "", "DPU");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id'])) {
    echo "<script>alert('ID properti tidak ditemukan!'); window.location.href = 'properti-saya.php';</script>";
    exit;
}

$id = intval($_GET['id']);

// Cek apakah properti dengan ID ini benar-benar ada
$cek = $koneksi->prepare("SELECT id FROM properti WHERE id = ?");
$cek->bind_param("i", $id);
$cek->execute();
$cek_result = $cek->get_result();

if ($cek_result->num_rows === 0) {
    echo "<script>alert('Properti tidak ditemukan.'); window.location.href = 'properti-saya.php';</script>";
    exit;
}
$cek->close();

// Lakukan penghapusan
$stmt = $koneksi->prepare("DELETE FROM properti WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Properti berhasil dihapus.'); window.location.href = 'properti-saya.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus properti.'); window.location.href = 'properti-saya.php';</script>";
}

$stmt->close();
$koneksi->close();
?>
