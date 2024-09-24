<!DOCTYPE html>
<html>

<head>
	<style>
		@page {
			size: A4 portrait;
			margin: 0;

		}

		@page :first {
			margin-top: 10mm;
			/* Ganti nilai ini sesuai kebutuhan Anda, bisa menggunakan satuan lain seperti px, cm, atau in juga */
		}


		/* Mengatur halaman menjadi dua baris */
		body {
			border: 0;
			font-size: 9px;
			height: 297mm;
			width: 210mm;
			padding-left: 2px;
			;
			/* max-width: 100%;
			min-width: 100%; */
			/* Set tinggi halaman A4 */
			display: flex;
			/* flex-direction: column; */
			overflow: scroll;
		}

		@media print {

			html,
			body {
				width: 210mm;
				height: 297mm;
				/* max-width: 100%;
				min-width: 100%; */
			}

			/* ... the rest of the rules ... */
		}

		.row {
			max-height: 50%;
			max-width: 100%;
			min-width: 100%;
			/* flex: 1; */
			/* Setiap baris mengambil setengah tinggi halaman */
			/* px solid #000; */
			margin: 0;
			padding: 10px;
			min-height: 48%;

		}

		/* Isi konten dalam baris */
		.row p {
			margin: 0;
		}

		/* Tabel dalam baris */
		.table {
			width: 100%;
			max-width: 100%;
			min-width: 100%;
			border-collapse: collapse;
			border: 1px solid;
		}

		.table th,
		.table td {
			font-size: 9px;
			font-family: Arial, Helvetica, sans-serif;
			/* px solid #000; */
			border: 1;
			/* padding: 8px; */
			text-align: left;
		}

		/* Tambahkan tombol cetak */
		/* .print-button {
			text-align: center;
			margin-top: 10px;
		} */
		.row>div.one {
			min-height: 350px;
			max-height: 380px;
			/* border: 1px solid black; */
			margin-bottom: 10px;
		}

		.row>div.two {
			margin-top: 10px;
			min-height: 180px;
			min-height: 180px;
			/* border: 1px solid black; */
			margin-bottom: 10px;
		}

		@media print {
			footer {
				page-break-after: always;
			}
		}
	</style>

</head>

