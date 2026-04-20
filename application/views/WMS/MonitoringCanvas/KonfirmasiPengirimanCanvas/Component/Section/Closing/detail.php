<section>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h4 class="pull-left">List Permintaan</h4>
					<div class="clearfix"></div>
				</div>
				<table class="table table-striped" id="table-list-permintaan" style="width:100%;">
					<thead>
						<tr class="bg-primary">
							<th class="text-center" style="color:white;"><span>No</span></th>
							<th class="text-center" style="color:white;"><span>Tanggal PPC</span></th>
							<th class="text-center" style="color:white;"><span>No PPC</span></th>
							<th class="text-center" style="color:white;"><span>No DO</span></th>
							<th class="text-center" style="color:white;"><span>Customer</span></th>
							<th class="text-center" style="color:white;"><span>Alamat</span></th>
							<th class="text-center" style="color:white;"><span>Area</span></th>
							<th class="text-center" style="color:white;"><span>Status DO</span></th>
							<th class="text-center" style="color:white;"><span>Action</span></th>
						</tr>
					</thead>
					<tbody>
						<tr class="text-center">
							<td>1</td>
							<td><?= $data->listPermintaan->tanggal ?></td>
							<td><?= $data->listPermintaan->canvas_kode ?></td>
							<td><?= $data->listPermintaan->delivery_order_kode ?></td>
							<td><?= $data->listPermintaan->customer ?></td>
							<td><?= $data->listPermintaan->alamat ?></td>
							<td><?= formatReplaceArray($data->listPermintaan->area) ?></td>
							<td><?= $data->listPermintaan->delivery_order_status ?></td>
							<td>
								<?php if ($data->header->is_confirm_canvas == null || $data->header->is_confirm_canvas == 1) { ?>
									<button class="btn btn-primary" onclick="handlerDownloadCanvasSO('<?= $data->listPermintaan->canvas_id ?>', '<?= $data->listPermintaan->delivery_order_batch_id ?>')"><i class="fas fa-download"></i> Download Canvas SO</button>
								<?php } ?>
							</td>
						</tr>
					</tbody>
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
					<h4 class="pull-left">List DO</h4>
					<div class="clearfix"></div>
				</div>
				<table class="table table-striped" id="table-list-do" style="width:100%;">
					<thead>
						<tr class="bg-primary">
							<th class="text-center" style="color:white;"><span>No</span></th>
							<th class="text-center" style="color:white;"><span>Tanggal DO</span></th>
							<th class="text-center" style="color:white;"><span>No. SO FAS</span></th>
							<th class="text-center" style="color:white;"><span>No. SO Ext</span></th>
							<th class="text-center" style="color:white;"><span>No. DO</span></th>
							<th class="text-center" style="color:white;"><span>Customer</span></th>
							<th class="text-center" style="color:white;"><span>Alamat</span></th>
							<th class="text-center" style="color:white;"><span>Tipe Pembayaran</span></th>
							<th class="text-center" style="color:white;"><span>Status DO</span></th>
							<th class="text-center" style="color:white;"><span>Action</span></th>
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
					<div class="pull-right">
						<?php if ($data->header->status_fdjr === 'In Process Receiving Outlet') { ?>
							<?php if ($data->header->is_confirm_canvas == 2 && $checkCreateBTB == 0) { ?>
								<!-- <button class="btn btn-primary btn-sm btn-create-btb" type="button" onclick="handlerCreateBTB()"><i class="fa fa-plus"></i> <span style="color:white;">Buat BTB</span></button> -->
							<?php } ?>
						<?php } ?>
					</div>
					<div class="clearfix"></div>
				</div>
				<table class="table table-striped" id="table-summary" style="width:100%;">
					<thead>
						<tr class="bg-primary">
							<th class="text-center" style="color:white;"><span>No</span></th>
							<th class="text-center" style="color:white;"><span>SKU Kode</span></th>
							<th class="text-center" style="color:white;"><span>SKU Nama</span></th>
							<th class="text-center" style="color:white;"><span>SKU Satuan</span></th>
							<th class="text-center" style="color:white;"><span>QTY Permintaan Canvas</span></th>
							<th class="text-center" style="color:white;"><span>QTY SO Canvas</span></th>
							<th class="text-center" style="color:white;"><span>QTY Kembali</span></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</section>