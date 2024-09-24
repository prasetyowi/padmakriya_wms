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

	.DTFC_LeftBodyLiner {
		overflow-y: unset !important
	}

	.DTFC_RightBodyLiner {
		overflow-y: unset !important
	}

	/** End Styling Tabs pane */
</style>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<h3 name="CAPTION-EXPORTPENERIMAANSURATJALAN">Export Penerimaan Surat Jalan</h3>
		</div>
		<div class="clearfix"></div>
		<hr>
		<div class="panel panel-default">
			<div class="panel-body form-horizontal form-label-left">
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							<label name="CAPTION-TANGGAL">Tanggal</label>
							<input type="text" id="filter_tanggal" class="form-control" name="filter_tanggal" value="" />
						</div>
						<div class="col-xs-6">
							<label name="CAPTION-PRINCIPLE">Principle</label>
							<select class="form-control select2" id="filter_principle" name="filter_principle">
								<option value=""><label name="CAPTION-PILIH">Pilih</label></option>
								<?php foreach ($Principle as $row) : ?>
									<option value="<?= $row['principle_id'] ?>"><?= $row['principle_kode'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-xs-12 text-right">
							<span id="loadingviewexportpenerimaansuratjalan" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
							<button type="button" id="btn-search-data-penerimaan_surat_jalan" class="btn btn-success" onclick="handlerFilterData(event)"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class=" panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-xs-12">
						<div class="x_content table-responsive">
							<table id="table_export_penerimaan_surat_jalan" width="100%" class="table table-striped text-center">
								<thead>
									<tr style="background:#F0EBE3;">
										<th style="vertical-align: middle;text-align:center;">#</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-IDPRICIPAL">ID Pricipal</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-NAMAPRINCIPAL">Nama Principal</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-IDPRODUK">ID Produk</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-NAMAPRODUK">Nama Produk</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-TANGGALCO">Tanggal CO</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-IDCO">ID CO</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-TOTAL">Total</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-HARGASATUAN">Harga satuan</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-TUJUAN">Tujuan</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>