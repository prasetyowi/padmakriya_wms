<section>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<div class="clearfix"></div>
				</div>
				<div class="container mt-2">
					<div class="row">
						<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
							<div class="form-group">
								<label>Kode FDJR</label>
								<input type="text" class="form-control" name="kodeFdjr" id="kodeFdjr" value="<?= $data->header->kode_fdjr ?>" disabled>
								<input type="hidden" class="form-control" name="delivery_order_batch_id" id="delivery_order_batch_id" value="<?= $data->header->delivery_order_batch_id ?>" disabled>
								<input type="hidden" class="form-control" name="delivery_order_id" id="delivery_order_id" value="<?= $data->header->delivery_order_id ?>" disabled>
								<input type="hidden" class="form-control" name="canvas_id" id="canvas_id" value="<?= $data->header->canvas_id ?>" disabled>
								<input type="hidden" class="form-control" name="lastUpdated" id="lastUpdated" value="" disabled>
							</div>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
							<div class="form-group">
								<label>Tipe</label>
								<input type="text" class="form-control" name="tipe" id="tipe" value="<?= $data->header->tipe_fdjr ?>" disabled>
							</div>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
							<div class="form-group">
								<label>Sales</label>
								<input type="text" class="form-control" name="sales" id="sales" value="<?= $data->header->sales ?>" disabled>
							</div>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
							<div class="form-group">
								<label>Tanggal FDJR</label>
								<input type="text" class="form-control" name="tanggalFdjr" id="tanggalFdjr" value="<?= $data->header->tanggal_fdjr ?>" disabled>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
							<div class="form-group">
								<label for="area">Area</label>
								<input type="text" class="form-control" name="area" id="area" value="<?= formatReplaceArray($data->header->area) ?>" disabled>
							</div>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
							<div class="form-group">
								<label>Nopol</label>
								<input type="text" class="form-control" name="nopol" id="nopol" value="<?= $data->header->nopol ?>" disabled>
							</div>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
							<div class="form-group">
								<label>Status FDJR</label>
								<input type="text" class="form-control" name="statusFdjr" id="statusFdjr" value="<?= $data->header->status_fdjr ?>" disabled>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>