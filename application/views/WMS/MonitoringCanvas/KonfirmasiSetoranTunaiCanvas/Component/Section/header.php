<section>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<div class="clearfix"></div>
				</div>
				<div class="container mt-2">
					<div class="row">
						<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
							<div class="form-group">
								<label>Kode Canvas</label>
								<input type="text" class="form-control" name="kodeCanvas" id="kodeCanvas" value="<?= $dataCanvas->header->canvas_kode ?>" disabled>
								<input type="hidden" class="form-control" name="client_wms_id" id="client_wms_id" value="<?= $dataCanvas->header->client_wms_id ?>" disabled>
								<input type="hidden" class="form-control" name="lastUpdated" id="lastUpdated" value="<?= $dataCanvas->header->canvas_tgl_update ?>" disabled>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
							<div class="form-group">
								<label>Tanggal Permintaan</label>
								<input type="text" class="form-control" name="tanggalPermintaan" id="tanggalPermintaan" value="<?= $dataCanvas->header->canvas_requestdate ?>" disabled>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
							<div class="form-group">
								<label>Perusahaan</label>
								<input type="text" class="form-control" name="perusahaan" id="perusahaan" value="<?= $dataCanvas->header->client_wms_nama ?>" disabled>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
							<div class="form-group">
								<label>Sales</label>
								<input type="text" class="form-control" name="sales" id="sales" value="<?= $dataCanvas->header->sales ?>" disabled>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
							<div class="form-group">
								<label for="area">Area</label>
								<input type="text" class="form-control" name="status" id="status" value="<?= formatReplaceArray($dataCanvas->header->area) ?>" disabled>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
							<div class="form-group">
								<label>Status</label>
								<input type="text" class="form-control" name="status" id="status" value="<?= $dataCanvas->header->canvas_status ?>" disabled>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>