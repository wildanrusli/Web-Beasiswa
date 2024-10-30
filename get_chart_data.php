<?php
include 'config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT pilihanBeasiswa, COUNT(*) as jumlah, AVG(ipk) as rata2_ipk FROM pendaftaran GROUP BY pilihanBeasiswa");
    $chartData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = [];
    $jumlahBeasiswa = [];
    $rataRataIPK = [];

    foreach ($chartData as $row) {
        $labels[] = $row['pilihanBeasiswa'];
        $jumlahBeasiswa[] = intval($row['jumlah']);
        $rataRataIPK[] = floatval($row['rata2_ipk']);
    }

    $response = [
        'labels' => $labels,
        'jumlahBeasiswa' => $jumlahBeasiswa,
        'rataRataIPK' => $rataRataIPK
    ];

    echo json_encode($response);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
}