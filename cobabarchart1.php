<!DOCTYPE html>
<html>

<head>
    <title>Data Transaksi Minggu Ini</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <canvas id="myChart"></canvas>

    <?php
    // Koneksi ke database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_bengkel";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi ke database gagal: " . $conn->connect_error);
    }

    // Query untuk mengambil data pendapatan per bulan
    $query = "SELECT MONTHNAME(tgl_trx) AS bulan, SUM(total) AS total_pendapatan
FROM trx
GROUP BY MONTH(tgl_trx)
ORDER BY MONTH(tgl_trx)";

    $result = $conn->query($query);
    $bulan = [];
    $totalPendapatan = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bulan[] = $row["bulan"];
            $totalPendapatan[] = $row["total_pendapatan"];
        }
    }

    // Menutup koneksi database
    $conn->close();
    ?>

    <!-- HTML dan JavaScript untuk menampilkan grafik -->
    <!DOCTYPE html>
    <html>

    <head>
        <title>Grafik Pendapatan per Bulan</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <body>
        <canvas id="pendapatanChart"></canvas>

        <script>
            var ctx = document.getElementById('pendapatanChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($bulan); ?>,
                    datasets: [{
                        label: 'Pendapatan per Bulan',
                        data: <?php echo json_encode($totalPendapatan); ?>,
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
                    }
                    
                }
                
            });
        </script>
    </body>

    </html>