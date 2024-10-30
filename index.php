<?php
// [1] Pastikan file konfigurasi sudah di-include dengan benar
include 'config.php';

try {
    // [2] Membuat koneksi menggunakan PDO dengan konfigurasi dari config.php
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // [3] Tangani error dengan aman menggunakan htmlspecialchars untuk mencegah XSS.
    die("Koneksi gagal: " . htmlspecialchars($e->getMessage()));
}

// [4] Cek apakah request adalah POST untuk memproses form.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // [5] Sanitasi input user untuk mencegah XSS.
    $nama = htmlspecialchars($_POST['nama']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $hp = htmlspecialchars($_POST['hp']);
    $semester = intval($_POST['semester']);
    $ipk = isset($_POST['ipk']) ? floatval($_POST['ipk']) : 0;
    $pilihanBeasiswa = $_POST['pilihanBeasiswa'];
    $message = '';

    $valid = true; // [6] Inisialisasi validasi sebagai benar.

    // [7] Validasi IPK berdasarkan jenis beasiswa.
    if (($pilihanBeasiswa === 'Beasiswa Akademik' || $pilihanBeasiswa === 'Beasiswa Non-Akademik') 
        && ($ipk < 3.00 || $ipk > 4.00)) {
        $message = "IPK harus antara 3.00 dan 4.00 untuk jenis beasiswa yang dipilih.";
        $valid = false;
    }

    // [8] Tentukan direktori untuk upload file.
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        // [9] Buat direktori jika belum ada.
        mkdir($uploadDir, 0777, true);
    }
    $uploadFile = $uploadDir . basename($_FILES['berkas']['name']);
    $fileType = mime_content_type($_FILES['berkas']['tmp_name']);

    // [10] Jika validasi berhasil, lanjutkan proses upload dan simpan data.
    if ($valid) {
        if (move_uploaded_file($_FILES['berkas']['tmp_name'], $uploadFile)) {
            // [11] Query untuk menyimpan data ke database.
            $sql = "INSERT INTO pendaftaran (nama, email, hp, semester, ipk, pilihanBeasiswa, berkas, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'Belum diverifikasi')";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$nama, $email, $hp, $semester, $ipk, $pilihanBeasiswa, $uploadFile]);
            
            // [12] Berikan feedback kepada user tentang hasil proses
            if ($result) {
                $message = "Pendaftaran berhasil disimpan!";
            } else {
                $message = "Gagal menyimpan data ke database!";
            }
        } else {
            $message = "Gagal mengupload berkas!";
        }
    }
    // [13] Tampilkan pesan menggunakan JavaScript.
    echo "<script>alert('$message');</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"> <!-- [14] Gunakan charset UTF-8 untuk mendukung multi-bahasa -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- [15] Responsif untuk mobile -->
    <!-- [16] Link CDN untuk Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css"> <!-- [17] CSS eksternal -->
</head>
<body>

<nav class="navbar navbar-inverse"> <!-- [18] Navbar untuk navigasi -->
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li><a href="jenis_beasiswa.php">Jenis Beasiswa</a></li>
            <li class="active"><a href="#">Daftar</a></li>
            <li><a href="hasil.php">Hasil</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1 class="text-center">Sistem Pendaftaran Beasiswa</h1>

    <form method="post" enctype="multipart/form-data">
        <!-- [19] Input untuk nama peserta -->
        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Anda" required>
        </div>
        <!-- [20] Input untuk email -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" placeholder="E-mail: nama@example.com" required>
        </div>
        <!-- [21] Input untuk nomor HP dengan validasi pattern -->
        <div class="form-group">
            <label for="hp">No HP:</label>
            <input type="text" class="form-control" name="hp" id="hp" 
                placeholder="Contoh: 081234567890" 
                required 
                pattern="08[0-9]{8,13}">
        </div>
        <!-- [22] JavaScript untuk validasi input nomor HP -->
        <script>
            document.getElementById('hp').addEventListener('input', function (e) {
                const value = this.value;
                // Hapus semua karakter non-digit
                this.value = value.replace(/\D/g, ''); // [23] Hapus karakter non-digit
                if (this.value.length > 15) {
                    this.value = this.value.slice(0, 15); // [24] Batasi panjang maksimal 15 digit
                }
            });
        </script>
        <!-- [25] Pilih semester -->
        <div class="form-group">
            <label for="semester">Semester:</label>
            <select class="form-control" name="semester" id="semester" required>
                <option value="">Pilih Semester</option>
                <?php for ($i = 1; $i <= 8; $i++): ?>
                    <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <!-- [26] Input IPK (read-only) -->
        <div class="form-group">
            <label for="ipk">IPK:</label>
            <!-- IPK dibuat read-only agar tidak bisa diubah oleh user -->
            <input type="number" class="form-control" name="ipk" id="ipk" step="0.01" min="0" max="4" required readonly>
        </div>
        <!-- [27] Pilihan Beasiswa -->
        <div class="form-group">
            <label for="pilihanBeasiswa">Pilihan Beasiswa:</label>
            <select class="form-control" name="pilihanBeasiswa" id="pilihanBeasiswa" required>
                <option value="">Pilih Beasiswa</option>
                <option value="Beasiswa Akademik">Beasiswa Akademik</option>
                <option value="Beasiswa Non-Akademik">Beasiswa Non-Akademik</option>
            </select>
        </div>
        <!-- [28] Input untuk unggah berkas -->
        <div class="form-group">
            <label for="berkas">Unggah Berkas:</label>
            <input type="file" class="form-control" name="berkas" id="berkas" required>
        </div>
        <script>
            document.getElementById('berkas').addEventListener('change', function () {
                const file = this.files[0];
                const allowedExtensions = /(\.pdf)$/i; // Ekstensi yang diperbolehkan

                if (!allowedExtensions.exec(file.name)) {
                    alert('Hanya file PDF yang diperbolehkan!');
                    this.value = ''; // Reset input jika format tidak valid
                    return;
                }
            });
        </script>
        <!-- [29] Tombol submit dan reset -->
        <button type="submit" class="btn btn-primary">Daftar</button>
        <button type="reset" class="btn btn-default">Batal</button>
    </form>
</div>

<script>
    // [30] JavaScript untuk mengatur IPK berdasarkan semester
    document.getElementById('semester').addEventListener('change', function () {
        const semester = parseInt(this.value);
        const ipkInput = document.getElementById('ipk');
        const beasiswaSelect = document.getElementById('pilihanBeasiswa');
        const berkasInput = document.getElementById('berkas');

        // Atur nilai IPK otomatis berdasarkan semester
        const ipkMin = {
            1: 2.6, 2: 2.8, 3: 3.0, 4: 3.2,
            5: 3.4, 6: 3.6, 7: 3.8, 8: 4.0
        };

        ipkInput.value = ipkMin[semester] || ''; // [31] Set nilai IPK otomatis
        
        // Nonaktifkan pilihan beasiswa dan unggah berkas jika IPK di bawah 3.0
        if (ipkMin[semester] < 3.0) {
            alert("IPK di bawah 3.0, Anda tidak memenuhi syarat untuk beasiswa.");
            beasiswaSelect.disabled = true;
            berkasInput.disabled = true;
        } else {
            beasiswaSelect.disabled = false;
            berkasInput.disabled = false;
        }
    });
</script>

</body>
</html>