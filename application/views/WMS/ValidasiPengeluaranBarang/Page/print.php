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
		<h3>Cetak Laporan Validasi Pengiriman Barang</h3>
		<hr>
	</center>

	<div style="page-break-after:always;">
		<center>
			<h4>Surat Tugas Pengiriman</h4>
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
				<td style="width: 3rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>
				<td style="width: 8rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Nomor</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $row['dataFDJR'][0]['delivery_order_batch_kode']; ?></td>
			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Pengemudi</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $row['dataFDJR'][0]['driver']; ?></td>
				<td style="width: 3rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Pengemudi Pengganti</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $AssigmentDriver == 0 ? '' : $AssigmentDriver->driver_pengganti ?></td>
				<td style="width: 8rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Tgl.Kirim</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $row['dataFDJR'][0]['delivery_order_batch_tanggal_kirim']; ?></td>
			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Nopol</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $kendaraan_nopol->kendaraan_nopol ?></td>
				<td style="width: 3rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Nopol Pengganti</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"></td>
				<td style="width: 8rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Pick List</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"><?= $row['dataFDJR'][0]['picking_list_kode']; ?></td>
			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Helper</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"></td>
				<td style="width: 3rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Helper Pengganti</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"></td>
				<td style="width: 8rem; border: 0;"></td>
				<th style="text-align: left; border: 0;">Odo Berangkat</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"></td>

			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Tgl & Jam terima barang dari gudang</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"></td>
				<td style="width: 3rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>
				<td style="width: 8rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>

			</tr>
			<tr>
				<th style="text-align: left; border: 0;">Tgl & jam keluar dari depo</th>
				<td style="text-align: left; border: 0;">:</td>
				<td style="text-align: left; border: 0;"></td>
				<td style="width: 3rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>
				<td style="width: 8rem; border: 0;"></td>
				<th style="text-align: left; border: 0;"></th>
				<td style="text-align: left; border: 0;"></td>
				<td style="text-align: left; border: 0;"></td>

			</tr>
		</table>

		<div id="table2" style="overflow-x:auto;margin-top:25px;">
			<table width="100%" id="table2" class="table2">
				<thead>
					<tr style="background-color: #F1F6F9;">
						<th rowspan="2">Prioritas</th>
						<th rowspan="2">Nama</th>
						<th rowspan="2">Alamat</th>
						<th rowspan="2">Tunai / Kredit</label></th>
						<th rowspan="2">Total Tagihan</label></th>
						<th colspan="2">Setoran</th>
						<th rowspan="2">Keterangan</label>
						<th rowspan="2">Biaya Parkir</label>
						<th rowspan="2">Jam Datang</label>
						<th rowspan="2">Jam Keluar</label>
					</tr>
					<tr style="background-color: #F1F6F9;">
						<th>Tunai</th>
						<th>BG/CHECK</th>

					</tr>
				</thead>
				<tbody>
					<?php if (!empty($row['dataFDJR'])) {
						function rupiah($angka)
						{

							$hasil_rupiah =  number_format($angka, 2, ',', '.');
							return $hasil_rupiah;
						}
						$no2 = 1;
						$totalBeratDO = 0;
						$totalVolumeDO = 0; ?>
						<?php foreach ($row['dataFDJR'] as $i => $detail) :

						?>
							<tr id="row-<?= $i ?>">
								<td class="text-center"></td>
								<td class="text-center"><?= $detail['delivery_order_kirim_nama'] ?></td>
								<td class="text-center"><?= $detail['delivery_order_kirim_alamat'] ?></td>
								<td class="text-center"><?= $detail['delivery_order_tipe_pembayaran'] == 1 ? 'Tunai' : 'kredit'  ?></td>
								<td class="text-center"><?= rupiah(Round($detail['delivery_order_nominal_tunai'])) ?></td>
								<td class="text-center" style="width:25px;"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
							</tr>
						<?php
						endforeach; ?>
						<!-- <?php } ?>
					<?php
					$no = 1;
					foreach ($row['dataFDJR'] as $data) : ?>
						<tr>
							<td><?= $data['delivery_order_no_urut_rute'] ?></td>
							<td><?= $data['delivery_order_kode'] ?></td>
							<td><?= $data['delivery_order_kirim_nama'] ?></td>
							<td><?= $data['delivery_order_kirim_alamat'] ?></td>
							<td><?= $data['segment1'] ?></td>
							<td><?= $data['segment2'] ?></td>
							<td><?= $data['segment3'] ?></td>
							<td><?= $data['delivery_order_status'] ?></td>
						</tr> -->
				</tbody>
				<!-- <?php $no++;
					endforeach; ?> -->
			</table>
			<table>
				<tr>
					<th style="text-align: left; border: 0; margin-left:10px; font-size:11px;">Biaya bensin</th>
					<td style="text-align: left; border: 0; "></td>
					<td style="text-align: left; border: 0;"></td>

					<td style="text-align: left; border: 0;"></td>
					<td style="width: 20rem; border: 0;"></td>
					<th style="text-align: left; border: 0; margin-left:10px; font-size:11px;">Total Tunai</th>
					<td style="text-align: left; border: 0; ">:</td>
					<td style="text-align: left; border: 0;"></td>
				</tr>
				<tr>
					<td style="text-align: left; border: 0;font-style:normal;font-family:Arial, Helvetica, sans-serif">Rupiah</td>
					<td style="text-align: left; border: 0; ">:</td>
					<td style="text-align: left; border: 0;"></td>

					<td style="text-align: left; border: 0;"></td>
					<td style="width: 20rem; border: 0;"></td>
					<th style="text-align: left; border: 0; margin-left:10px; font-size:11px;">Odo Meter Pulang</th>
					<td style="text-align: left; border: 0; ">:</td>
					<td style="text-align: left; border: 0;"></td>
				</tr>
				<tr>
					<td style="text-align: left; border: 0;font-style:normal;font-family:Arial, Helvetica, sans-serif">Liter</td>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
					<td style="width: 20rem; border: 0;"></td>
					<th style="text-align: left; border: 0; margin-left:10px; font-size:11px;"></th>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
				</tr>


			</table>
			<table>
				<tr>
					<td style="width: 7rem; border: 0; text-align:center">ADMIN</td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
					<td style="width: 7rem; border: 0; text-align:center">PENGEMUDI</td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
					<td style="width: 7rem; border: 0; text-align:center">KASIR</td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>

				</tr>
				<tr>
					<td style="text-align: left; border: 0;">&nbsp;</td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;">&nbsp;</td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;">&nbsp;</td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
				</tr>
				<tr>
					<td style="text-align: left; border: 0;">&nbsp;</td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;">&nbsp;</td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;">&nbsp;</td>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>
				</tr>
				<tr>
					<td style="width: 7rem; border: 0; text-align:center">................</td>
					<td style="width: 7rem; border: 0; text-align:center">&nbsp;</td>
					<td style="width: 7rem; border: 0; text-align:center">&nbsp;</td>
					<td style="width: 7rem; border: 0; text-align:center">................</td>
					<td style="width: 7rem; border: 0; text-align:center">&nbsp;</td>
					<td style="width: 7rem; border: 0; text-align:center">&nbsp;</td>
					<td style="width: 7rem; border: 0; text-align:center">................</td>
					<td style="width: 7rem; border: 0; text-align:center">&nbsp;</td>
					<td style="width: 7rem; border: 0; text-align:center">&nbsp;</td>
				</tr>


			</table>
		</div>
	</div>
	<?php foreach ($row['dataDO'] as $value) { ?>
		<div style="page-break-after:always;">
			<center>
				<h4>Delivery Order</h4>
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
					<th style="text-align: left; border: 0;">Nama Outlet</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $value['delivery_order_kirim_nama']; ?></td>
				</tr>
				<tr>
					<th style="text-align: left; border: 0;">Tanggal Rencana Pengiriman</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $value['delivery_order_tgl_rencana_kirim']; ?></td>
					<td style="width: 15rem; border: 0;"></td>
					<th style="text-align: left; border: 0;">Alamat Outlet</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $value['delivery_order_kirim_alamat']; ?></td>
				</tr>
				<tr>
					<th style="text-align: left; border: 0;">No. FDJR</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $value['delivery_order_batch_kode']; ?></td>
					<td style="width: 15rem; border: 0;"></td>
					<th style="text-align: left; border: 0;">Status</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $value['delivery_order_status']; ?></td>
				</tr>
				<tr>
					<th style="text-align: left; border: 0;">No. DO</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $value['delivery_order_kode']; ?></td>
					<td style="width: 15rem; border: 0;"></td>
					</td>
				</tr>
			</table>

			<div id="table2" style="overflow-x:auto;margin-top:25px;">
				<table width="100%" id="table2" class="table2">
					<thead>
						<tr>
							<th>#</th>
							<th>principal</th>
							<th>SKU Kode</th>
							<th>SKU</th>
							<th>Kemasan</th>
							<th>Satuan</th>
							<th>QTY</th>
							<th>QTY Kirim</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($value['detail'] as $value2) : ?>
							<tr>
								<td><?= $no ?></td>
								<td><?= $value2['principal'] ?></td>
								<td><?= $value2['sku_kode'] ?></td>
								<td><?= $value2['sku_nama_produk'] ?></td>
								<td><?= $value2['sku_kemasan'] ?></td>
								<td><?= $value2['sku_satuan'] ?></td>
								<td><?= $value2['sku_qty'] ?></td>
								<td><?= $value2['sku_qty_kirim'] ?></td>
							</tr>
					</tbody>
				<?php $no++;
						endforeach; ?>
				</table>
			</div>
		</div>
	<?php } ?>

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