<div class="modal fade" id="list_data_pilih_sku" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" style="width:90%">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-DATASKUINDUK">Data SKU</h4>
			</div>
			<div class="modal-body">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="container mt-2">
							<div class="row">

								<div class="form-group">
									<label for="filterPrincipleSKU" name="CAPTION-PRINCIPLE">Principle</label>
									<select name="filterPrincipleSKU" id="filterPrincipleSKU" onchange="handlerFilterSKU(event)" class="form-control select2">
										<option value="">--Pilih Principle--</option>
										<?php foreach ($principle as $key => $value) { ?>
											<option value="<?= $value->principle_id ?>"><?= $value->principle_nama ?></option>
										<?php } ?>
									</select>
								</div>

								<!-- <button class="btn btn-primary btn-sm" onclick="handlerFilterSKU(event)"><i class="fa fa-search"></i> <span>Filter</span></button> -->

								<div class="x_content table-responsive" style="margin-top: 15px;">
									<table id="table_list_data_pilih_sku" width="100%" class="table table-xs table-striped" style="font-size:11px;">
										<thead>
											<tr>
												<td class="text-center" width="5%">
													<input type="checkbox" id="check-all-pilih-sku" class="form-control input-sm" width="20" onchange="checkAllSKU(this)" />
												</td>
												<td width="7%"><strong><label name="CAPTION-SKUKODE">SKU Kode</label></strong></td>
												<td width="15%"><strong><label name="CAPTION-SKU">SKU</label></strong></td>
												<td class="text-center" width="10%"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
												<td class="text-center" width="10%"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn_pilih_sku"><i class="fas fa-plus"></i> <label name="CAPTION-PILIH">Pilih</label></button>
				<button type="button" class="btn btn-dark btn_close_list_data_pilih_sku"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>

</div>