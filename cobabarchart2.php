<!DOCTYPE html>
<html>

<head>
    <title>Data Transaksi Tahun Ini</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
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

    // Query untuk mendapatkan pendapatan per bulan berdasarkan jenis barang/jasa
    $queryChart1 = "SELECT MONTHNAME(tgl_trx) AS bulan, b.jenis, SUM(t.total) AS total_pendapatan
FROM trx t
INNER JOIN tmp_trx ON t.id_trx = tmp_trx.id_trx
INNER JOIN barangjasa b ON tmp_trx.id_brg = b.id_brg
WHERE b.jenis IN ('barang', 'jasa')
GROUP BY bulan, jenis
ORDER BY bulan DESC";
    $resultChart1 = $conn->query($queryChart1);

    $dataChart1 = array(); // Array untuk menyimpan data pendapatan per bulan berdasarkan jenis
    $bulanLabelsChart1 = array(); // Array untuk menyimpan label bulan
    
    if ($resultChart1->num_rows > 0) {
        while ($rowChart1 = $resultChart1->fetch_assoc()) {
            $bulanChart1 = $rowChart1["bulan"];
            $jenisChart1 = $rowChart1["jenis"];
            $pendapatanChart1 = $rowChart1["total_pendapatan"];

            // Menyimpan data pendapatan per bulan berdasarkan jenis
            $dataChart1[$jenisChart1][] = $pendapatanChart1;

            // Menyimpan label bulan
            if (!in_array($bulanChart1, $bulanLabelsChart1)) {
                $bulanLabelsChart1[] = $bulanChart1;
            }
        }
    }

    // Menutup koneksi database
    $conn->close();
    ?>

    <!-- Kode JavaScript untuk menggambar grafik batang (bar chart) menggunakan Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <canvas id="barChart1"></canvas>
    <script>
        // Membuat grafik batang (bar chart)
        var ctx = document.getElementById("barChart1").getContext("2d");
        var barChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: <?php echo json_encode($bulanLabelsChart1); ?>, // Label bulan
                datasets: [{
                    label: "Barang",
                    data: <?php echo json_encode($dataChart1["barang"]); ?>, // Data pendapatan jenis barang
                    backgroundColor: "rgba(76, 73, 236, 0.5)",
                    borderColor: "rgba(76, 73, 236, 1)",
                    borderWidth: 1
                },
                {
                    label: "Jasa",
                    data: <?php echo json_encode($dataChart1["jasa"]); ?>, // Data pendapatan jenis jasa
                    backgroundColor: "rgba(175, 173, 253,0.5)",
                    borderColor: "rgba(175, 173, 253,1)",
                    borderWidth: 1
                }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return "Rp" + value.toLocaleString(); // Format penulisan angka sebagai mata uang
                            }
                        }
                    }
                }
            }
        });
    </script>