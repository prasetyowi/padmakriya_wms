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