<body>
	<?php
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
			$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
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
		return $hasil;
	}
	?>
	<div style="min-height: 100%;max-height:100%">
		<?php $tempDo = "" ?>
		<?php $tempDo2 = "";

		$lastValueKeys = array();
		foreach ($data_do as $key => $val) {
			if (!isset($lastValueKeys[$val['delivery_order_kode']])) {
				$lastValueKeys[$val['delivery_order_kode']] = $key; // Simpan key terakhir dengan nilai ini
			} else {
				$lastValueKeys[$val['delivery_order_kode']] = $key; // Update key terakhir dengan nilai ini
			}
		} ?>

		<?php foreach ($data_do as $key => $val) { ?>
			<?php $tempDo = $val['delivery_order_kode'] ?>
			<?php
			$style = '';
			if ($key == $lastValueKeys[$val['delivery_order_kode']]) {
				// Key terakhir dengan nilai ini, tambahkan gaya warna
				$style = '';
			} else {
				$style = 'display:none';
			}

			?>
			<div class="row">
				<div class="one">
					<center>
						<table border="0" style="min-height: 100%;min-width:100%;">
							<tr>
								<th style="text-align: left;" colspan="2"><?= $val['client_pt_nama'] ?></th>

								<td style="width: 10rem;"></td>
								<th style="text-align: left;">DELIVERY ORDER KREDIT</th>
								<td style="text-align: left; "></td>
								<td style="text-align: left;"></td>
							</tr>
							<tr>
								<th style="text-align: left;" colspan="2"><?= $val['client_pt_alamat'] ?></th>

								<td style="width: 10rem;"></td>
								<th style="text-align: left;">Delivery number</th>
								<td style="text-align: left; ">:</td>
								<td style="text-align: left;"><?= $val['delivery_order_kode'] ?></td>
							</tr>
							<tr>
								<th style="text-align: left;" colspan="2"><?= $val['client_pt_telepon'] ?> &nbsp; NPWP</th>

								<td style="width: 10rem;"></td>
								<th style="text-align: left;">Tanggal DO</th>
								<td style="text-align: left; ">:</td>
								<td style="text-align: left;"><?= $val['delivery_order_tgl_buat_do'] ?></td>
							</tr>
							<tr>
								<th style="text-align: left;" colspan="2">Kepada YTH : <?= $val['delivery_order_kirim_nama'] . " / " . $val['client_pt_telepon'] ?></th>

								<td style="width: 10rem;"></td>
								<th style="text-align: left;">Jatuh Tempo</th>
								<td style="text-align: left; ">:</td>
								<td style="text-align: left;"><?= $val['delivery_order_tgl_buat_do'] ?></td>
							</tr>
							<tr>
								<th style="text-align: left;" colspan="2"></th>

								<td style="width: 10rem;"></td>
								<th style="text-align: left;">Tanggal Order</th>
								<td style="text-align: left; ">:</td>
								<td style="text-align: left;"><?= $val['delivery_order_tgl_buat_do'] ?></td>
							</tr>
							<tr>
								<th style="text-align: left;" colspan="2">&nbsp; <?= $val['delivery_order_kirim_alamat'] ?></th>

								<td style="width: 10rem;"></td>
								<th style="text-align: left;"></th>
								<td style="text-align: left; "></td>
								<td style="text-align: left;"></td>
							</tr>

						</table>
					</center>

					<div id="" style="overflow-x:auto;margin-top:15px;">
						<table class="table table-bordered table-striped" width="100%" id="table-do-draft" border="1">
							<thead>
								<tr style="background-color: #F1F6F9;">
									<th>Kode<?= count($data_do) ?></th>
									<th>Nama Produk</th>
									<th>Satuan</th>
									<th>Jumlah</label></th>
									<th>Harga Jual</label></th>
									<th>Jumlah(RP)</th>
									<th>Disc</label>
									<th>H.Jual+PPN</label>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($val['data'] as $value) { ?>
									<?php $tempDo2 = $value['delivery_order_kode'] ?>
									<tr class="text-center">
										<td class="td_l1" style="font-size: 10px;width:8%"><?= $value['delivery_order_kode'] ?></td>
										<td class="td_l1" style="font-size: 10px;width:20%"><?= $value['sku_nama_produk'] ?></td>
										<td class="td_l1" style="font-size: 10px;width:8%"><?= $value['sku_satuan'] ?></td>
										<td class="td_l1" style="font-size: 10px;width:8%"><?= $value['sku_qty'] ?></td>
										<td class="td_l1" style="font-size: 10px;width:8%"><?= format_idr(round($value['sku_harga_satuan'])) ?></td>
										<td class="td_l1" style="font-size: 10px;width:8%"><?= format_idr(round($value['jumlah'])) ?></td>
										<td class="td_l1" style="font-size: 10px;width:8%"><?= format_idr(round($value['sku_disc_percent'])) ?></td>
										<td class="td_l1" style="font-size: 10px;width:8%"><?= format_idr(round($value['sku_harga_nett'])) ?></td>
									<?php } ?>

							</tbody>
							<tfoot>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td colspan="4"></td>
									<td colspan="1">Jumlah</td>
									<td colspan="1"><?= (round($val['sum_jumlah'])) ?></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div class="two">
					<table class="" border="0" style="padding:10px;float:left; max-width: 300px;   margin-right: 50px;<?= $style ?>">
						<tr>
							<th style="text-align: left;padding:10px">Cap & Ttd</th>
							<td style="text-align: left;"></td>
							<td style="text-align: left;"></td>

						</tr>
						<tr>
							<td style="text-align: left;font-style:normal;font-family:Arial, Helvetica, sans-serif;padding:5px">Toko/Pembeli</td>
							<td style="text-align: left;;width:5rem"></td>
							<th style="text-align: left;">Hormat Kami</th>



						</tr>
						<tr>
							<td style="text-align: left;font-style:normal;font-family:Arial, Helvetica, sans-serif"></td>
							<td style="text-align: left; height: 3rem;"></td>
							<td style="text-align: left; "></td>


						</tr>
						<tr>
							<td style="text-align: left;font-style:normal;font-family:Arial, Helvetica, sans-serif">---------------</td>
							<td style="text-align: left; "></td>
							<td style="text-align: left; ">---------------</td>


						</tr>
						<tr>
							<td style="text-align: left;font-style:normal;font-family:Arial, Helvetica, sans-serif">Terbilang</td>
							<td style="text-align: left; " colspan="2"> <?= terbilang(round((int)$val['sum_jumlah'])) ?></td>


						</tr>


					</table>


					<table class="" <?php echo 'style="' . $style . '"' ?>" border="0" style="margin:0">
						<tr>
							<th style="text-align: left;">Disc</th>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;"><?= $val['sum_sku_disc_percent'] ?></td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">Salesman</td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">0</td>

						</tr>
						<tr>
							<th style="text-align: left;"> </th>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;"> </td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">Divisi</td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">0</td>

						</tr>
						<tr>
							<th style="text-align: left;"> </th>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;"> </td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">PO NO</td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;"><?= $val['delivery_order_kode'] ?></td>

						</tr>
						<tr>
							<th style="text-align: left;">DPP</th>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">0</td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">INT SO/DO NO</td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">0</td>

						</tr>
						<tr>
							<th style="text-align: left;">PPN</th>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">0</td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">Cetakan</td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">0</td>

						</tr>
						<tr>
							<th style="text-align: left;">Total</th>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;">0</td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;" colspan="2">Harga Jual Sudah Termasuk PPN</td>

							<td style="text-align: left;">0</td>

						</tr>
						<tr>
							<th style="text-align: left;"></th>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;"></td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;" colspan="2"> </td>

							<td style="text-align: left;"></td>

						</tr>
						<tr>
							<th style="text-align: left;">Dicetak</th>
							<td style="text-align: left;"><?= date('d-m-Y') ?></td>
							<td style="width: 4rem;"></td>
							<td style="width: 4rem;"></td>
							<td style="text-align: left;" colspan="2"></td>

							<td style="text-align: left;">0</td>

						</tr>

					</table>
				</div>
			</div>
		<?php } ?>
	</div>


	<!-- Tombol cetak -->
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous">
</script>

<script>
	// window.print();

	$(document).ready(function() {

		window.print();
	});
</script>

</html>