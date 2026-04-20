<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>report_distribusi_pengiriman_<?= date('d-M-Y H:i:s') ?></title>
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
			font-size: 14px;
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
	<center>
		<h3>Cetak Laporan Pengiriman Barang</h3>
		<hr>
	</center>

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
			<td style="width: 17rem; border: 0;"></td>
			<th style="text-align: left; border: 0;">Tanggal</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $stp->tgl_create; ?></td>
		</tr>
		<tr>
			<th style="text-align: left; border: 0;">No Pengiriman Barang</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $stp->serah_terima_kirim_kode; ?></td>
			<td style="width: 15rem; border: 0;"></td>
			<th style="text-align: left; border: 0;">No. PPB</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $stp->picking_order_kode; ?></td>
		</tr>
		<tr>
			<th style="text-align: left; border: 0;">No. FDJR</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $stp->delivery_order_batch_kode; ?></td>
			<td style="width: 15rem; border: 0;"></td>
			<th style="text-align: left; border: 0;">Keterangan</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $stp->serah_terima_kirim_keterangan == null ? '-' : $stp->serah_terima_kirim_keterangan; ?>
			</td>
		</tr>
		<tr>
			<th style="text-align: left; border: 0;">Status</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $stp->serah_terima_kirim_status; ?></td>
		</tr>
		<tr>
			<th style="text-align: left; border: 0;">Driver</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $stp->karyawan_nama; ?></td>
		</tr>
		<tr>

		</tr>
	</table>

	<div id="table2" style="overflow-x:auto;">
		<?php if ($stp_d1 != NULL) { ?>
			<table id="TabelDetailBulk" width="100%" class="table">
				<thead>
					<tr>
						<!-- <th>Action</th> -->
						<th style="text-align: center;" name="CAPTION-NO">No</th>
						<th style="text-align: center;" name="CAPTION-PRINCIPLE">Principle</th>
						<th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
						<th style="text-align: center;" name="CAPTION-SKUNAMA">Nama Barang</th>
						<th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
						<th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
						<th style="text-align: center;" hidden name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
						<th style="text-align: center;" hidden name="CAPTION-JUMLAHDIAMBIL">Jumlah Diambil</th>
						<th style="text-align: center;" name="CAPTION-JUMLAHTERIMABAGUS">Jumlah Terima (Bagus)</th>
						<th style="text-align: center;" name="CAPTION-JUMLAHTERIMARUSAK">Jumlah Terima (Rusak)</th>
						<!-- <th style="text-align: center;">Checked</th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($stp_d1 as $key => $value) {
					?>
						<tr>
							<td style="text-align: center;"><?= $no ?></td>
							<td style="text-align: center;"><?= $value['principle_kode'] ?></td>
							<td style="text-align: center;"><?= $value['sku_kode'] ?></td>
							<td style="text-align: center;"><?= $value['sku_nama_produk'] ?></td>
							<td style="text-align: center;"><?= $value['sku_kemasan'] ?></td>
							<td style="text-align: center;"><?= $value['sku_satuan'] ?></td>
							<td style="text-align: center;" hidden><?= $value['jumlah_ambil_plan'] ?></td>
							<td style="text-align: center;" hidden><?= $value['jumlah_ambil_aktual'] ?></td>
							<td style="text-align: center;"><?= $value['jumlah_serah_terima'] == 0 ? '' : $value['jumlah_serah_terima'] ?></td>
							<td style="text-align: center;"><?= $value['jumlah_serah_terima_rusak'] == 0 ? '' : $value['jumlah_serah_terima_rusak'] ?></td>
							<!-- <td style="text-align: center;"><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail" value="0" disabled></td> -->
						</tr>

					<?php $no++;
					} ?>
				</tbody>
			</table>
		<?php } ?>
		<?php if ($stp_d2 != NULL) { ?>
			<table id="TabelDetailStandar" width="100%" class="table">
				<thead>
					<tr>
						<!-- <th>Action</th> -->
						<th style="text-align: center;" name="CAPTION-NO">No</th>
						<th style="text-align: center; vertical-align:middle;" name="CAPTION-DOKODE">DO Kode</th>
						<th style="text-align: center; vertical-align:middle;" name="CAPTION-CUSTNAME">Nama</th>
						<th style="text-align: center; vertical-align:middle;" name="CAPTION-CUSTADDRESS">Alamat</th>
						<!-- <th style="text-align: center;">Kemasan</th>
										<th style="text-align: center;">Satuan</th> -->
						<th style="text-align: center; vertical-align:middle;" hidden name="CAPTION-JUMLAHPAKET">Jumlah Paket</th>
						<th style="text-align: center; vertical-align:middle;" name="CAPTION-TOTALSERAHTERIMA">Jumlah Serah Terima</th>
						<!-- <th style="text-align: center;">Checked</th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($stp_d2 as $key => $value) {
					?>
						<tr>
							<td style="text-align: center;"><?= $no ?></td>
							<td style="text-align: center;"><?= $value['delivery_order_kode'] ?></td>
							<!-- <td style="text-align: center;"><?= $value['tipe_delivery_order_alias'] ?></td> -->
							<!-- <td style="text-align: center;"><?= $value['delivery_order_status'] ?></td> -->
							<td style="text-align: center;"><?= $value['delivery_order_kirim_nama'] ?></td>
							<td style="text-align: center;"><?= $value['delivery_order_kirim_alamat'] ?></td>
							<!-- <td style="text-align: center;"><?= $value['delivery_order_kirim_telp'] ?></td> -->
							<td style="text-align: center;" hidden><?= $value['jumlah_paket'] ?></td>
							<td style="text-align: center;"><?= $value['jumlah_serah_terima'] ?></td>
							<!-- <td style="text-align: center;"><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail" value="0" disabled></td> -->
						</tr>

					<?php $no++;
					} ?>

				</tbody>
			</table>
		<?php } ?>
		<?php if ($stp_d3 != NULL) { ?>
			<table id="TabelDetailReschedule" width="100%" class="table">
				<thead>
					<tr>
						<!-- <th>Action</th> -->
						<th style="text-align: center;" name="CAPTION-NO">No</th>
						<th style="text-align: center;" name="CAPTION-PRINCIPLE">Principle</th>
						<th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
						<th style="text-align: center;" name="CAPTION-SKUNAMA">Nama Barang</th>
						<th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
						<th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
						<th style="text-align: center;" hidden name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
						<th style="text-align: center;" hidden name="CAPTION-JUMLAHDIAMBIL">Jumlah Diambil</th>
						<th style="text-align: center;" name="CAPTION-JUMLAHTERIMABAGUS">Jumlah Terima (Bagus)</th>
						<th style="text-align: center;" name="CAPTION-JUMLAHTERIMARUSAK">Jumlah Terima (Rusak)</th>
						<!-- <th style="text-align: center;">Checked</th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($stp_d3 as $key => $value) {
					?>
						<tr>
							<td style="text-align: center;"><?= $no ?></td>
							<td style="text-align: center;"><?= $value['principle_kode'] ?></td>
							<td style="text-align: center;"><?= $value['sku_kode'] ?></td>
							<td style="text-align: center;"><?= $value['sku_nama_produk'] ?></td>
							<td style="text-align: center;"><?= $value['sku_kemasan'] ?></td>
							<td style="text-align: center;"><?= $value['sku_satuan'] ?></td>
							<td style="text-align: center;" hidden><?= $value['jumlah_ambil_plan'] ?></td>
							<td style="text-align: center;" hidden><?= $value['jumlah_ambil_aktual'] ?></td>
							<td style="text-align: center;"><?= $value['jumlah_serah_terima'] == 0 ? '' : $value['jumlah_serah_terima'] ?></td>
							<td style="text-align: center;"><?= $value['jumlah_serah_terima_rusak'] == 0 ? '' : $value['jumlah_serah_terima_rusak'] ?></td>
							<!-- <td style="text-align: center;"><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail" value="0" disabled></td> -->
						</tr>

					<?php $no++;
					} ?>
				</tbody>
			</table>
		<?php } ?>
		<?php if ($stp_d4 != NULL) { ?>
			<table id="TabelDetailCanvas" width="100%" class="table">
				<thead>
					<tr>
						<!-- <th>Action</th> -->
						<th style="text-align: center;" name="CAPTION-NO">No</th>
						<th style="text-align: center;" name="CAPTION-PRINCIPLE">Principle</th>
						<th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
						<th style="text-align: center;" name="CAPTION-SKUNAMA">Nama Barang</th>
						<th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
						<th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
						<th style="text-align: center;" hidden name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
						<th style="text-align: center;" hidden name="CAPTION-JUMLAHDIAMBIL">Jumlah Diambil</th>
						<th style="text-align: center;" name="CAPTION-JUMLAHTERIMABAGUS">Jumlah Terima (Bagus)</th>
						<th style="text-align: center;" name="CAPTION-JUMLAHTERIMARUSAK">Jumlah Terima (Rusak)</th>
						<!-- <th style="text-align: center;">Checked</th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($stp_d4 as $key => $value) {
					?>
						<tr>
							<td style="text-align: center;"><?= $no ?></td>
							<td style="text-align: center;"><?= $value['principle_kode'] ?></td>
							<td style="text-align: center;"><?= $value['sku_kode'] ?></td>
							<td style="text-align: center;"><?= $value['sku_nama_produk'] ?></td>
							<td style="text-align: center;"><?= $value['sku_kemasan'] ?></td>
							<td style="text-align: center;"><?= $value['sku_satuan'] ?></td>
							<td style="text-align: center;" hidden><?= $value['jumlah_ambil_plan'] ?></td>
							<td style="text-align: center;" hidden><?= $value['jumlah_ambil_aktual'] ?></td>
							<td style="text-align: center;"><?= $value['jumlah_serah_terima'] == 0 ? '' : $value['jumlah_serah_terima'] ?></td>
							<td style="text-align: center;"><?= $value['jumlah_serah_terima_rusak'] == 0 ? '' : $value['jumlah_serah_terima_rusak'] ?></td>
							<!-- <td style="text-align: center;"><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail" value="0" disabled></td> -->
						</tr>

					<?php $no++;
					} ?>
				</tbody>
			</table>
		<?php } ?>
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