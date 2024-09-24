<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>report hasil stok opname <?= $header->kode ?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<style>
		@media print {
			@page {
				size: A4 landscape;
			}

			.table2 {
				/* page-break-inside: avoid; */
				font-family: Arial, Helvetica, sans-serif;
				width: 100%;
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
		}
	</style>
</head>

<body>
	<div class="mx-5">
		<center>
			<h3>Hasil Stock Opname <?= $header->kode ?></h3>
			<hr>
		</center>

		<div class="mt-2">
			<table style="padding-bottom: 10px">
				<tr style="page-break-before:avoid">
					<th style="text-align: left; border: 0;">No. Dokumen</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header->kode ?></td>
					<td style="width: 12rem; border: 0;"></td>
					<th style="text-align: left; border: 0;">Tipe Stock Opname</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header->tipe_stock_opname ?></td>
				</tr>
				<tr style="page-break-before:avoid">
					<th style="text-align: left; border: 0;">Jenis Stock</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header->jenis_stock ?></td>
					<td style="width: 12rem; border: 0;"></td>
					<th style="text-align: left; border: 0;">Principle</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header->principle == null ? 'All' : $header->principle ?></td>


				</tr>
				<tr style="page-break-before:avoid">
					<th style="text-align: left; border: 0;">Perusahaan</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header->perusahaan == null ? 'All' : $header->perusahaan ?></td>
					<td style="width: 12rem; border: 0;"></td>
					<th style="text-align: left; border: 0;">Area Opname</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header->area_opname ?></td>

				</tr>
				<tr style="page-break-before:avoid">
					<th style="text-align: left; border: 0;">Unit Cabang</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header->unit_cabang ?></td>
					<td style="width: 12rem; border: 0;"></td>
					<th style="text-align: left; border: 0;">Tanggal</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header->tanggal ?></td>

				</tr>
				<tr style="page-break-before:avoid">
					<th style="text-align: left; border: 0;">Penanggung Jawab</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header->penanggung_jawab ?></td>
					<td style="width: 12rem; border: 0;"></td>
					<th style="text-align: left; border: 0;"></th>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>

				</tr>
				<tr style="page-break-before:avoid">
					<th style="text-align: left; border: 0;">Keterangan</th>
					<td style="text-align: left; border: 0;">:</td>
					<td style="text-align: left; border: 0;"><?= $header->keterangan == null ? '-' : $header->keterangan ?></td>
					<td style="width: 12rem; border: 0;"></td>
					<th style="text-align: left; border: 0;"></th>
					<td style="text-align: left; border: 0;"></td>
					<td style="text-align: left; border: 0;"></td>

				</tr>
			</table>
		</div>
		<hr>
		<?php foreach ($detail as $key => $value) : ?>
			<div style="margin-top:15px;">
				<div style="margin:10px 10px 10px 0px">
					<h5 style="display:inline; font-size: small"><strong><?= $key ?>,</strong></h5>
					<h5 style="display:inline; font-size: small"><strong><?= $value['sku_nama_produk'] ?>,</strong></h5>
					<h5 style="display:inline; font-size: small"><strong><?= $value['sku_expired_date'] ?>,</strong></h5>
					<h5 style="display:inline; font-size: small"><strong><?= $value['sku_kemasan'] ?>,</strong></h5>
					<h5 style="display:inline; font-size: small"><strong><?= $value['sku_satuan'] ?>,</strong></h5>
					<h5 style="display:inline; font-size: small"><strong>Total Qty Sistem : <?= $value['tot_qty_sistem'] ?>,</strong></h5>
					<h5 style="display:inline; font-size: small"><strong>Total Qty Aktual <?= $value['tot_qty_aktual'] ?>,</strong></h5>
					<h5 style="display:inline; font-size: small"><strong>Total Deviasi <?= $value['tot_deviasi'] ?></strong></h5>
				</div>

				<div class="table-responsive">
					<table class="table table-striped table-bordered" width="100%">
						<thead>
							<tr class="text-center">
								<td width="10%"><strong><label name="CAPTION-KODERAK">Kode Rak</label></strong></td>
								<td width="10%"><strong><label name="CAPTION-KODELOKASI">Kode Lokasi</label></strong></td>
								<td width="10%"><strong><label name="CAPTION-KODEPALLET">Kode Pallet</label></strong></td>
								<td width="8%"><strong><label name="CAPTION-QTYSISTEM">Qty Sistem</label></strong></td>
								<td width="8%"><strong><label name="CAPTION-QTYAKTUAL">Qty Aktual</label></strong></td>
								<td width="8%"><strong><label name="CAPTION-DEVIASI">Deviasi</label></strong></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($value['data'] as $keys => $v) : ?>
								<tr>
									<?php if ($keys == 0) :  ?>
										<td style="vertical-align: middle" class="text-center" rowspan="<?= count($value['data']) ?>"><?= $value['rak_lajur_detail_nama'] ?></td>
										<td style="vertical-align: middle" class="text-center" rowspan="<?= count($value['data']) ?>"><?= $value['area'] ?></td>
									<?php endif; ?>
									<td class="text-center"><?= $v['pallet_kode'] ?></td>
									<td class="text-center"><?= $v['sku_qty_sistem'] ?></td>
									<td class="text-center"><?= $v['sku_actual_qty_opname'] ?></td>
									<td class="text-center"><?= $v['deviasi'] ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endforeach; ?>
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
</body>

</html>