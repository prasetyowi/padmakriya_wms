<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>report_persiapan_stock_opname<?= date('d-M-Y H:i:s') ?></title>
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
			border: 3px dashed #ddd;
		}

		.table2 th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: center;
			border-top: 3px dashed #ddd;
			border-bottom: 3px dashed #ddd;
		}

		td,
		th {
			/* padding: 8px; */
			text-align: center;
			font-family: sans-serif;
			font-style: normal;
			font-size: 12px;
			border-bottom: 3px dashed #D3D3D3;
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
	<center>
		<h3>Persiapan Stock Opname</h3>
		<hr>
	</center>

	<div>
		<!-- <div style="page-break-after:always;"> -->
		<table style="padding-bottom: 10px">

			<tr>
				<th style="text-align: left; border: 0;">Tanggal dicetak</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;">
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
				</td>

				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Tipe Stock Opname</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->tipe_opname_nama ?></td>
			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Tanggal Stock Opname</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->tgl_opname ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Jenis Stock</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->tipe_stok ?></td>
			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Perusahaan</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->client_wms_nama ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Status Pengajuan</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->tr_opname_plan_status ?></td>
			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Principle</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->principle_nama ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Keterangan</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->tr_opname_plan_keterangan ?></td>


			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Unit Cabang</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->depo_nama ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>

			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Penanggung Jawab</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->karyawan_nama ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>

			</tr>
			<tr>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>

			</tr>


		</table>
	</div>
	<div id="" style="overflow-x:auto;margin-top:15px;">
		<table class="table " width="100%" style="max: width 100px;" border="0" id="table-do-draft">
			<thead>
				<tr style="background-color: #F1F6F9;">
					<td rowspan="2" style="text-align: center; vertical-align:middle"><strong>No</strong></td>
					<td rowspan="2" style="text-align: center;vertical-align:middle"><strong>Kode Lokasi</strong></td>
					<td rowspan="2" style="text-align: center;vertical-align:middle"><strong>No. Pallet</strong></td>
					<td rowspan="2" style="text-align: center;vertical-align:middle"><strong>Kode Barang</strong></td>
					<td rowspan="2" style="text-align: center;vertical-align:middle"><strong>Nama Barang</strong></td>
					<td rowspan="2" style="text-align: center;vertical-align:middle"><strong>Kemasan</strong></td>
					<td rowspan="2" style="text-align: center;vertical-align:middle"><strong>Satuan</strong></td>
					<td rowspan="2" style="text-align: center;vertical-align:middle"><strong>ED</strong></td>
					<td rowspan="2" style="text-align: center;vertical-align:middle"><strong>No. Batch</strong></td>
					<td rowspan="2" style="text-align: center;vertical-align:middle"><strong>Qty Sistem</strong></td>
					<td colspan="2" style="text-align: center;"><strong>Qty Fisik</strong></td>
					<td rowspan="2" style="text-align: center;vertical-align:middle"><strong>Keterangan</strong></td>
				</tr>
				<tr style="background-color: #F1F6F9;">

					<td><strong>Bagus</strong></td>
					<td><strong>Rusak</strong></td>
				</tr>

			</thead>
			<tbody>
				<?php if (count($detail) > 0) { ?>
					<?php foreach ($detail as $i => $value) : ?>
						<tr>
							<td><?= $i + 1 ?></td>
							<td><?= $value->rak_lajur_detail_nama ?></td>
							<td><?= $value->pallet_kode ?></td>
							<td><?= $value->sku_kode ?></td>
							<td><?= $value->sku_nama_produk ?></td>
							<td><?= $value->sku_kemasan ?></td>
							<td><?= $value->sku_satuan ?></td>
							<td><?= $value->sku_stock_expired_date ?></td>
							<td></td>
							<td><?= $value->sku_stock_qty ?></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					<?php endforeach; ?>
				<?php } ?>


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
