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
			<div class="title_left" id="pp1">
				<h4 name="CAPTION-LAPORANLEADTIMEDO">Laporan Lead Time DO</h4>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4>Filter Data</h4>
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-6">
								<label name="CAPTION-TANGGAL">Tanggal</label>
								<input type="text" id="filter_stock_tanggal" class="form-control" name="filter_stock_tanggal" value="" />
							</div>
							<div class="col-xs-6">
								<label name="CAPTION-PRINCIPLE">Principle</label>
								<select class="form-control select2" id="filter_stock_principle" name="filter_stock_principle" style="width:100%">
									<option value=""><label name="CAPTION-ALL">All</label></option>
									<?php foreach ($Principle as $row) { ?>
										<option value="<?= $row['principle_id']; ?>"><?= $row['principle_kode']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-xs-12 text-right">
								<span id="loadingviewdodraft" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
								<button type="button" id="btncariAll" class="btn btn-primary" onclick="handlerFilterData(event)"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="row">
					<div class="col-xs-12">
						<div class="x_content table-responsive">
							<table id="table_laporan_lead_time_do" width="100%" class="table table-striped table-bordered">
								<thead>
									<tr class="bg-primary">
										<td class="text-center" style="color:white;"><span name="CAPTION-TIPE">Tipe</span></td>
										<td class="text-center" style="color:white;"><span name="CAPTION-KETERANGAN">Keterangan</span></td>
										<td class="text-center" style="color:white;">0 - 3 (Hari)</td>
										<td class="text-center" style="color:white;">4 - 7 (Hari)</td>
										<td class="text-center" style="color:white;">8 - 14 (Hari)</td>
										<td class="text-center" style="color:white;">15 - 28 (Hari)</td>
										<td class="text-center" style="color:white;">29+ (Hari)</td>
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

	<div class="modal fade" id="modal_detail_so" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-lg" style="width:95%;">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h4 class="modal-title inputTitle"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 table-responsive">
							<table id="table_laporan_lead_time_do_detail_so" width="100%" class="table table-striped text-center">
								<thead>
									<tr class="bg-primary">
										<th class="text-center" style="color:white;">#</th>
										<th class="text-center" style="color:white;"><span name="CAPTION-TANGGALSO">Tanggal SO</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-TGLKIRIM">Tanggal Kirim</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-NOSO">No. SO</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-NOPO">No. PO</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-KODESALES">Kode Sales</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-SALES">Sales</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-KODECUSTOMEREKSTERNAL">Kode Customer Eksternal</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-PRINCIPLE">Principle</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-PERUSAHAAN">Perusahaan</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-CUSTOMER">Customer</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-ALAMAT">Alamat</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-TIPE">Tipe</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-STATUS">Status</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-NOMINALSO">Nominal SO</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-KETERANGAN">Keterangan</span></th>
										<th class="text-center" style="color:white;"><span name="CAPTION-PRIORITY">Prioritas</span></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6">
							<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_detail_do" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-lg" style="width:95%;">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h4 class="modal-title inputTitle"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 table-responsive">
							<table id="table_laporan_lead_time_do_detail_do" width="100%" class="table table-striped text-center">
								<thead>
									<tr class="bg-primary">
										<th class="text-center" style="color:white;">#</th>
										<td class="text-center" style="color:white;"><span name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-NODO">No. DO</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-NOSO">No. SO</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal</span>
										</td>
										<td class="text-center" style="color:white;"><span name="CAPTION-PRINCIPLE">Principle</span>
										<td class="text-center" style="color:white;"><span name="CAPTION-SALES">Sales</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-NAMACUSTOMER">Nama Customer</span>
										</td>
										<td class="text-center" style="color:white;"><span name="CAPTION-ALAMATKIRIM">Alamat Kirim</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-AREA">Area</span> </td>
										</td>
										<td class="text-center" style="color:white;"><span name="CAPTION-TIPE">Tipe</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-UMUR">Umur SO</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-NOMINAL">Nominal</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-STATUS">Status</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-SEGMENT1">Segment 1</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-SEGMENT2">Segment 2</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-SEGMENT3">Segment 3</span> </td>
										<td class="text-center" style="color:white;"><span name="CAPTION-PRIORITY">Prioritas</span> </td>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6">
							<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>