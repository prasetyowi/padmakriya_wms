<style>
	.wrapper {
		display: inline-flex;
		margin-top: 10px;
		height: 40px;
		width: 30%;
		align-items: center;
		justify-content: space-evenly;
		border-radius: 5px;
	}

	.wrapper .option {
		background: #fff;
		height: 100%;
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: space-evenly;
		margin: 0 10px;
		border-radius: 5px;
		cursor: pointer;
		padding: 0 10px;
		border: 2px solid lightgrey;
		transition: all 0.3s ease;
	}

	.wrapper .option .dot {
		height: 20px;
		width: 20px;
		background: #d9d9d9;
		border-radius: 50%;
		position: relative;
	}

	.wrapper .option .dot::before {
		position: absolute;
		content: "";
		top: 4px;
		left: 4px;
		width: 12px;
		height: 12px;
		background: #0069d9;
		border-radius: 50%;
		opacity: 0;
		transform: scale(1.5);
		transition: all 0.3s ease;
	}

	input[type="radio"] {
		display: none;
	}

	#option-1:checked:checked~.option-1,
	#option-2:checked:checked~.option-2 {
		border-color: #0069d9;
		background: #0069d9;
	}

	#option-1:checked:checked~.option-1 .dot,
	#option-2:checked:checked~.option-2 .dot {
		background: #fff;
	}

	#option-1:checked:checked~.option-1 .dot::before,
	#option-2:checked:checked~.option-2 .dot::before {
		opacity: 1;
		transform: scale(1);
	}

	.wrapper .option span {
		font-size: 16px;
		color: #808080;
	}

	#option-1:checked:checked~.option-1 span,
	#option-2:checked:checked~.option-2 span {
		color: #fff;
	}

	/** End Styling Tabs pane */
</style>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-MENULAPORANSTOCKSKU">Menu Laporan Stock SKU</h3>
			</div>
		</div>

		<div class="clearfix"></div>
		<div class="wrapper">
			<input type="radio" name="selectSplit" id="option-1" value="0" class="chkradio" onchange="chkradio(event)" checked>
			<input type="radio" name="selectSplit" id="option-2" value="1" class="chkradio" onchange="chkradio(event)">
			<label for="option-1" class="option option-1">
				<span>Stock SKU</span>
			</label>
			<label for="option-2" class="option option-2">
				<span>Pallet</span>
			</label>
		</div>
		<hr>
		<div id="page1">
			<div class="panel panel-default" id="pp">
				<div class="panel-body form-horizontal form-label-left">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NAMASKU">Nama SKU</label>
								<div class="col-md-8 col-sm-8">
									<input type="text" name="filter_sku" class="form-control" autocomplete="off" id="filter_sku" placeholder="masukkan nama sku">
								</div>
							</div>
						</div>

					</div>
					<div class="row">
						<div id="preview_input_manual" style="margin-top:10px">
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name=""></label>
										<div class="col-md-8 col-sm-8">
											<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll;">
												<table class="table table-striped table-sm table-hover" id="table-fixed">
													<tbody id="konten-table">

													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
					<div class="col-xs-12 text-right">
						<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Memuat Data...</label>...</span>
						<!-- <button type="button" id="btn_stock_pencarian" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button> -->
					</div>
				</div>

			</div>
			<div class="panel panel-default">
				<div class=" panel-body form-horizontal form-label-left">
					<div class="row">
						<table id="tablelaporanstocksku" width="100%" class="table table-striped text-center">
							<thead>
								<tr style="background:#F0EBE3;">
									<th class="text-center" name="CAPTION-NO">No</th>
									<th class="text-center" name="CAPTION-LOKASI">Lokasi</th>
									<th class="text-center" name="CAPTION-PALLET">Pallet</th>
									<th class="text-center" name="CAPTION-ED">ED</th>
									<th class="text-center" name="CAPTION-BATCHNO">Batch No</th>

									<th class="text-center" name="CAPTION-QTY">QTY</th>
								</tr>
							</thead>
							<tbody id="konten-tablelaporanstocksku">

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="page2" style="display:none;">
			<div class="panel panel-default" id="pp">
				<div class="panel-body form-horizontal form-label-left">
					<div class="row">
						<div class="col-lg-6">

							<div class="form-group">
								<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
								<div class="col-md-8 col-sm-8">
									<select class="form-control select2" id="filter_stock_principle" name="filter_stock_principle" style="width:100%">
										<option value=""><label name="CAPTION-ALL">All</label></option>
										<?php foreach ($Principle as $row) { ?>
											<option value="<?= $row['principle_id']; ?>"><?= $row['principle_kode']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KODEPALLET">Pallet</label>
								<div class="col-md-8 col-sm-8">
									<select class="form-control select2" id="filter_pallet" name="filter_pallet" style="width:100%">
										<option value=""><label name="CAPTION-ALL">All</label></option>
										<?php foreach ($Pallet as $row) { ?>
											<option value="<?= $row['pallet_id']; ?>"><?= $row['pallet_kode']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-6">

							<div class="form-group">
								<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-LOKASIRAK">Lokasi Rak</label>
								<div class="col-md-8 col-sm-8">
									<select class="form-control select2" id="filter_rak" name="filter_rak" style="width:100%">
										<option value=""><label name="CAPTION-ALL">All</label></option>
										<?php foreach ($RakLajur as $row) { ?>
											<option value="<?= $row['rak_lajur_detail_id']; ?>"><?= $row['rak_lajur_detail_nama']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xs-12 text-right">
						<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Memuat Data...</label>...</span>
						<button type="button" id="btncariAll" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
					</div>
				</div>

			</div>
			<div class="panel panel-default">
				<div class="panel-body form-horizontal form-label-left">

					<div class="panel-body form-horizontal form-label-left">
						<div class="row">
							<table id="tablelaporanstockskupage2" width="100%" class="table table-striped text-center">
								<thead>
									<tr style="background:#F0EBE3;">
										<th class="text-center" name="CAPTION-NO">No</th>
										<th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
										<th class="text-center" name="CAPTION-PALLET">Pallet</th>
										<th class="text-center" name="CAPTION-LOKASI">Lokasi Rak</th>
										<th class="text-center" name="CAPTION-SKUKODE">SKU Kode</th>
										<th class="text-center" name="CAPTION-SKU">SKU</th>
										<th class="text-center" name="CAPTION-ED">ED</th>
										<th class="text-center" name="CAPTION-BATCHNO">Batch No</th>
										<th class="text-center" name="CAPTION-QTYSTOKAKHIR">Qty Stok Akhir</th>
									</tr>
								</thead>
								<tbody id="konten-tablelaporanstockskupage2">

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>