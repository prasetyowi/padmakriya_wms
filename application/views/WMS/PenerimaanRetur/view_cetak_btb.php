<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>report_distribusi_penerimaan_<?= date('d-M-Y H:i:s') ?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<style>
		.table2 {
			/* page-break-inside: avoid; */
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		.table2 tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		.table2 tr:hover {
			background-color: #ddd;
		}

		hr {
			border: 1px solid black;
		}

		.table2 th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: center;
			background-color: #04AA6D;
			border-top: 1px solid #ddd;
			border-bottom: 1px solid #ddd;
			color: white;
		}

		td,
		th {
			padding: 8px;
			text-align: center;
			font-family: sans-serif;
			font-style: normal;
			font-size: 12px;
			border-bottom: 1px solid #D3D3D3;
		}

		/* tr {
        page-break-inside: avoid;
        page-break-after: auto;
    } */

		@media print {
			@page {
				size: landscape
			}

			body {
				-webkit-print-color-adjust: exact;
			}
		}
	</style>
</head>

<body>
	<!-- <center>
		<h3>Bukti Penerimaan Barang Outlet</h3>
		<hr>
	</center> -->

	<?php foreach ($DOHeader as $header) : ?>
		<div id="" style="overflow-x:auto;">
			<table style="padding:1px" width="100%">
				<tr>
					<th style="text-align: left; border: 0;"></th>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
					<td rowspan="4" style=" border: 0;">
						<h3>Bukti Penerimaan Barang Outlet</h3>
					</td>
					<!-- <td style="text-align: left; border: 0;">
					<?php
					$hari = date('l');
					$dayList = array(
						'Monday'    => 'Senin',
						'Tuesday'   => 'Selasa',
						'Wednesday' => 'Rabu',
						'Thursday'  => 'Kamis',
						'Friday'    => 'Jumat',
						'Saturday'  => 'Sabtu',
						'Sunday'    => 'Minggu'
					);

					$monthList = array(
						'01' => 'Januari',
						'02' => 'Februari',
						'03' => 'Maret',
						'04' => 'April',
						'05' => 'Mei',
						'06' => 'Juni',
						'07' => 'Juli',
						'08' => 'Agustus',
						'06' => 'September',
						'10' => 'Oktober',
						'11' => 'November',
						'12' => 'Desember'
					);

					$tgl = date('d-m-Y');
					$cek = explode("-", $tgl);

					echo $dayList[$hari] . ', ' . $cek[0] . ' ' . $monthList[$cek[1]] . ' ' . $cek[2];
					?>
				</td> -->
					<td style=" border: 0;"></td>
					<th style="text-align: left; border: 0;">No DO</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header['delivery_order_kode'] ?> </td>

				</tr>
				<tr>
					<td style="text-align: left; border: 0;">[</td>
					<td style="text-align: left; border: 0;">]</td>
					<th style="text-align: left; border: 0;font-size:11px">Retur</th>
					<td style=" border: 0;"></td>
					<th style="text-align: left; border: 0;">No FDJR</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header['delivery_order_batch_kode']  ?></td>
				</tr>
				<tr>
					<td style="text-align: left; border: 0;">[</td>
					<td style="text-align: left; border: 0;">]</td>
					<th style="text-align: left; border: 0;">Gagal Kirim</th>
					<td style=" border: 0;"></td>
					<th style="text-align: left; border: 0;">Tanggal BTB</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header['penerimaan_penjualan_tgl'] ?></td>
					</td>
				</tr>
				<tr>
					<td style="text-align: left; border: 0;">[</td>
					<td style="text-align: left; border: 0;">]</td>
					<th style="text-align: left; border: 0;">Terkirim sebagian</th>
					<td style=" border: 0;"></td>
					<th style="text-align: left; border: 0;">Nama Toko</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $nama_toko ?></td>

				</tr>
				<tr>

				</tr>
			</table>
		</div>
	<?php endforeach; ?>
	&nbsp;
	<div id="table" style="overflow-x:auto;">
		<table id="tabledoretur" width="100%" class="table table-bordered">
			<thead>
				<tr class="" style="background-color: grey;">
					<th class="text-center"><span name="CAPTION-NO">No</span></th>
					<th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
					<th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
					<th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
					<th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
					<th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
					<th class="text-center"><span name="CAPTION-QTY">Jumlah Barang</span></th>
					<th class="text-center"><span name="CAPTION-QTYTERIMA">Jumlah Terima</span></th>
					<th class="text-center"><span>Kondisi</span></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ($DODetail != "0") {
					$no = 1;
					foreach ($DODetail as $detail) : ?>
						<tr>
							<td class="text-center"><?= $no ?></td>
							<td class="text-center"><?= $detail['principle'] ?></td>
							<td class="text-center"><?= $detail['sku_kode'] ?></td>
							<td class="text-center"><?= $detail['sku_nama_produk'] ?></td>
							<td class="text-center"><?= $detail['sku_kemasan'] ?></td>
							<td class="text-center"><?= $detail['sku_satuan'] ?></td>
							<td class="text-center"><?= $detail['sku_qty'] ?></td>
							<td class="text-center"><?= $detail['sisa_jumlah_terima'] ?></td>
							<td class="text-center"><?= $detail['kondisi_barang'] ?></td>
						</tr>
				<?php $no++;
					endforeach;
				} ?>
			</tbody>
		</table>

	</div>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous">
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			window.print();
		});
	</script>