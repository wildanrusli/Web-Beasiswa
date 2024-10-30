<?php
include 'config.php';

$message = '';

// Mengecek apakah ada parameter 'delete' di URL untuk menghapus data tertentu.
if (isset($_GET['delete'])) {
$id = $_GET['delete'];
$stmt = $pdo->prepare("DELETE FROM pendaftaran WHERE id = ?");
$stmt->execute([$id]);
header('Location: hasil.php');
exit;
}

// Mengambil semua data dari tabel 'pendaftaran' dan mengurutkannya secara descending berdasarkan ID.
$stmt = $pdo->query("SELECT * FROM pendaftaran ORDER BY id DESC");
$pendaftaran = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Menghubungkan Bootstrap untuk styling CSS dan jQuery untuk fungsi interaktif -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <title>Hasil Pendaftaran</title>
    <!-- Menghubungkan CSS tambahan -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Menghubungkan Chart.js untuk visualisasi data -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Styling untuk kontainer grafik */
        #chartContainer {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-top: 20px;
        }
        /* Menyesuaikan ukuran canvas grafik */
        #beasiswaChart, #ipkChart {
            width: 40%;
            max-widht: 600px;
            height: auto !important;
        }
    </style>
</head>
<body>

<!-- Navbar untuk navigasi antar halaman -->
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
    </div>
    <ul class="nav navbar-nav">
      <li><a href="jenis_beasiswa.php">Jenis Beasiswa</a></li>
      <li><a href="index.php">Daftar</a></li>
      <li class="active"><a href="#">Hasil</a></li>
    </ul>
  </div>
</nav>

<h1>Hasil Pendaftaran</h1>
    <table>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Semester</th>
            <th>IPK</th>
            <th>Pilihan Beasiswa</th>
            <th>Berkas</th>
            <th>Status</th>
            <th>Hapus</th>
        </tr>
        <?php foreach ($pendaftaran as $daftar): ?>
        <tr>
            <td><?php echo htmlspecialchars($daftar['nama']); ?></td>
            <td><?php echo htmlspecialchars($daftar['email']); ?></td>
            <td><?php echo htmlspecialchars($daftar['hp']); ?></td>
            <td><?php echo htmlspecialchars($daftar['semester']); ?></td>
            <td><?php echo htmlspecialchars($daftar['ipk']); ?></td>
            <td><?php echo htmlspecialchars($daftar['pilihanBeasiswa']); ?></td>
            <td><a href="<?php echo htmlspecialchars($daftar['berkas']); ?>" download>Download</a></td>
            <td><?php echo htmlspecialchars($daftar['status']); ?></td>
            <td>
                <a href="?delete=<?php echo $daftar['id']; ?>" class="delete" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Grafik Pendaftaran Beasiswa</h2>
    <div id="chartContainer">
        <canvas id="beasiswaChart"></canvas>
        <canvas id="ipkChart"></canvas>
    </div>

    <footer>
        © Informatics Engineering at Telkom University by Muhamad Wildan Rusli
    </footer>

    <script>
    
    // Mengambil data untuk grafik dari server
    fetch('get_chart_data.php')
        .then(response => response.json())
        .then(data => {
            const ctxBeasiswa = document.getElementById('beasiswaChart').getContext('2d');
            const ctxIPK = document.getElementById('ipkChart').getContext('2d');

            // Membuat grafik batang untuk jumlah pendaftaran per jenis beasiswa
            new Chart(ctxBeasiswa, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah Pendaftaran',
                        data: data.jumlahBeasiswa,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Pendaftaran per Jenis Beasiswa'
                        }
                    }
                }
            });

            // Membuat grafik pie untuk rata-rata IPK
            new Chart(ctxIPK, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Rata-rata IPK',
                        data: data.rataRataIPK,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Rata-rata IPK per Jenis Beasiswa'
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error:', error)); // Menangani error jika terjadi

    // Fungsi untuk validasi
    function validateForm() {
        return true;
    }
    </script>
</body>
</html>