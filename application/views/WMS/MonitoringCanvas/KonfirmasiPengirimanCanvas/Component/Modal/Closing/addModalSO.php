<div class="modal fade" id="modal-addSo" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label>Tambah SO</label></h4>
			</div>
			<div class="modal-body">
				<div class="row" style="margin-bottom: 30px;">
					<label for="brandFilter" class="col-sm-2 col-form-label">Brand</label>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-sm-5">
								<select name="brandFilter" id="brandFilter" class="form-control select2"></select>
							</div>
							<div class="col-sm-7">
								<button class="btn btn-primary" onclick="requestGetDataSO()"><i class="fa fa-search"></i> Filter</button>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-striped" id="table-list-so">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;"><input type="checkbox" name="select-so" onclick="handlerSelectAllSO(event)" id="select-so" value="1"></th>
									<th class="text-center" style="color:white;"><span>Tanggal</span></th>
									<th class="text-center" style="color:white;"><span>Kode SO FAS</span></th>
									<th class="text-center" style="color:white;"><span>Kode SO Exktenal</span></th>
									<th class="text-center" style="color:white;"><span>Customer</span></th>
									<th class="text-center" style="color:white;"><span>Alamat</span></th>
									<th class="text-center" style="color:white;"><span>Keterangan</span></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" onclick="handlerChoosenSOInChecked()"><span name="CAPTION-PILIH">Pilih</span></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger"><span name="CAPTION-CLOSE">Tutup</span></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-sku" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xl" style="width:90%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-CARISKU">Cari SKU</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-4">
								<label name="CAPTION-PRINCIPLE">Principle</label>
								<input type="text" id="filter-principle" name="filter_principle" class="form-control input-sm" />
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-BRAND">Brand</label>
								<input type="text" id="filter-brand" name="filter_brand" class="form-control input-sm" />
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-SKU">SKU</label>
								<input type="text" id="filter-sku-nama-produk" name="filter_sku_nama_produk" class="form-control input-sm" />
							</div>
						</div><br>
						<div class="row">
							<div class="col-xs-4">
								<label name="CAPTION-KEMASAN">Kemasan</label>
								<input type="text" id="filter-sku-kemasan" name="filter_sku_kemasan" class="form-control input-sm" />
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-SATUAN">Satuan</label>
								<input type="text" id="filter-sku-satuan" name="filter_sku_satuan" class="form-control input-sm" />
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 text-left">
								<label>&nbsp;</label>
								<div>
									<span id="loadingsku" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
										<label name="CAPTION-LOADING">Loading</label>...</span>
									<button type="button" id="btn-search-sku" class="btn btn-success" onclick="initDataSKU()"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-striped" id="table-sku">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;"><input type="checkbox" name="check-all-sku" id="check-all-sku" value="1"></th>
									<th class="text-center" style="color:white;" name="CAPTION-PRINCIPLE">Principle</th>
									<th class="text-center" style="color:white;" name="CAPTION-BRAND">Brand</th>
									<th class="text-center" style="color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
									<th class="text-center" style="color:white;" name="CAPTION-SKU">SKU</th>
									<th class="text-center" style="color:white;" name="CAPTION-KEMASAN">Kemasan</th>
									<th class="text-center" style="color:white;" name="CAPTION-SATUAN">Satuan</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-info" id="btn-choose-sku-multi" onclick="initDataSKUSummaryNotHaveDO()"><label name=" CAPTION-PILIH">Pilih</label></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>