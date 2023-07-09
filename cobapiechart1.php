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

    // Query untuk mendapatkan jumlah barang/jasa per jenis
    $queryChart2 = "SELECT jenis, SUM(stok) AS total_stok
FROM barangjasa
GROUP BY jenis";
    $resultChart2 = $conn->query($queryChart2);

    $dataChart2 = array(); // Array untuk menyimpan data jumlah barang/jasa per jenis
    $labelsChart2 = array(); // Array untuk menyimpan label jenis
    
    if ($resultChart2->num_rows > 0) {
        while ($rowChart2 = $resultChart2->fetch_assoc()) {
            $jenisChart2 = $rowChart2["jenis"];
            $stokChart2 = $rowChart2["total_stok"];

            // Menyimpan data jumlah barang/jasa per jenis
            $dataChart2[] = $stokChart2;

            // Menyimpan label jenis
            $labelsChart2[] = $jenisChart2;
        }
    }

    // Menutup koneksi database
    $conn->close();
    ?>

    <!-- Kode JavaScript untuk menggambar grafik lingkaran (pie chart) menggunakan Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <canvas id="pieChart"></canvas>
    <script>
        // Membuat grafik lingkaran (pie chart)
        var ctxChart2 = document.getElementById("pieChart").getContext("2d");
        var pieChart = new Chart(ctxChart2, {
            type: "pie",
            data: {
                labels: <?php echo json_encode($labelsChart2); ?>, // Label jenis
                datasets: [{
                    data: <?php echo json_encode($dataChart2); ?>, // Data jumlah barang/jasa per jenis
                    backgroundColor: ["rgba(255, 99, 132, 0.5)", "rgba(54, 162, 235, 0.5)", "rgba(255, 206, 86, 0.5)"], // Warna latar belakang setiap bagian lingkaran
                    borderColor: ["rgba(255, 99, 132, 1)", "rgba(54, 162, 235, 1)", "rgba(255, 206, 86, 1)"], // Warna batas setiap bagian lingkaran
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: "Persentase Jumlah Barang/Jasa",
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    </script>