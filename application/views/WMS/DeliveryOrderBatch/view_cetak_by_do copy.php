<!DOCTYPE html>
<html>

<head>
	<style>
		@page {
			size: A4;
			margin: 0;
		}

		body {
			margin: 0;
			padding: 0;
			display: flex;
			flex-direction: column;
			/* height: 100vh; */

		}

		table {
			margin: 0 auto;
			font-size: 12px;
		}

		.container {
			display: flex;
			flex: 1;
			/* height: 50vh; */
			background-color: #000;
			/* Biarkan container menempati sebanyak mungkin ruang yang tersedia */

			/* Sembunyikan overflow jika konten melebihi ukuran container */
			flex-direction: column;
			/* Menjadikan flex container menjadi kolom (vertikal) */
		}

		.row {
			background-color: #F1F6D9;
			flex: 1;
			/* Bagi container menjadi dua baris */
			border: 1px solid #000;
			padding: 10px;
			margin: 5px;
			/* Tambahkan margin untuk pemisah antara dua baris */
		}

		.break {
			page-break-after: always;
			/* page-break-before: always; */
			/* page-break-inside: avoid; */
		}

		.eek {
			background-color: #F1D6F9;
			page-break-after: always;

		}

		.eek2 {
			background-color: #FFFF12;
			/* kuning */
		}
	</style>
	<!-- <style>
		@page {
			size: 210mm 297mm;
			margin: 0;
		}

		body {
			margin: 0;
			padding: 0;
		}

		.container {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			height: 100%;
		}

		.row {
			max-height: 15mm;
			/* Lebar A4 */
			/* max-height: 100%; */
			margin: 5mm;
			/* Jarak antara baris */
			border: 1px solid #000;
			page-break-before: always;
			/* Membuat halaman baru sebelum setiap baris */
		}

		.content {
			padding: 10px;
		}
	</style> -->
</head>

<body>
	<div class="container">
		<?php foreach ($bkbbulk as $key => $val) { ?>
			<div class="row">
				<div class="<?= ($key % 2 === 0)  ? 'eek2' : 'eek' ?> content">
					<center>
						<table border="1">
							<tr>
								<th style="text-align: left;border: 1;font-size:13px;" colspan="2">PT PADMA SALES NIMAS ZUHROTI KIRANA_____<?= $key ?></th>

								<td style="width: 8rem;border: 1;"></td>
								<th style="text-align: left;border: 1;font-size:13px;">DELIVERY ORDER KREDIT</th>
								<td style="text-align: left;border: 1; "></td>
								<td style="text-align: left;border: 1;"></td>
							</tr>
							<tr>
								<th style="text-align: left;border: 1;font-size:13px;" colspan="2">KENJERAN 501KENJERAN 501KENJERAN 501</th>

								<td style="width: 8rem;border: 1;"></td>
								<th style="text-align: left;border: 1;font-size:13px;">Delivery number</th>
								<td style="text-align: left;border: 1; ">:</td>
								<td style="text-align: left;border: 1;">999-9999-99999912121</td>
							</tr>
							<tr>
								<th style="text-align: left;border: 1;font-size:13px;" colspan="2">TELEPOnn &nbsp; NPWP</th>

								<td style="width: 8rem;border: 1;"></td>
								<th style="text-align: left;border: 1;font-size:13px;">Tanggal DO</th>
								<td style="text-align: left;border: 1; ">:</td>
								<td style="text-align: left;border: 1;">999-9999-999999</td>
							</tr>
							<tr>
								<th style="text-align: left;border: 1;font-size:13px;" colspan="2">Kepada YTH : FARISBOY / OK (120192019211)</th>

								<td style="width: 8rem;border: 1;"></td>
								<th style="text-align: left;border: 1;font-size:13px;">Jatuh Tempo</th>
								<td style="text-align: left;border: 1; ">:</td>
								<td style="text-align: left;border: 1;">999-9999-999999</td>
							</tr>
							<tr>
								<th style="text-align: left;border: 1;font-size:13px;" colspan="2">&nbsp; PASAR YOSO SUBAYRAY JATIM</th>

								<td style="width: 8rem;border: 1;"></td>
								<th style="text-align: left;border: 1;font-size:13px;">Tanggal Order</th>
								<td style="text-align: left;border: 1; "></td>
								<td style="text-align: left;border: 1;">999-9999-999999</td>
							</tr>

						</table>
					</center>

					<div id="" style="overflow-x:auto;margin-top:15px;">
						<table class="table table-bordered table-striped" width="100%" id="table-do-draft">
							<thead>
								<tr style="background-color: #F1F6F9;">
									<th>Kode<?= count($bkbbulk) ?></th>
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
								<?php foreach ($val as $value) { ?>

									<tr class="text-center">
										<td class="td_l1" style="font-size: 12px"><?= $value['delivery_order_kode'] ?></td>
										<td class="td_l1" style="font-size: 12px"><?= $value['sku_nama_produk'] ?></td>
										<td class="td_l1" style="font-size: 12px">pcs</td>
										<td class="td_l1" style="font-size: 12px"><?= $value['sku_qty'] ?></td>
										<td class="td_l1" style="font-size: 12px"><?= $value['sku_harga_satuan'] ?></td>
										<td class="td_l1" style="font-size: 12px"><?= $value['jumlah'] ?></td>
										<td class="td_l1" style="font-size: 12px"><?= 1 ?></td>
										<td class="td_l1" style="font-size: 12px"><?= $value['sku_harga_nett'] ?></td>
									<?php } ?>

							</tbody>
						</table>
					</div>
					<table class="<?= $key + 1 == count($bkbbulk) ? 'owl' : ''; ?>">
						<tr>
							<th style="text-align: left;border: 1; margin-left:10px; font-size:11px;">Biaya bensin</th>
							<td style="text-align: left;border: 1; "></td>
							<td style="text-align: left;border: 1;"></td>

							<td style="text-align: left;border: 1;"></td>
							<td style="width: 8rem;border: 1;"></td>
							<th style="text-align: left;border: 1; margin-left:10px; font-size:11px;">Total Tunai</th>
							<td style="text-align: left;border: 1; ">:</td>
							<td style="text-align: left;border: 1;"></td>
						</tr>
						<tr>
							<td style="text-align: left;border: 1;font-style:normal;font-family:Arial, Helvetica, sans-serif">Rupiah</td>
							<td style="text-align: left;border: 1; ">:</td>
							<td style="text-align: left;border: 1;"></td>

							<td style="text-align: left;border: 1;"></td>
							<td style="width: 8rem;border: 1;"></td>
							<th style="text-align: left;border: 1; margin-left:10px; font-size:11px;">Odo Meter Pulang</th>
							<td style="text-align: left;border: 1; ">:</td>
							<td style="text-align: left;border: 1;"></td>
						</tr>
						<tr>
							<td style="text-align: left;border: 1;font-style:normal;font-family:Arial, Helvetica, sans-serif">Liter</td>
							<td style="text-align: left;border: 1;">:</td>
							<td style="text-align: left;border: 1;"></td>
							<td style="text-align: left;border: 1;"></td>
							<td style="width: 8rem;border: 1;"></td>
							<th style="text-align: left;border: 1; margin-left:10px; font-size:11px;"></th>
							<td style="text-align: left;border: 1;"></td>
							<td style="text-align: left;border: 1;"></td>
						</tr>


					</table>
				</div>
			</div>
		<?php } ?>
	</div>
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
