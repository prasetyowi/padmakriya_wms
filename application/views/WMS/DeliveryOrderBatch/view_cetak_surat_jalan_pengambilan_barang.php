<!DOCTYPE html>
<html>

<head>
	<title>Surat Jalan Pengambilan Barang</title>
	<meta charset="utf-8">
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 20px;
		}

		h2 {
			text-align: center;
		}

		.table {
			width: 100%;
			border-collapse: collapse;
		}

		.table,
		.table th,
		.table td {
			border: 1px solid black;
		}

		/* .table th,
		.table td {
			padding: 8px;
			text-align: center;
		} */
	</style>
</head>

<body>
	<div>
		<h2><?= $header->client_wms_nama ?></h2>
		<br>
		<hr>
		<h3 style="text-align: center;">SURAT JALAN PENGAMBILAN BARANG</h3>
		<table class="table" style="margin-top: 10px;">
			<tbody>
				<tr>
					<td style="border: none; width: 40%;">NO SO</td>
					<td style=" border: none;">:</td>
					<td style="border: none;"><?= $header->so_eksternal_no ?></td>
				</tr>
				<tr>
					<td style="border: none;">NO DO</td>
					<td style="border: none;">:</td>
					<td style="border: none;"><?= $header->do_eksternal_no ?></td>
				</tr>
				<tr>
					<td style="border: none;">NAMA DISTRIBUTOR</td>
					<td style="border: none;">:</td>
					<td style="border: none;"><?= $header->client_wms_nama ?></td>
				</tr>
				<tr>
					<td style="border: none;">NAMA DRIVER</td>
					<td style="border: none;">:</td>
					<td style="border: none;"><?= $header->karyawan_nama ?></td>
				</tr>
				<tr>
					<td style="border: none;">PLAT NO</td>
					<td style="border: none;">:</td>
					<td style="border: none;"><?= $header->kendaraan_nopol ?></td>
				</tr>
				<tr>
					<td style="border: none;">TUJUAN</td>
					<td style="border: none;">:</td>
					<td style="border: none;"></td>
				</tr>
				<tr>
					<td style="border: none;">TANGGAL PENGAMBILAN</td>
					<td style="border: none;">:</td>
					<td style="border: none;"><?= $header->tgl_kirim ?></td>
				</tr>
			</tbody>
		</table>
		<br>
		<h3>Detail barang yang diambil:</h3>
		<table class="table">
			<thead>
				<tr style="text-align: center;">
					<th>NO</th>
					<th>SKU KODE</th>
					<th>SKU NAMA PRODUK</th>
					<th>QTY</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($detail as $key => $value) : ?>
					<tr style="text-align: center;">
						<td><?= $key + 1 ?></td>
						<td><?= $value->sku_kode ?></td>
						<td><?= $value->sku_nama_produk ?></td>
						<td><?= $value->jumlah ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div style="height: 120px; margin-top: 80px">
			<label for="">Menyetujui,</label>
		</div>
	</div>
</body>

</html>