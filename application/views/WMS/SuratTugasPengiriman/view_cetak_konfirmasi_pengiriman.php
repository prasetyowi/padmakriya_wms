<?php
//membuat format rupiah dengan PHP
//tutorial www.malasngoding.com

function format_rupiah($angka)
{

	$hasil_rupiah = number_format($angka, 2, ',', '.');
	return $hasil_rupiah;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>report_confirm_delivery_<?= date('dMY') ?></title>
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
		<h3>Cetak Laporan Konfirmasi Pengiriman</h3>
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
			<th style="text-align: left; border: 0;">Driver</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $ClosingPengirimanHeader[0]['karyawan_nama']; ?></td>
		</tr>
		<tr>
			<th style="text-align: left; border: 0;">No FDJR</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $ClosingPengirimanHeader[0]['delivery_order_batch_kode']; ?></td>
			<td style="width: 15rem; border: 0;"></td>
			<th style="text-align: left; border: 0;">Armada</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $ClosingPengirimanHeader[0]['kendaraan_model']; ?></td>
		</tr>
		<tr>
			<th style="text-align: left; border: 0;">Tanggal FDJR</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $ClosingPengirimanHeader[0]['delivery_order_batch_tanggal']; ?></td>
			<td style="width: 15rem; border: 0;"></td>
			<th style="text-align: left; border: 0;">Tipe Ekspedisi</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $ClosingPengirimanHeader[0]['tipe_ekspedisi_nama'] == null ? '-' : $ClosingPengirimanHeader[0]['tipe_ekspedisi_nama']; ?>
			</td>
		</tr>
		<tr>
			<th style="text-align: left; border: 0;">Tipe</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $ClosingPengirimanHeader[0]['tipe_delivery_order_alias']; ?></td>
		</tr>
		<tr>
			<th style="text-align: left; border: 0;">Area</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $ClosingPengirimanHeader[0]['area_nama']; ?></td>
		</tr>
		<tr>
			<th style="text-align: left; border: 0;">Status</th>
			<td style="text-align: left; border: 0;">:</td>
			<td style="text-align: left; border: 0;"><?= $ClosingPengirimanHeader[0]['delivery_order_batch_status']; ?></td>
		</tr>
		<tr>

		</tr>
	</table>
	<h4>Konfirmasi Pengiriman Detail</h4>
	<div id="table2" style="overflow-x:auto;">
		<table width="100%" id="table2" class="table2">
			<thead>
				<tr>
					<th><span name="CAPTION-NO">No</span></th>
					<th><span name="CAPTION-TANGGALDO">Tgl DO</span></th>
					<th><span name="CAPTION-NODO">No DO</span></th>
					<th><span name="CAPTION-CUSTOMER">Customer</span></th>
					<th style="width:20%"><span name="CAPTION-ALAMAT">Alamat Customer</span></th>
					<th><span name="CAPTION-TELP">No Telp</span></th>
					<th><span name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</span></th>
					<th><span name="CAPTION-TOTALTAGIHAN">Total Tagihan</span></th>
					<th style="width:15%;"><span name="CAPTION-TOTALBAYAR">Total Bayar</span></th>
					<th><span name="CAPTION-NOURUTRUTE">No Urut Rute</span></th>
					<th><span name="CAPTION-TERKIRIM">Status DO</span></th>
					<th style="width:20%"><span name="CAPTION-ALASANGAGALKIRIM">Alasan Gagal Kirim</span>
					</th>
					<th><span name="CAPTION-TITIPAN">Ada Titipan</span></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				foreach ($ClosingPengirimanDetail as $key => $value) : ?>
					<tr>
						<td><?= $key + 1 ?></td>
						<td><?= $value['delivery_order_tgl_buat_do'] ?></td>
						<td><?= $value['delivery_order_kode'] ?></td>
						<td><?= $value['delivery_order_kirim_nama'] ?></td>
						<td><?= $value['delivery_order_kirim_alamat'] ?></td>
						<td><?= $value['delivery_order_kirim_telp'] ?></td>
						<td><?= $value['delivery_order_tipe_pembayaran'] ?></td>
						<td><?= format_rupiah($value['delivery_order_nominal_tunai']) ?></td>
						<td><?= format_rupiah($value['delivery_order_jumlah_bayar']) ?></td>
						<td><?= $value['delivery_order_no_urut_rute'] ?></td>
						<td><?= $value['delivery_order_status'] ?></td>
						<td><?= $value['reason_id'] ?></td>
						<td><?= $value['ada_titipan'] == '1' ? 'Ada' : 'Tidak' ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<br>
	<h4>Delivery Order Detail</h4>
	<div id="table2" style="overflow-x:auto;">
		<table width="100%" id="table2" class="table2">
			<thead>
				<tr>
					<th><span name="CAPTION-NO">No</span></th>
					<th><span name="CAPTION-NODO">No DO</span></th>
					<th><span name="CAPTION-PRINCIPLE">Principle</span></th>
					<th><span name="CAPTION-SKUKODE">SKU Kode</span></th>
					<th style="width:20%"><span name="CAPTION-SKU">SKU</span></th>
					<th><span name="CAPTION-SKUHARGANETT">SKU Harga Nett</span></th>
					<th><span name="CAPTION-QTY">QTY</span></th>
					<th><span name="CAPTION-QTYKIRIM">QTY Kirim</span></th>
					<th><span name="CAPTION-REASON">Reason</span></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				foreach ($ClosingPengirimanDODetail as $key => $value) : ?>
					<tr>
						<td><?= $key + 1 ?></td>
						<td><?= $value['delivery_order_kode'] ?></td>
						<td><?= $value['principle_kode'] ?></td>
						<td><?= $value['sku_kode'] ?></td>
						<td><?= $value['sku_nama_produk'] ?></td>
						<td><?= format_rupiah($value['sku_harga_nett']) ?></td>
						<td><?= $value['sku_qty'] ?></td>
						<td><?= $value['sku_qty_kirim'] ?></td>
						<td><?= $value['reason_keterangan'] ?></td>
					</tr>
				<?php endforeach; ?>
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