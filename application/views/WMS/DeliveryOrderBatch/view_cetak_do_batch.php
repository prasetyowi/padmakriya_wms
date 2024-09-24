<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>report_validasi_pengiriman_barang_<?= date('d-M-Y H:i:s') ?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<style>
		body {
			font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
			font-size: 8pt;
		}

		.container {
			max-width: 1200px;
		}

		.header,
		.product-table-section,
		.payment-info,
		.footer {
			padding: 2px;
			margin-bottom: 10px;
		}

		.header-table,
		.product-table,
		.footer-table {
			width: 100%;
			border-collapse: collapse;
		}

		.header-table td,
		.header-table th {
			padding: 2px;
			text-align: left;
		}

		.product-table td,
		.product-table th {
			width: 50px;
			border: 1px dashed black;
			padding: 5px;
		}

		.footer-table {
			margin-top: 20px;
		}

		.right-align {
			text-align: right;
		}

		.highlight {
			background-color: #FFD700;
		}

		.text-number {
			text-align: right;
		}

		.footer {
			font-size: 12px;
		}


		/* Membuat page break sebelum elemen ini */
		.page-break {
			page-break-before: always;
			/* Mulai halaman baru sebelum elemen ini */
		}

		@media print {
			@page {
				size: landscape
			}

			body {
				-webkit-print-color-adjust: exact;
			}

			.page-break {
				page-break-before: always;
			}
		}
	</style>
</head>

