<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-109012000">Closing Gudang</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<!-- <div class="x_title">
						<div class="clearfix"></div>
						<div style="float: right;">
							<button onclick="modalDetail()" class="btn btn-primary">Detail</button>
						</div>
					</div> -->

					<div style="float: right;">
						<button onclick="modalDetail()" class="btn btn-warning"><label name="CAPTION-HISTORI">Histori</label></button>
					</div>
					<hr style="margin-top: 50px;">
					<div class="form-group">
						<div class="row">
							<div class="col-xs-4">
								<label name="CAPTION-TGLTUTUPGUDANG">Tanggal Closing Gudang</label>
								<input readonly type="date" id="ClosingGudang-closing_gudang_tgl" class="form-control" name="ClosingGudang[closing_gudang_tgl]" value="<?= getLastTbgDepo() ?>" />
							</div>
							<div class="col-xs-4">
								<br>
								<span id="loadingviewclosing" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
								<button type="button" id="btn_filter" class="btn btn-primary"><i class="fa fa-search"></i> <span name="CAPTION-FILTER">Filter</span></button>
								<button type="button" id="btn_proses_closing" class="btn btn-success"><i class="fa fa-save"></i> <span name="CAPTION-Proses">Proses</span></button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h3>
								<span class="label label-danger" id="status_not_closing">Not Closed</span>
								<span class="label label-success" id="status_closing" style="display: none;">Not Closed</span>
							</h3>
							<div class="x_content table-responsive">
								<table id="table_closing_gudang" width="100%" class="table table-striped table-bordered">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;">#</th>
											<th class="text-center" style="color:white;"><span name="CAPTION-JENISDOKUMEN">Jenis Dokumen</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-DRAFTSTATUS">Draft</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-DILAKSANAKAN">Dilaksanakan</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-DIBATALKAN">Dibatalkan</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-TOTAL">Total</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-STATUS">Status</span></th>
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
</div>

<div class="modal fade" id="modal-detail-dokumen" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label id="kode-dokumen"></label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-striped" id="table-dokumen">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;">#</th>
									<th class="text-center" style="color:white;" name="CAPTION-NODOKUMEN">No Dokumen</th>
									<th class="text-center" style="color:white;" name="CAPTION-STATUS">Status</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<span id="loadingviewclosingdetail" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
				<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="previewdetailtutupgudang" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="width: 80%;">
		<!-- Modal content-->
		<div class="modal-content modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-header bg-primary">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h4 class="modal-title"><label name="CAPTION-HISTORI">HISTORI</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="table-responsive">
						<table class="table table-striped" id="list-detail-tutup-gudang">
							<thead class="bg-primary">
								<tr>
									<th class="text-center" style="color: white;" name="CAPTION-KODE">Kode</th>
									<th class="text-center" style="color: white;" name="CAPTION-BULAN">Bulan</th>
									<th class="text-center" style="color: white;" name="CAPTION-TAHUN">Tahun</th>
									<th class="text-center" style="color: white;" name="CAPTION-TANGGALBUAT">Tanggal Buat</th>
									<th class="text-center" style="color: white;" name="CAPTION-TANGGALTBG">Tanggal TBG</th>
									<th class="text-center" style="color: white;" name="CAPTION-TANGGALNEXTTBG">Tanggal NEXT TBG</th>
									<th class="text-center" style="color: white;" name="CAPTION-STATUS">STATUS</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<span id="loadingupdate" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>