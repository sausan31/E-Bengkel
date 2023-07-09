<?php
include("sess_check.php");
include("dist/function/format_rupiah.php");

$tgl = date('Y-m-d');
$ttl = 0;
$sql = "SELECT * FROM trx WHERE tgl_trx='$tgl'";
$ress = mysqli_query($conn, $sql);
$jmltrx = mysqli_num_rows($ress);
// query database mencari data admin
while ($data = mysqli_fetch_array($ress)) {
	$tot = $data['total'];
	$ttl += $tot;
}

// Mendapatkan bulan dan tahun saat ini
$currentMonth = date("m");
$currentYear = date("Y");
// Query untuk menghitung jumlah pendapatan
$query = "SELECT SUM(total) AS total_pendapatan FROM trx WHERE MONTH(tgl_trx) = '$currentMonth' AND YEAR(tgl_trx) = '$currentYear'";
$result = $conn->query($query);
if ($result->num_rows > 0) {
	$row = $result->fetch_assoc();
	$totalPendapatan = $row["total_pendapatan"];
	// echo "Total pendapatan bulan ini: " . $totalPendapatan;
} else {
	echo "Rp0";
}

// Mendapatkan tanggal awal dan akhir minggu ini
$dayOfWeek = date("N", strtotime($tgl));
$weekStart = date("Y-m-d", strtotime("-" . ($dayOfWeek - 1) . " days", strtotime($tgl)));
$weekEnd = date("Y-m-d", strtotime("+" . (7 - $dayOfWeek) . " days", strtotime($tgl)));
// Query untuk menghitung jumlah transaksi per jenis jasa
$queryjasa = "SELECT b.nama, SUM(tmp_trx.jml) AS total_transaksi
FROM barangjasa b
INNER JOIN tmp_trx ON b.id_brg = tmp_trx.id_brg
INNER JOIN trx ON tmp_trx.id_trx = trx.id_trx
WHERE b.jenis = 'jasa' AND trx.tgl_trx BETWEEN '$weekStart' AND '$weekEnd'
GROUP BY b.nama
ORDER BY total_transaksi DESC
LIMIT 1";
$resultjasa = $conn->query($queryjasa);
if ($resultjasa->num_rows > 0) {
	$rowjasa = $resultjasa->fetch_assoc();
	$namaJasaTerbanyak = $rowjasa["nama"];
	$jumlahTransaksiJasaTerbanyak = $rowjasa["total_transaksi"];
	// echo "Nama Barang dengan Transaksi Terbanyak pada Minggu Ini: " . $namaBarangTerbanyak . "<br>";
	// echo "Jumlah Transaksi: " . $jumlahTransaksiTerbanyak;
} else {
	echo "Tidak ada data transaksi pada minggu ini.";
}

// Query untuk menghitung jumlah transaksi per jenis barang
$querybarang = "SELECT b.nama, SUM(tmp_trx.jml) AS total_transaksi
          FROM barangjasa b
          INNER JOIN tmp_trx ON b.id_brg = tmp_trx.id_brg
          INNER JOIN trx ON tmp_trx.id_trx = trx.id_trx
          WHERE b.jenis = 'barang' AND trx.tgl_trx BETWEEN '$weekStart' AND '$weekEnd'
          GROUP BY b.nama
          ORDER BY total_transaksi DESC
          LIMIT 1";
$resultbarang = $conn->query($querybarang);
if ($resultbarang->num_rows > 0) {
	$rowbarang = $resultbarang->fetch_assoc();
	$namaBarangTerbanyak = $rowbarang["nama"];
	// echo "Nama barang dengan transaksi terbanyak pada minggu ini: " . $namaBarangTerbanyak;
} else {
	echo "Tidak ada transaksi pada minggu ini.";
}


// Bar chart 1
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

// Pie Chart
// Query untuk mendapatkan jumlah barang/jasa per jenis
$queryChart2 = "SELECT nama, SUM(stok) AS total_stok
FROM barangjasa
WHERE jenis = 'barang'
GROUP BY nama";
$resultChart2 = $conn->query($queryChart2);

$dataChart2 = array(); // Array untuk menyimpan data jumlah barang/jasa per jenis

if ($resultChart2->num_rows > 0) {
	while ($rowChart2 = $resultChart2->fetch_assoc()) {
		$namaBarangChart2 = $rowChart2["nama"];
		$totalStokChart2 = $rowChart2["total_stok"];

		// Menyimpan data jumlah barang per nama_barang
		$dataChart2[$namaBarangChart2] = $totalStokChart2;
	}
}

