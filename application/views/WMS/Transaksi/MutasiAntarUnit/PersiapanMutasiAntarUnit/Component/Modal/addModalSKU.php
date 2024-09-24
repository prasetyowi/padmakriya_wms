<div class="modal fade" id="modalAddSKU" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" style="width: 85%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label>Tambah SKU</label></h4>
			</div>
			<div class="modal-body">
				<div class="row" style="margin-bottom: 30px;">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h4><strong><label name="CAPTION-FILTERSKU">Filter SKU</label></strong></h4>
								<div class="clearfix"></div>
							</div>
							<div class="container mt-2">
								<div class="row">
									<div class="col-md-3 col-lg-3 col-xl-3 col-xs-12 col-sm-12">
										<div class="form-group">
											<label name="CAPTION-DEPO">Depo</label>
											<input type="text" name="depoFilterSKU" id="depoFilterSKU" class="form-control" readonly>
											<input type="hidden" name="depoIdFilterSKU" id="depoIdFilterSKU" class="form-control" readonly>
											<input type="hidden" name="depoDetailIdFilterSKU" id="depoDetailIdFilterSKU" class="form-control" readonly>
										</div>

										<div class="form-group">
											<label name="CAPTION-GUDANG">Gudang</label>
											<input type="text" name="depoDetailFilterSKU" id="depoDetailFilterSKU" class="form-control" readonly>
										</div>
									</div>
									<div class="col-md-3 col-lg-3 col-xl-3 col-xs-12 col-sm-12">
										<div class="form-group">
											<label name="CAPTION-PRINCIPLE">Principle</label>
											<select name="principleFilterSKU" id="principleFilterSKU" class="form-control select2"></select>
										</div>

										<div class="form-group">
											<label name="CAPTION-BRAND">Brand</label>
											<select name="principleBrandFilterSKU" id="principleBrandFilterSKU" class="form-control select2"></select>
										</div>
									</div>
									<div class="col-md-3 col-lg-3 col-xl-3 col-xs-12 col-sm-12">
										<div class="form-group">
											<label name="CAPTION-SKUINDUK">SKU Induk</label>
											<select name="skuIndukFilterSKU" id="skuIndukFilterSKU" class="form-control select2"></select>
										</div>

										<div class="form-group">
											<label name="CAPTION-NAMASKU">Nama SKU</label>
											<input type="text" name="namaSkuFilterSKU" id="namaSkuFilterSKU" placeholder="Nama SKU" class="form-control">
										</div>
									</div>
									<div class="col-md-3 col-lg-3 col-xl-3 col-xs-12 col-sm-12">
										<div class="form-group">
											<label name="CAPTION-KODESKUWMS">Kode SKU WMS</label>
											<input type="text" name="kodeSKUWMSFilterSKU" id="kodeSKUWMSFilterSKU" placeholder="Kode SKU WMS" class="form-control">
										</div>

										<div class="form-group">
											<label name="CAPTION-KODESKUPABRIK">Kode SKU Pabrik</label>
											<input type="text" name="kodeSKUPabrikFilterSKU" id="kodeSKUPabrikFilterSKU" placeholder="Kode SKU Pabrik" class="form-control">
										</div>
									</div>

									<div class="col-md-12 col-lg-12 col-xl-12 col-xs-12 col-sm-12">
										<div style="margin-top: 10px;">
											<button class="btn btn-primary handlerFilterSKUTambah" onclick="handlerFilterSKUTambah(event)"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="head-switch">
						<div class="switch-holder">
							<div class="switch-toggle">
								<input type="checkbox" id="check_scan" class="check_scan" onchange="handlerChangeViewScan(event)">
								<label for="check_scan"></label>
							</div>
							<div class="switch-label" style="margin-left: -40px;">
								<button type="button" class="btn btn-info startScan" id="startScan" name="startScan" onclick="handlerStartScan(event)"> <i class="fas fa-qrcode"></i> Scan</button>
								<button type="button" class="btn btn-warning inputScan" id="inputScan" name="inputScan" style="display:none" onclick="handlerStartInput(event)"> <i class="fas fa-keyboard"></i> Input</button>
							</div>
						</div>
					</div> -->
				</div>
				<div class="row">
					<div class="col-xs-12 table-responsive">
						<table class="table table-striped" id="tableListSKU" width="100%">
							<thead>
								<tr class="bg-primary text-center text-bold text-white">
									<td><input type="checkbox" name="select-sku" class="check-item" style="transform: scale(1.5)" onclick="handlerSelectAllSKU(event)" id="select-sku" value="1"></td>
									<td><span>SKU Induk</span></td>
									<td><span>SKU Kode</span></td>
									<td><span>SKU Nama Produk</span></td>
									<td><span>SKU Satuan</span></td>
									<td><span>QTY</span></td>
									<td><span>ED Produk</span></td>
									<td><span>QTY Ambil</span></td>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info handlerChoosenSKUInChecked" onclick="handlerChoosenSKUInChecked(event)"><span>Simpan</span></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger" onclick="handlerCloseSKUInChecked(event)"><span name="CAPTION-CLOSE">Tutup</span></button>
			</div>
		</div>
	</div>
</div>