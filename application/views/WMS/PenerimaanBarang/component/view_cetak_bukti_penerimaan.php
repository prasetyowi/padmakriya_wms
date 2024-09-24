<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>report_validasi_pengiriman_barang_<?= date('d-M-Y H:i:s') ?></title>
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
			font-size: 14px;
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
		<h3>Bukti Penerimaan Barang Supplier</h3>
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
				<th style="text-align: left; border: 0;">Nama Pengemudi</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->pengemudi ?></td>
			</tr>
			<tr>
				<th style="text-align: left; border: 0;">No.Doc PO Rec</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->kode ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Jasa Pengangkut</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->e_kode . " - " . $atas->e_nama ?></td>
			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Tanggal</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->tgl ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">No Kendaraan</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->nopol ?></td>
			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Lokasi Penerimaan</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->gudang ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Checker</th>
				<td style="text-align: left; border: 0;">:</td>
				<?php foreach ($checker as $ck) : ?>
					<td style="text-align: left; border: 0; white-space:nowrap">-<?= $ck->karyawan_nama . "\n" ?></td>
				<?php endforeach; ?>

			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Perusahaan</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $pt ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>

			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Principle</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $p_kode ?>-<?= $p_nama ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>

			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Keterangan</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $atas->keterangan ?></td>
				<td style="width: 12rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>

			</tr>


		</table>
	</div>
	<div id="" style="overflow-x:auto;margin-top:15px;">
		<table class="table table-bordered table-striped" width="100%" id="table-do-draft">
			<thead>
				<tr style="background-color: #F1F6F9;">
					<td><strong>No</strong></td>
					<td><strong>Kode</strong></td>
					<td><strong>SKU</strong></td>
					<td><strong>Kemasan</strong></td>
					<td><strong>Satuan</strong></td>
					<td><strong>Batch No</strong></td>
					<td><strong>Expired Date</strong></td>
					<td><strong>Jumlah Barang</strong></td>
					<td><strong>Terima</strong></td>
					<td><strong>No. Pallet</strong></td>
					<td><strong>Lokasi Tujuan</strong></td>
					<td><strong>Tipe Tujuan</strong></td>
				</tr>

			</thead>
			<tbody>

				<?php foreach ($detail as $i => $row) :
					$no = 1; ?>
					<tr>
						<td class="text-center"><?= $i + 1 ?></td>
						<td class="text-center"><?= $row->sku_kode ?></td>
						<td><?= $row->sku_nama ?></td>
						<td class="text-center"><?= $row->sku_kemasan ?></td>
						<td class="text-center"><?= $row->sku_satuan ?></td>
						<td class="text-center"><?= $row->batch_no == null ? '' :  $row->batch_no ?></td>
						<td class="text-center"><?= $row->sku_exp_date ?></td>
						<td class="text-center"><?= $row->jml_barang ?></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>


					</tr>
				<?php $no++;
				endforeach ?>

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