<body>
	<div class="container">
		<!-- <div style="page-break-after:always;"> -->
		<?php foreach ($FDJR as $key_header => $header) : ?>
			<!-- Header Section -->
			<div class="header">
				<table class="header-table">
					<tr>
						<td colspan="2"><?= $header['client_wms_nama'] ?></td>
						<th rowspan="2">SURAT JALAN</th>
						<th rowspan="2">REG</th>
						<td colspan="2" style="text-align: center;"><?= $header['delivery_order_tipe_pembayaran'] ?></td>
					</tr>
					<tr>
						<td colspan="2"><?= $header['client_wms_alamat'] ?></td>
						<td colspan="2" style="text-align: center;"><?= $header['bCash'] ?></td>
					</tr>
					<tr>
						<td colspan="2">TELP <?= $header['client_wms_telepon'] ?></td>
						<td colspan="2"></td>
						<td>Nama Sopir</td>
						<td>: <?= $header['driver_nama'] ?></td>
					</tr>
					<tr>
						<td colspan="2">NPWP </td>
						<td colspan="2"></td>
						<td>NOPOL Mobil Kirim</td>
						<td>: <?= $header['kendaraan_nopol'] ?></td>
					</tr>
					<tr>
						<td>Nama Toko</td>
						<td>: <?= $header['delivery_order_kirim_nama'] ?></td>
						<td>NO SO SISTEM</td>
						<td>: <?= $header['sales_order_kode'] ?></td>
						<td>Delivery Number WMS</td>
						<td>: <?= $header['delivery_order_kode'] ?></td>
					</tr>
					<tr>
						<td>Alamat</td>
						<td>: <?= $header['client_pt_alamat'] ?></td>
						<td>NO SO BOSNET</td>
						<td>: <?= $header['sales_order_no_po'] ?></td>
						<td>TGL KIRIM AKTUAL WMS</td>
						<td>: <?= $header['delivery_order_tgl_aktual_kirim'] ?></td>
					</tr>
					<tr>
						<td>Telp Toko</td>
						<td>: <?= $header['delivery_order_kirim_telp'] ?></td>
						<td>TGL ORDER</td>
						<td>: <?= $header['sales_order_tgl'] ?></td>
						<td>PENERIMA</td>
						<td>: <?= $header['delivery_order_kirim_nama'] ?></td>
					</tr>
					<tr>
						<td>NPWP Toko</td>
						<td>: <?= $header['client_pt_npwp'] ?></td>
						<td>TGL minta dikirim</td>
						<td>: <?= $header['delivery_order_tgl_rencana_kirim'] ?></td>
						<td>ALAMAT KIRIM</td>
						<td>: <?= $header['delivery_order_kirim_alamat'] ?></td>
					</tr>
					<tr>
						<td>NIK TOKO</td>
						<td>: </td>
						<td>JATUH TEMPO</td>
						<td>: <?= $header['delivery_order_tgl_expired_do'] ?></td>
						<td>NPWP PENERIMA</td>
						<td>: <?= $header['client_pt_npwp'] ?></td>
					</tr>
				</table>
			</div>

			<!-- Product Table Section -->
			<div class="product-table-section">
				<table class="product-table">
					<thead>
						<tr>
							<th>Kode</th>
							<th>Nama Produk</th>
							<th>Satuan</th>
							<th>Jumlah</th>
							<th>Harga</th>
							<th>Jumlah (Rp)</th>
							<th>Disc 1</th>
							<th>Disc 2</th>
							<th>Disc 3</th>
							<th>H. Jual + PPN</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($FDJRDetail as $detail) :
							if ($header['delivery_order_id'] == $detail['delivery_order_id']) {
						?>
								<tr>
									<td><?= $detail['sku_kode'] ?></td>
									<td><?= $detail['sku_nama_produk'] ?></td>
									<td><?= $detail['sku_satuan'] ?></td>
									<td class="text-number"><?= $detail['sku_qty'] ?></td>
									<td class="text-number"><?= formatRupiah($detail['sku_harga_satuan']) ?></td>
									<td class="text-number"><?= formatRupiah($detail['sku_harga_nett']) ?></td>
									<td class="text-number"><?= formatRupiah($detail['diskon1']) ?></td>
									<td class="text-number"><?= formatRupiah($detail['diskon2']) ?></td>
									<td class="text-number"><?= formatRupiah($detail['diskon3']) ?></td>
									<td class="text-number"><?= formatRupiah($detail['sku_harga_nett_ppn']) ?></td>
								</tr>
						<?php }
						endforeach; ?>
					</tbody>
					<tfoot>
						<?php foreach ($FDJRTotal as $total) :
							if ($header['delivery_order_id'] == $total['delivery_order_id']) {
						?>
								<tr>
									<td colspan="4"></td>
									<td>Jumlah (Rp)</td>
									<td class="text-number"><?= formatRupiah($total['sku_harga_nett']) ?></td>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="2" style="border :0px;">Salesman</td>
									<td colspan="2" style="border :0px;">: <?= $header['sales_nama'] ?></td>
									<td class="text-number" style="border :0px;">Disc 1</td>
									<td class="text-number" style="border :0px;"><?= formatRupiah($total['diskon1']) ?></td>
									<td colspan="2" style="border :0px;text-align: center;font-size: 7pt;">Hormat Kami,</td>
									<td colspan="2" style="border :0px;text-align: center;font-size: 7pt;">Stempel dan tanda tangan PENERIMA</td>
								</tr>
								<tr>
									<td colspan="2" style="border :0px;">Nomor PO Outlet</td>
									<td colspan="2" style="border :0px;">: <?= $header['sales_order_no_po'] ?></td>
									<td class="text-number" style="border :0px;">Disc 2</td>
									<td class="text-number" style="border :0px;"><?= formatRupiah($total['diskon2']) ?></td>
									<td colspan="4" style="border :0px;"></td>
								</tr>
								<tr>
									<td colspan="2" style="border :0px;">Int SO No / Ext DO No</td>
									<td colspan="2" style="border :0px;">: <?= $header['sales_order_kode'] ?></td>
									<td class="text-number" style="border :0px;">Disc 3</td>
									<td class="text-number" style="border :0px;"><?= formatRupiah($total['diskon3']) ?></td>
									<td colspan="4" style="border :0px;"></td>
								</tr>
								<tr>
									<td colspan="4" style="border :0px;"></td>
									<td style="border :0px;">DPP</td>
									<td class="text-number" style="border :0px;"><?= formatRupiah($total['sku_harga_nett']) ?></td>
									<td colspan="4" style="border :0px;"></td>
								</tr>
								<tr>
									<td colspan="4" style="border :0px;"></td>
									<td style="border :0px;">Diskon Global</td>
									<td class="text-number" style="border :0px;"><?= formatRupiah($total['sku_diskon_global_rp']) ?></td>
									<td colspan="4" style="border :0px;"></td>
								</tr>
								<tr>
									<td colspan="4" style="border :0px;">Barang telah dihitung dan diperiksa kondisinya dengan baik.</td>
									<td style="border-top :0px;border-left :0px;border-right :0px;border-bottom :1px dashed;">PPN 11 %</td>
									<td class="text-number" style="border-top :0px;border-left :0px;border-right :0px;border-bottom :1px dashed;"><?= formatRupiah($total['sku_ppn_rp']) ?></td>
									<td style="text-align: center;border :0px;">(<?= $this->session->userdata('pengguna_username') ?>)</td>
									<td style="text-align: center;border :0px;">Approved By:</td>
									<td colspan="2" style="border-top :0px;border-left :0px;border-right :0px;border-bottom :1px dashed;"><?= $header['delivery_order_approve_who'] ?></td>
								</tr>
								<tr>
									<td colspan="4" style="border :0px;">Klaim tidak bisa dilayani setelah pengirim meninggalkan tempat kirim.</td>
									<td style="border :0px;">TOTAL</td>
									<td class="text-number" style="border :0px;"><?= formatRupiah($total['sku_harga_nett_ppn']) ?></td>
									<td colspan="4" style="border :0px;"></td>
								</tr>
								<tr>
									<td colspan="4" style="border :0px;">Pembayaran ke <?= $header['client_wms_nama'] ?></td>
									<td colspan="6" style="border :0px;">TERBILANG : <?= strtoupper(terbilang($total['sku_harga_nett_ppn'])) ?> RUPIAH. </td>
								</tr>
								<tr>
									<td colspan="4" style="border :0px;">Bank BCA No. Rek 088-2870888</td>
									<td colspan="6" style="border :0px;">PEMBAYARAN SAH BILA DILAMPIRKAN FAKTUR ASLI (DO/INVOICE)</td>
								</tr>
								<tr>
									<td colspan="10" style="border :0px;">BARCODE DO</td>
								</tr>
								<tr>
									<td colspan="6" style="border :0px;"></td>
									<td colspan="4" style="border :0px;text-align: right;">Printed <?= date('d F Y H:i:s') ?> <?= $this->session->userdata('pengguna_username') ?> - <?= $this->session->userdata('pengguna_username') ?></td>
								</tr>
						<?php }
						endforeach; ?>
					</tfoot>
				</table>
			</div>

			<?php if ($key_header + 1 < count($FDJR)) { ?>
				<!-- Break halaman di sini -->
				<div class="page-break"></div>
			<?php } ?>

		<?php endforeach; ?>
	</div>

	<?php
	function formatRupiah($angka)
	{
		return number_format($angka, 0, ',', '.');
	}

	function penyebut($nilai)
	{
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " " . $huruf[$nilai];
		} else if ($nilai < 20) {
			$temp = penyebut($nilai - 10) . " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut($nilai % 1000000000);
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai / 1000000000000) . " triliun" . penyebut($nilai % 1000000000000);
		}
		return $temp;
	}

	function terbilang($nilai)
	{
		if ($nilai < 0) {
			$hasil = "minus " . trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}
		return ucfirst($hasil);
	}
	?>

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