// Line chart
// Query untuk mendapatkan jumlah transaksi per hari berdasarkan jenis barang/jasa
$queryChart3 = "SELECT t.tgl_trx, b.jenis, COUNT(*) AS jumlah_transaksi
FROM trx t
INNER JOIN tmp_trx tt ON t.id_trx = tt.id_trx
INNER JOIN barangjasa b ON tt.id_brg = b.id_brg
WHERE b.jenis IN ('barang', 'jasa') AND t.tgl_trx >= '$weekStart' AND t.tgl_trx <= '$weekEnd'
GROUP BY t.tgl_trx, b.jenis
ORDER BY t.tgl_trx";
$resultChart3 = $conn->query($queryChart3);

$dataBarangChart3 = array(); // Array untuk menyimpan data jumlah transaksi barang per hari
$dataJasaChart3 = array(); // Array untuk menyimpan data jumlah transaksi jasa per hari
$labelsChart3 = array(); // Array untuk menyimpan label tanggal

if ($resultChart3->num_rows > 0) {
	while ($rowChart3 = $resultChart3->fetch_assoc()) {
		$tanggalChart3 = $rowChart3["tgl_trx"];
		$jenisChart3 = $rowChart3["jenis"];
		$jumlahTransaksiChart3 = $rowChart3["jumlah_transaksi"];

		// Menyimpan data jumlah transaksi barang/jasa per hari
		if ($jenisChart3 === "barang") {
			$dataBarangChart3[] = $jumlahTransaksiChart3;
		} elseif ($jenisChart3 === "jasa") {
			$dataJasaChart3[] = $jumlahTransaksiChart3;
		}

		// Menyimpan label tanggal
		if (!in_array($tanggalChart3, $labelsChart3)) {
			$labelsChart3[] = $tanggalChart3;
		}
	}
}


// deskripsi halaman
$pagedesc = "Beranda";
include("layout_top.php");
?>
<!-- top of file -->
<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid"><br>
		<div class="row">
			<div class="col-md-4">
				<div class="panel panel-heading" style="border-radius: 20px; padding: 30px 10px;">
					<div class="col-xs-1 group-21523101">
						<img class="img-215231" src="img/healthicons-money-bag.svg" alt="healthiconsmoney:money-bag">
					</div>
					<div>
						<div class="text-right group-d1">Total Pendapatan Bulan Ini</div>
						<span class="pull-right bahnschrift-bold-manatee-16px" style="margin-top: -5px;">
							<?php echo format_rupiah($totalPendapatan); ?>
						</span>
					</div>
				</div>
			</div><!-- /.col-lg-12 -->
			<div class="col-md-4">
				<div class="panel panel-heading" style="border-radius: 20px; padding: 30px 10px;">
					<div class="col-xs-1 group-21523102">
						<img class="img-215231" src="img/fluent-people-24-filled.svg" alt="fluent:people-24-filled">
					</div>
					<div>
						<div class="text-right group-d1">Tren Jasa Minggu Ini</div>
						<span class="pull-right bahnschrift-bold-manatee-16px" style="margin-top: -5px;">
							<?php echo $namaJasaTerbanyak; ?>
						</span>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-heading" style="border-radius: 20px; padding: 30px 10px;">
					<div class="col-xs-1 group-21523103">
						<img class="img-215231" src="img/fluent-clipboard-more-20-filled.svg"
							alt="fluent:clipboard-more-20-filled">
					</div>
					<div>
						<div class="text-right group-d1">Tren barang Minggu Ini</div>
						<span class="pull-right bahnschrift-bold-manatee-16px" style="margin-top: -5px;">
							<?php echo $namaBarangTerbanyak; ?>
						</span>
					</div>
				</div>
			</div>
		</div><!-- /.row -->

		<div class="row">
			<div class="col-lg-6 col-md-6">
				<div style="border-radius: 20px; position: relative;">
					<div class="group-d2">
						<div class="row">
							<div class="col-xs-9 text-right">
								<div class="font-d2">Pendapatan Hari Ini</div>
								<div class="huge price">
									<?php echo format_rupiah($ttl); ?>
								</div>
							</div>
						</div>
						<div style="align-items: flex-end;
										align-self: flex-end;
										gap: 19px;
										margin-bottom: 23px;
										min-height: 133px;
										width: 169px;">
							<div style="color: #808080;
											font-family: Bahnschrift-SemiBold;
											font-size: 20px;
											font-weight: 600;
											letter-spacing: 0;
											line-height: normal;
											margin-right: 4px;
											min-height: 25px;
											min-width: 165px;
											text-align: right;
											white-space: nowrap;">Transaksi Hari Ini</div><br>
							<div class="text-right" style="color: rgba(76, 73, 236, 1);
																font-family: Bahnschrift;
																font-size: 23px;
																font-weight: 700;
																height: 28px;
																letter-spacing: 2.3px;
																margin-right: 5px;
																min-width: 28px;"><?php echo $jmltrx; ?></div><br>
							<a href="trx.php">
								<div class="pull-right btn-info" style="align-items: center;
																	border-radius: 10px;
																	display: flex;
																	flex-direction: column;
																	height: 42px;
																	justify-content: center;
																	padding: 10px 20px;
																	position: relative;
																	width: 147px;">
									<span style="color: #ffffff;
														font-family: Bahnschrift-Reguler;
														font-size: 16px;
														font-weight: 400;
														letter-spacing: 0;
														line-height: 24px;
														position: relative;
														text-align: center;
														white-space: nowrap;
														width: fit-content;">Lihat Transaksi</span>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div><!-- /.panel-green -->

			<div class="col-lg-6 col-md-6">
					<div class="lineChart">
						<canvas id="lineChart"></canvas>
					</div>
			</div><!-- /.panel-green -->
		</div><!-- /.row --><br>

		<div class="row">
			<div class="col-lg-6 col-md-6">
				<div class="barChart">
					<canvas id="barChart1"></canvas>
				</div>
			</div>

			<div class="col-lg-6 col-md-6">
				<div class="pieChart">
					<canvas id="pieChart"></canvas>
				</div>
			</div>
		</div><!-- /.row --><br><br>

	</div><!-- /.row -->
