<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?= $title ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

	<style>
		* {
			margin: 0;
			padding: 0;
			font-size: 13px;
		}

		@page {
			size: A4 landscape;
		}

		.div-header-first {
			width: 42.5%;
		}

		.div-header-second {
			width: 25%;
		}

		.div-child-header-first {
			width: 34%;
		}

		.div-child-header-second {
			width: 1%;
		}

		.div-child-header-third {
			width: 65%;
		}

		textarea {
			resize: none;
		}

		.barcode-img {
			width: 90%;
			height: 80px;
			position: relative;
			left: 50%;
			transform: translateX(-50%);
		}

		.page-break {
			page-break-inside: avoid
		}
	</style>

</head>

<body>

	<?php if ($datas) { ?>
		<?php foreach ($datas as $key => $value) { ?>
			<section class="mx-4 my-2 page-break">
				<h3 class="my-3 mb-3 text-center"><?= $title ?></h3>
				<hr>
				<div class="d-flex justify-content-start gap-4 mt-3">
					<div class="div-header-first">
						<div class="mb-2 d-flex gap-3">
							<div class="div-child-header-first">
								<h6>Tanggal Mutasi</h6>
							</div>
							<div class="div-child-header-second"> : </div>
							<div class="div-child-header-third">
								<input type="text" class="form-control" disabled value="<?= $value['tanggal'] ?>">
							</div>
						</div>

						<div class="mb-2 d-flex gap-3">
							<div class="div-child-header-first">
								<h6>Depo Asal</h6>
							</div>
							<div class="div-child-header-second"> : </div>
							<div class="div-child-header-third">
								<input type="text" class="form-control" disabled value="<?= $value['depoAsal'] ?>">
							</div>
						</div>

						<div class="mb-2 d-flex gap-3">
							<div class="div-child-header-first">
								<h6>Gudang Asal</h6>
							</div>
							<div class="div-child-header-second"> : </div>
							<div class="div-child-header-third">
								<input type="text" class="form-control" disabled value="<?= $value['gudangAsal'] ?>">
							</div>
						</div>

						<div class="mb-2 d-flex gap-3">
							<div class="div-child-header-first">
								<h6>Alamat Depo Asal</h6>
							</div>
							<div class="div-child-header-second"> : </div>
							<div class="div-child-header-third">
								<textarea cols="30" rows="4" class="form-control" disabled><?= $value['alamatDepoAsal'] ?></textarea>
							</div>
						</div>
					</div>
					<div class="div-header-first">
						<div class="mb-2 d-flex gap-3">
							<div class="div-child-header-first">
								<h6>Depo Tujuan</h6>
							</div>
							<div class="div-child-header-second"> : </div>
							<div class="div-child-header-third">
								<input type="text" class="form-control" disabled value="<?= $value['depoTujuan'] ?>">
							</div>
						</div>

						<div class="mb-2 d-flex gap-3">
							<div class="div-child-header-first">
								<h6>Alamat Depo Tujuan</h6>
							</div>
							<div class="div-child-header-second"> : </div>
							<div class="div-child-header-third">
								<textarea cols="30" rows="4" class="form-control" disabled><?= $value['alamatDepoTujuan'] ?></textarea>
							</div>
						</div>

						<div class="mb-2 d-flex gap-3">
							<div class="div-child-header-first">
								<h6>Ekspedisi</h6>
							</div>
							<div class="div-child-header-second"> : </div>
							<div class="div-child-header-third">
								<input type="text" class="form-control" disabled value="<?= $value['ekspedisi'] ?>">
							</div>
						</div>

						<div class="mb-2 d-flex gap-3">
							<div class="div-child-header-first">
								<h6>Pengemudi</h6>
							</div>
							<div class="div-child-header-second"> : </div>
							<div class="div-child-header-third">
								<input type="text" class="form-control" disabled value="<?= $value['pengemudi'] ?>">
							</div>
						</div>

						<div class="mb-2 d-flex gap-3">
							<div class="div-child-header-first">
								<h6>Kendaraan</h6>
							</div>
							<div class="div-child-header-second"> : </div>
							<div class="div-child-header-third">
								<input type="text" class="form-control" disabled value="<?= $value['kendaraan'] ?>">
							</div>
						</div>

						<div class="mb-2 d-flex gap-3">
							<div class="div-child-header-first">
								<h6>Nopol</h6>
							</div>
							<div class="div-child-header-second"> : </div>
							<div class="div-child-header-third">
								<input type="text" class="form-control" disabled value="<?= $value['nopol'] ?>">
							</div>
						</div>
					</div>
					<div class="div-header-second">
						<img src="data:image/png;base64,<?= base64_encode($barcodeGenerate->getBarcode($value['kodeMutasi'], $barcodeGenerate::TYPE_CODE_128)) ?>" class="barcode-img">
						<p class="text-center" style="margin-top:5px; font-size: 20px"><strong><?= $value['kodeMutasi'] ?></strong></p>

						<div class="form-group mt-4">
							<h6 class="text-center">Keterangan Mutasi</h6>
							<textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" disabled><?= $value['keterangan'] ?></textarea>
						</div>
					</div>
				</div>

				<div class="mt-4 table-responsive">
					<table class="table table-striped table-bordered" width="100%">
						<thead>
							<tr class="text-center">
								<th>No.</th>
								<th>SKU Kode</th>
								<th>Nama Produk</th>
								<th>Satuan</th>
								<th>Qty</th>
								<th>Expired Date</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($value['data'] as $number => $itemChild) { ?>
								<tr class="text-center">
									<td><?= $number + 1 ?></td>
									<td><?= $itemChild->sku_kode ?></td>
									<td><?= $itemChild->sku_nama_produk ?></td>
									<td><?= $itemChild->sku_satuan ?></td>
									<td><?= $itemChild->qty_ambil ?></td>
									<td><?= $itemChild->expired_date ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>

				<div class="mt-4" class="page-break">
					<h5 class="text-center">Mengetahui</h5>
					<div class="d-flex justify-content-center align-items-center text-center mt-5">
						<div style="width: 50%;">
							<h6>TTD</h6><br><br><br><br>
							<h6>.........................................</h6>
						</div>
						<div style="width: 50%;">
							<h6>TTD</h6><br><br><br><br>
							<h6>.........................................</h6>
						</div>
					</div>
				</div>
			</section>
		<?php } ?>
	<?php } ?>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>


	<script>
		window.print();
	</script>
</body>

</html>