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
			<h3 name="CAPTION-133001100">Export FDJR Bosnet</h3>
		</div>
		<div class="clearfix"></div>
		<hr>
		<div class="panel panel-default">
			<div class="panel-body form-horizontal form-label-left">
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							<label name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
							<input type="text" id="filter_tanggal_kirim" class="form-control" name="filter_tanggal_kirim" value="" />
						</div>
						<div class="col-xs-6">
							<label name="CAPTION-PENGEMUDI">Pengemudi</label>
							<select class="form-control select2" id="filter_pengemudi" name="filter_pengemudi">
								<option value=""><label name="CAPTION-PILIH">Pilih</label></option>
								<?php foreach ($Pengemudi as $row) : ?>
									<option value="<?= $row['szEmployeeId'] ?>"><?= $row['szEmployeeId'] . " || " . $row['szName'] ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-xs-6">
							<label name="CAPTION-GUDANG">Gudang</label>
							<select class="form-control select2" id="filter_gudang" name="filter_gudang">
								<option value=""><label name="CAPTION-PILIH">Pilih</label></option>
								<?php foreach ($Gudang as $row) : ?>
									<option value="<?= $row['szWarehouseId'] ?>"><?= $row['szWarehouseId'] . " || " . $row['szName'] ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-xs-6">
							<label name="CAPTION-RITKE">Rit Ke</label>
							<input type="text" id="filter_rit" class="form-control" name="filter_rit" value="" />
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-xs-12 text-right">
							<span id="loadingviewexportfdjr" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
							<button type="button" id="btn-search-data-fdjr" class="btn btn-success" onclick="handlerFilterData(event)"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
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
							<table id="table_export_fdjr" width="100%" class="table table-striped text-center">
								<thead>
									<tr style="background:#F0EBE3;">
										<th style="vertical-align: middle;text-align:center;">#</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-IDPENGEMUDI">ID PENGEMUDI</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-TANGGALPENGIRIMAN">TANGGAL PENGIRIMAN</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-IDSO">ID SO</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-IDDO">ID DO</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-ID KENDARAAN">IDKENDARAAN</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-RITKE">RIT KE</th>
										<th style="vertical-align: middle;text-align:center;" name="CAPTION-IDGUDANG">ID GUDANG</th>
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