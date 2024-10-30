<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Jenis Beasiswa</title>
    <style>
        /* Background image */
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-image: url('testi.png'); /* Menambahkan gambar background */
            background-size: cover; /* Memastikan gambar menutupi seluruh layar */
            background-position: center; /* Menempatkan gambar di tengah */
            background-attachment: fixed; /* Membuat gambar tetap saat scroll */
            background-color: #f4f4f4; /* Sebagai fallback warna */
        }

        .container { 
            max-width: 800px; 
            margin: 50px auto; 
            background-color: rgba(255, 255, 255, 0.9); /* Warna background dengan opacity */
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
        }

        h1 { 
            text-align: center; 
            color: #4CAF50; 
        }

        .beasiswa-box { 
            border: 2px solid #4CAF50; 
            border-radius: 10px; 
            padding: 15px; 
            margin: 20px 0; 
        }

        .beasiswa-box h2 { 
            color: #333; 
        }

        .beasiswa-box ul { 
            list-style: disc; 
            margin-left: 20px; 
        }

        .button { 
            display: block; 
            width: 100%; 
            padding: 10px; 
            background-color: #4CAF50; 
            color: white; 
            text-align: center; 
            border-radius: 5px; 
            text-decoration: none; 
            font-size: 16px; 
            font-weight: bold; 
        }

        .button:hover { 
            background-color: #45a049; 
        }
    </style>
</head>
<body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                </div>
                <ul class="nav navbar-nav">
                <li class="active"><a href="#">Jenis Beasiswa</a></li>
                <li><a href="index.php">Daftar</a></li>
                <li><a href="hasil.php">Hasil</a></li>
                </ul>
            </div>
        </nav>
    <div class="container">
        <h1>Jenis Beasiswa Telkom University</h1>

        <div class="beasiswa-box">
            <h2>Beasiswa Akademik</h2>
            <p>Adapun syarat dari ketentuan beasiswa akademik yaitu:</p>
            <ul>
                <li>IPK minimal 3.0 dari 4.0</li>
                <li>Tidak pernah mengulang mata kuliah</li>
                <li>Aktif dalam kegiatan akademik kampus</li>
                <li>Mengumpulkan proposal penelitian</li>
            </ul>
            <a href="index.php#form-pendaftaran" class="button">Daftar Beasiswa Akademik</a>
        </div>

        <div class="beasiswa-box">
            <h2>Beasiswa Non-Akademik</h2>
            <p>Adapun syarat dari ketentuan beasiswa non-akademik yaitu:</p>
            <ul>
                <li>Memiliki sertifikat atau penghargaan di bidang non-akademik</li>
                <li>Aktif mengikuti kompetisi atau kegiatan di luar akademik</li>
                <li>IPK mulai 3.0 dari 4.0</li>
                <li>Menyertakan rekomendasi dari pihak terkait (misalnya pelatih atau mentor)</li>
            </ul>
            <a href="index.php#form-pendaftaran" class="button">Daftar Beasiswa Non-Akademik</a>
        </div>
    </div>
</body>
</html>