</div><!-- /.container-fluid -->
</div><!-- /#page-wrapper -->
<!-- bottom of file -->

<script>
	// Membuat grafik batang (bar chart)
	var ctx = document.getElementById("barChart1").getContext("2d");
	var barChart = new Chart(ctx, {
		type: "bar",
		title: {
			text: "Revenue Chart of Acme Corporation"
		},
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
			},
			plugins: {
				title: {
					display: true,
					text: "Pendapatan per Bulan", // Judul chart
					font: {
						size: 15
					}
				}
			}

		}
	});

	// Membuat grafik lingkaran (pie chart)
	var ctxChart2 = document.getElementById("pieChart").getContext("2d");
	var pieChart = new Chart(ctxChart2, {
		type: "pie",
		data: {
			labels: <?php echo json_encode(array_keys($dataChart2)); ?>, // Nama barang sebagai label
			datasets: [{
				data: <?php echo json_encode(array_values($dataChart2)); ?>, // Jumlah stok barang sebagai data
				backgroundColor: [
					"rgba(76, 73, 236, 0.5)",
					"rgba(54, 162, 235, 0.5)",
					"rgba(255, 206, 86, 0.5)",
					"rgba(75, 192, 192, 0.5)",
					"rgba(153, 102, 255, 0.5)",
					"rgba(255, 159, 64, 0.5)"
				],
				borderColor: [
					"rgba(76, 73, 236, 1)",
					"rgba(54, 162, 235, 1)",
					"rgba(255, 206, 86, 1)",
					"rgba(75, 192, 192, 1)",
					"rgba(153, 102, 255, 1)",
					"rgba(255, 159, 64, 1)"
				],
				borderWidth: 1
			}]
		},
		options: {
			plugins: {
				title: {
					display: true,
					text: "Perbandingan Jumlah Stok Barang", // Judul chart
					font: {
						size: 16
					}
				}
			}
		}
	});

	// Membuat grafik garis (line chart)
	var ctxChart3 = document.getElementById("lineChart").getContext("2d");
	var lineChart = new Chart(ctxChart3, {
		type: "line",
		data: {
			labels: <?php echo json_encode($labelsChart3); ?>, // Label tanggal
			datasets: [
				{
					label: "Barang",
					data: <?php echo json_encode($dataBarangChart3); ?>, // Data jumlah transaksi barang per hari
					backgroundColor: "rgba(76, 73, 236, 0.5)",
					borderColor: "rgba(76, 73, 236, 1)",
					borderWidth: 1,
					fill: false
				},
				{
					label: "Jasa",
					data: <?php echo json_encode($dataJasaChart3); ?>, // Data jumlah transaksi jasa per hari
					backgroundColor: "rgba(54, 162, 235, 0.5)",
					borderColor: "rgba(54, 162, 235, 1)",
					borderWidth: 1,
					fill: false
				}
			]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true,
					ticks: {
						stepSize: 1
					}
				}
			},
			plugins: {
				title: {
					display: true,
					text: "Tren Jumlah Transaksi dalam 1 minggu",
					font: {
						size: 16
					}
				}
			}
		}
	});
</script>

<?php
include("layout_bottom.php");
?>