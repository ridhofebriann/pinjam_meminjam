<?php
include 'config.php';

// --- LOGIKA PHP (Create, Delete, Update) ---
if (isset($_POST['simpan'])) {
    $barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $peminjam = mysqli_real_escape_string($koneksi, $_POST['nama_peminjam']);
    $jenis = $_POST['jenis'];
    
    $query = "INSERT INTO tabel_peminjaman (nama_barang, nama_peminjam, jenis, status) VALUES ('$barang', '$peminjam', '$jenis', 'DIPINJAM')";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Berhasil Disimpan!'); window.location='index.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM tabel_peminjaman WHERE id='$id'");
    header("Location: index.php");
}

if (isset($_GET['selesai'])) {
    $id = $_GET['selesai'];
    mysqli_query($koneksi, "UPDATE tabel_peminjaman SET status='SELESAI' WHERE id='$id'");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinjamYuk - Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-box-seam-fill me-2"></i>PinjamYuk App
            </a>
            <span class="text-white small">Developed by Ridho Febrian</span>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-plus-circle me-2"></i>Input Transaksi
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label text-secondary small text-uppercase fw-bold">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" placeholder="Misal: Charger" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-secondary small text-uppercase fw-bold">Nama Orang</label>
                                <input type="text" name="nama_peminjam" class="form-control" placeholder="Misal: Budi" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-secondary small text-uppercase fw-bold">Jenis Transaksi</label>
                                <select name="jenis" class="form-select">
                                    <option value="KELUAR">🔴 Barang Keluar (Dipinjam Teman)</option>
                                    <option value="MASUK">🟢 Barang Masuk (Pinjam Teman)</option>
                                </select>
                            </div>
                            
                            <button type="submit" name="simpan" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-save me-2"></i>Simpan Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-list-ul me-2"></i>Riwayat Peminjaman</span>
                        <span class="badge bg-primary rounded-pill fw-normal">Realtime DB</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Barang</th>
                                        <th>Orang</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tampil = mysqli_query($koneksi, "SELECT * FROM tabel_peminjaman ORDER BY id DESC");
                                    
                                    // Cek kalau datanya kosong
                                    if(mysqli_num_rows($tampil) == 0){
                                        echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Belum ada data peminjaman.</td></tr>";
                                    }

                                    while ($data = mysqli_fetch_array($tampil)) :
                                    ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark"><?= $data['nama_barang']; ?></td>
                                        <td class="text-secondary"><?= $data['nama_peminjam']; ?></td>
                                        <td>
                                            <?php if($data['jenis'] == 'KELUAR') { ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill">
                                                    <i class="bi bi-arrow-up-right small me-1"></i>Keluar
                                                </span>
                                            <?php } else { ?>
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill">
                                                    <i class="bi bi-arrow-down-left small me-1"></i>Masuk
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if($data['status'] == 'DIPINJAM'){ ?>
                                                <span class="badge bg-warning text-dark status-badge shadow-sm">Sedang Dipinjam</span>
                                            <?php } else { ?>
                                                <span class="badge bg-secondary status-badge">Selesai</span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <?php if($data['status'] == 'DIPINJAM') { ?>
                                                <a href="index.php?selesai=<?= $data['id']; ?>" class="btn btn-sm btn-outline-success" title="Tandai Selesai" onclick="return confirm('Barang sudah kembali?')">
                                                    <i class="bi bi-check-lg"></i>
                                                </a>
                                            <?php } ?>
                                            <a href="index.php?hapus=<?= $data['id']; ?>" class="btn btn-sm btn-outline-danger ms-1" title="Hapus Data" onclick="return confirm('Hapus data ini?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>