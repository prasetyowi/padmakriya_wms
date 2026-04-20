<section>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h4 class="pull-left">Sales Order</h4>
					<?php if ($this->input->get('mode') === 'edit') {  ?>
						<div class="pull-right"><button class="btn btn-info btn-sm" type="button" onclick="handlerAddSO()"><i class="fa fa-plus"></i> <span style="color:white;">Tambah SO</span></button></div>
					<?php } ?>
					<div class="clearfix"></div>
				</div>
				<table class="table table-striped" id="table-so-choose" style="width:100%;">
					<thead>
						<tr class="bg-primary">
							<th class="text-center" style="color:white;"><span>No</span></th>
							<th class="text-center" style="color:white;"><span>Tanggal</span></th>
							<th class="text-center" style="color:white;"><span>Kode SO FAS</span></th>
							<th class="text-center" style="color:white;"><span>Kode SO Exktenal</span></th>
							<th class="text-center" style="color:white;"><span>Customer</span></th>
							<th class="text-center" style="color:white;"><span>Alamat</span></th>
							<th class="text-center" style="color:white;"><span>Keterangan</span></th>
							<?php if ($this->input->get('mode') === 'edit') {  ?>
								<th class="text-center" style="color:white;"><span>Action</span></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<section>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h4 class="pull-left">Summary Data</h4>
					<div class="clearfix"></div>
				</div>
				<table class="table table-striped" id="table-so-summary" style="width:100%;">
					<thead>
						<tr class="bg-primary">
							<th class="text-center" style="color:white;"><span>No</span></th>
							<th class="text-center" style="color:white;"><span>SKU Kode</span></th>
							<th class="text-center" style="color:white;"><span>SKU Nama</span></th>
							<th class="text-center" style="color:white;"><span>SKU Kemasan</span></th>
							<th class="text-center" style="color:white;"><span>SKU Satuan</span></th>
							<th class="text-center" style="color:white;"><span>QTY Canvas</span></th>
							<th class="text-center" style="color:white;"><span>QTY SO</span></th>
							<th class="text-center" style="color:white;"><span>Sisa</span></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</section>