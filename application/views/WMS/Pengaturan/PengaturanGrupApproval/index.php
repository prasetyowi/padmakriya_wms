<style>
	.modal-body {
		max-height: calc(100vh - 210px);
		overflow-x: auto;
		overflow-y: auto;
	}

	.invalid-feedback {
		color: red;
	}
</style>

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-PENGATURANGRUPAPPROVAL">Pengaturan Grup Approval</h3>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">

				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<!-- <h5 name="CAPTION-">Grup Approval</h5> -->
								<button type="button" id="addGrup" class="btn btn-success" onclick="addGrup()">
									<i class="fa fa-plus"></i> <label name="CAPTION-TAMBAH"></label>
								</button>
								<div class="clearfix"></div>
							</div>

							<div class="x_content">
								<div class="row">
									<!--div class="x_content" style="overflow-x:auto"-->
									<table id="tableListGrup" width="100%" class="table table-striped tableReferensi">
										<thead>
											<tr>
												<th name="CAPTION-NO" class="text-center">No</th>
												<th name="CAPTION-NAMAGRUP" class="text-center">Nama Grup</th>
												<th name="CAPTION-KETERANGAN" class="text-center">Keterangan</th>
												<th name="CAPTION-AKSI" class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody class="text-center">
											<?php if ($datas) { ?>
												<?php foreach ($datas as $key => $value) { ?>
													<tr>
														<td><?= $key + 1 ?></td>
														<td><?= $value->approval_group_nama ?></td>
														<td><?= $value->approval_group_keterangan ?></td>
														<td>
															<button type="button" id="btnaddlevel-<?= $key + 1 ?>" class="btn btn-success" onclick="openGrup('<?= $value->approval_group_id ?>', '<?= $key + 1 ?>')">
																<i class="fa fa-plus"></i>
															</button>
														</td>
													</tr>
												<?php } ?>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /page content -->

		<div class="modal fade" id="modalGrup" class="modalGrup" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
						<h4 class="modal-title"><label name="CAPTION-">Tambah Grup</label>
						</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title">
										<h5>Tambah Grup Baru</h5>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="nama_grup" name="CAPTION-NAMAGRUP">Nama Grup</label>
													<input type="text" class="form-control" id="nama_grup" placeholder="Masukkan nama grup...">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<label for="keterangan_grup" name="CAPTION-KETERANGAN">Keterangan Grup</label>
													<textarea class="form-control" id="keterangan_grup" placeholder="Masukkan keterangan grup..."></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<span id="loadingdelete" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
							Loading...</span>
						<button type="button" class="btn btn-success" id="saveGrup"><label name="CAPTION-SIMPAN">Simpan</label></button>
						<button type="button" class="btn btn-danger" data-dismiss="modal"><label name="CAPTION-KEMBALI">Kembali</label></button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modalGrupDetail" class="modalGrupDetail" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
						<h4 class="modal-title"><label name="CAPTION-">Tambah Grup</label>
						</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title">
										<button type="button" id="addDetail" class="btn btn-success" onclick="addDetail()">
											<i class="fa fa-plus"></i> <label name="CAPTION-TAMBAH"></label>
										</button>
										<div class="clearfix"></div>
									</div>
									<input type="hidden" id="approval_group_id">
									<div class="x_content">
										<div class="row">
											<table id="tableGrupDetail" class="table table-striped tableGrupDetail">
												<thead>
													<tr>
														<th name="" class="text-center">No</th>
														<th name="CAPTION-KARYAWAN" class="text-center">Karyawan</th>
														<th name="CAPTION-DIVISI" class="text-center">Divisi</th>
														<th name="CAPTION-LEVEL" class="text-center">Level</th>
														<th name="CAPTION-URUTAN" class="text-center">Tingkatan</th>
														<th name="CAPTION-AKSI" class="text-center">Aksi</th>
													</tr>
												</thead>
												<tbody class="text-center">

												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<span id="loadingdelete" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
							Loading...</span>
						<button type="button" class="btn btn-success" id="saveDetail"><label name="CAPTION-SIMPAN">Simpan</label></button>
						<button type="button" class="btn btn-danger" data-dismiss="modal"><label name="CAPTION-KEMBALI">Kembali</label></button>
					</div>
				</div>
			</div>
		</div>