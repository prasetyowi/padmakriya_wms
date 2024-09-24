`
<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title name="CAPTION-CETAKPORECEIPT">Cetak PO Receipt</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

	<style>
		* {
			margin: 0;
			padding: 0;
			/* box-sizing: border-box; */
		}

		html {
			position: relative;
			min-width: 180mm;
			min-height: 100mm;
		}

		table {
			margin: 0 auto;
			/* or margin: 0 auto 0 auto */
		}
	</style>

</head>

<body>

	<div style="padding: 10px; margin:10px">
		<h3 class="text-center">Bukti Terima Barang</h3>
		<div class="table-responsive mt-4">
			<table class="table table-striped" width="100">
				<thead>
					<tr>
						<td width="5%" class="text-center"><strong>No.</strong></td>
						<td width="10%" class="text-center"><strong>Kode</strong></td>
						<td width="20%"><strong>SKU</strong></td>
						<td width="10%" class="text-center"><strong>Kemasan</strong></td>
						<td width="10%" class="text-center"><strong>Satuan</strong></td>
						<td width="10%" class="text-center"><strong>Batch No</strong></td>
						<td width="10%" class="text-center"><strong>Tgl. Expired</strong></td>
						<td width="10%" class="text-center"><strong>Jumlah Barang</strong></td>
						<td width="10%" class="text-center"><strong>Terima</strong></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($detail as $key => $data) { ?>
						<tr>
							<td class="text-center"><?= $key + 1 ?></td>
							<td class="text-center"><?= $data->sku_kode ?></td>
							<td><?= $data->sku_nama ?></td>
							<td class="text-center"><?= $data->sku_kemasan ?></td>
							<td class="text-center"><?= $data->sku_satuan ?></td>
							<td class="text-center"><?= $data->batch_no == null || $data->batch_no == "" ? "" : $data->batch_no  ?></td>
							<td class="text-center"><?= $data->sku_exp_date ?></td>
							<td class="text-center"><?= $data->jml_barang ?></td>
							<td class="text-center"><?= $data->jml_terima ?></td>
						</tr>

					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>


	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>

	<script>
		$(document).ready(function() {
			window.print();

		});
	</script>

</body>


</html>`