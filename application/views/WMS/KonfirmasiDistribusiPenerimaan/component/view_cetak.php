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
	<center>
		<h3>Cetak Laporan Konfirmasi Distribusi Penerimaan Barang</h3>
		<hr>
	</center>


	<div id="table2" style="overflow-x:auto;">
		<table id="tablecetak" width="100%" class="table">
			<thead>
				<tr>
					<!-- <th>Action</th> -->
					<td><strong><label name="CAPTION-NO">No</label></strong></td>
					<td><strong><label name="CAPTION-NODRAFTMUTASI">No. Draft Mutasi</label></strong></td>
					<td><strong><label name="CAPTION-NOPENERIMAAN">No. Penerimaan</label></strong></td>
					<td><strong><label name="CAPTION-TANGGAL">Tanggal</label></strong></td>
					<td><strong><label name="CAPTION-LOKASIASAL">Lokasi Asal</label></strong></td>
					<td><strong><label name="CAPTION-LOKASITUJUAN">Lokasi Tujuan</label></strong></td>
					<td><strong><label name="CAPTION-NOPALLET">No. Pallet</label></strong></td>
					<td><strong><label name="CAPTION-LOKASIBINTUJUAN">Lokasi Bin Tujuan</label></strong></td>
					<!-- <th style="text-align: center;">Checked</th> -->
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				foreach ($s_detail as $key => $value) {
				?>
					<?php
					// $no = 1;
					foreach ($value as $key => $value1) {

					?>
						<tr>
							<td style="text-align: center;"><?= $no ?></td>
							<td style="text-align: center;"><?= $value1->no_mutasi_draft ?></td>
							<td style="text-align: center;"><?= $value1->no_penerimaan ?></td>
							<td style="text-align: center;"><?= $value1->tgl ?></td>
							<td style="text-align: center;"><?= $value1->depo_asal ?></td>
							<td style="text-align: center;"><?= $value1->depo_tujuan ?></td>
							<td style="text-align: center;"><?= $value1->kode ?></td>
							<td style="text-align: center;"><?= $value1->rak_nama ?></td>

						</tr>

					<?php
					} ?>
				<?php
					$no++;
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