<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-MENUPERMINTAANPENGELUARANBARANG"></h3>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-PENCARIANPERMINTAANPENGELUARANBARANG"></label></div>
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-NOPPB"></label>
							<div class="col-md-8 col-sm-8">
								<input type="hidden" id="txtpickinglistid" value="" />
								<input type="hidden" id="txtcheckerpb" class="form-control" value="" />
								<input type="hidden" class="form-control" name="txtnoppb" id="txtnoppb" required="required" readonly>
								<input type="hidden" class="form-control" name="lastUpdated" id="lastUpdated" readonly>
								<input type="text" class="form-control" placeholder="Auto Generate" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-TGLPPB"></label>
							<div class="col-md-8 col-sm-8">
								<!-- <input type="text" id="dateppb" class="datepicker" value="" /> -->
								<input type="hidden" id="txtdepoid" value="" />
								<input type="text" id="dateppb" class="form-control" value="<?php echo date('d/m/Y'); ?>" readonly />
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="col-form-label col-md-2 col-sm-2 label-align">Gudang</label>
							<div class="col-md-8 col-sm-8">

								<select id="slcgudang" class="form-control" readonly></select>
							</div>
						</div> -->
						<div class="form-group">
							<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-KETERANGAN"></label>
							<div class="col-md-8 col-sm-8">
								<textarea class="form-control" id="txtketerangan" name="txtketerangan"></textarea>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-NOPB"></label>
							<div class="col-md-8 col-sm-8">
								<select id="slcnopb" class="form-control" onchange="GetFormPengeluaranBarang(this.value)"></select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-NOFDJR"></label>
							<div class="col-md-8 col-sm-8">
								<input type="text" class="form-control" name="txtnofdjr" id="txtnofdjr" required="required" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-TIPEPB"></label>
							<div class="col-md-8 col-sm-8">
								<select id="slctipepb" class="form-control" readonly></select>
								<!-- <select id="slctipepb" class="form-control"></select> -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-DRIVER"></label>
							<div class="col-md-8 col-sm-8">
								<select id="slcaddcheckerbkb" class="form-control" readonly></select>
								<!-- <select id="slctipepb" class="form-control"></select> -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-STATUSPPB"></label>
							<div class="col-md-4 col-sm-4">
								<input type="checkbox" id="slcstatusppb" name="slcstatusppb" style="transform: scale(1.5);margin-right:10px" checked disabled value="In Progress"> In Progress
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>

		<div class="panel panel-default" id="panelbkbbulk" style="display: none;">
			<div class="panel-heading"><label name="CAPTION-DETAILPENGELUARANBARANGBULK"></label></div>
			<!-- <input type="hidden" id="txtcheckerpb" class="form-control" value="" /> -->
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<table id="tabledetailpbbulk" width="100%" class="table table-striped">
						<thead>
							<tr>
								<th name="CAPTION-NO"></th>
								<th name="CAPTION-PRINCIPLE"></th>
								<th name="CAPTION-SKUKODE"></th>
								<th name="CAPTION-NAMABARANG"></th>
								<th name="CAPTION-KEMASAN"></th>
								<th name="CAPTION-SATUAN"></th>
								<th name="CAPTION-JUMLAHBARANG"></th>
								<th name="CAPTION-PERMINTAANEDBARANG"></th>
								<th name="CAPTION-LOKASIGUDANGBARANG"></th>
								<th name="JUMLAHBARANGDIAMBIL"></th>
								<th name="CAPTION-PERMINTAANEDBARANG"></th>
								<!-- <th name="CAPTION-CHECKER"></th> -->
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="panel panel-default" id="panelbkbstandar" style="display: none;">
			<div class="panel-heading"><label>Detail Pengeluaran Barang Picking per DO</label></div>
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-2 col-sm-2 label-align"><label name="CAPTION-CHECKER"></label></label>
							<div class="col-md-8 col-sm-8">
								<select id="slccheckerpb" class="form-control" style="width:100%;"></select>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<table id="tabledetailpbstandar" width="100%" class="table table-striped">
						<thead>
							<tr>
								<th name="CAPTION-NO"></th>
								<th name="CAPTION-NODO"></th>
								<th name="CAPTION-SKUBARANGWMS"></th>
								<th name="CAPTION-NAMABARANG"></th>
								<th name="CAPTION-KEMASAN"></th>
								<th name="CAPTION-SATUAN"></th>
								<th name="CAPTION-JUMLAHBARANG"></th>
								<th name="CAPTION-PERMINTAANEDBARANG"></th>
								<th name="CAPTION-LOKASIGUDANGBARANG"></th>
								<th name="CAPTION-JUMLAHBARANGDIAMBIL"></th>
								<th name="CAPTION-PERMINTAANEDBARANG"></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="panel panel-default" id="panelbkbkirimulang" style="display: none;">
			<div class="panel-heading"><label>Detail Pengeluaran Barang DO Reschedule</label></div>
			<!-- <input type="hidden" id="txtcheckerpb" class="form-control" value="" /> -->
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<table id="tabledetailpbkirimulang" width="100%" class="table table-striped">
						<thead>
							<tr>
								<th name="CAPTION-NO"></th>
								<th name="CAPTION-PRINCIPLE"></th>
								<th name="CAPTION-SKUKODE"></th>
								<th name="CAPTION-NAMABARANG"></th>
								<th name="CAPTION-KEMASAN"></th>
								<th name="CAPTION-SATUAN"></th>
								<th name="CAPTION-JUMLAHBARANG"></th>
								<th name="CAPTION-PERMINTAANEDBARANG"></th>
								<th name="CAPTION-LOKASIGUDANGBARANG"></th>
								<th name="JUMLAHBARANGDIAMBIL"></th>
								<th name="CAPTION-PERMINTAANEDBARANG"></th>
								<!-- <th name="CAPTION-CHECKER"></th> -->
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="panel panel-default" id="panelbkbcanvas" style="display: none;">
			<div class="panel-heading"><label>Detail Pengeluaran Barang Canvas</label></div>
			<!-- <input type="hidden" id="txtcheckerpb" class="form-control" value="" /> -->
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<table id="tabledetailpbcanvas" width="100%" class="table table-striped">
						<thead>
							<tr>
								<th name="CAPTION-NO"></th>
								<th name="CAPTION-PRINCIPLE"></th>
								<th name="CAPTION-SKUKODE"></th>
								<th name="CAPTION-NAMABARANG"></th>
								<th name="CAPTION-KEMASAN"></th>
								<th name="CAPTION-SATUAN"></th>
								<th name="CAPTION-JUMLAHBARANG"></th>
								<th name="CAPTION-PERMINTAANEDBARANG"></th>
								<th name="CAPTION-LOKASIGUDANGBARANG"></th>
								<th name="JUMLAHBARANGDIAMBIL"></th>
								<th name="CAPTION-PERMINTAANEDBARANG"></th>
								<!-- <th name="CAPTION-CHECKER"></th> -->
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="panel">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row pull-right">
					<!-- <button type="button" id="btnkonfirmasibarang" class="btn btn-success">Konfirmasi Pengeluaran Barang Selesai</button> -->
					<button type="button" id="btnsavebkb" class="btn btn-success"><label name="CAPTION-SIMPAN"></label></button>
					<!-- <button type="button" id="btnbatalbkb" class="btn btn-danger">Batal</button> -->
					<a href="<?php echo base_url('WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu'); ?>" class="btn btn-danger"><label name="CAPTION-KEMBALI"></label></a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="previewkonfirmasibkb" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
				<h4 class="modal-title">
					<label name="CAPTION-KONFIRMASIBKB"></label>
				</h4>
				<input type="hidden" id="pickingorderid" />
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<h4>
							<label name="CAPTION-SIMPANBKB"></label>
						</h4>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyeslaksanakanbkb"><label name="CAPTION-IYA"></label></button>
				<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnolaksanakanbkb"><label name="CAPTION-TIDAK"></label></button>
			</div>
		</div>
	</div>
</div>