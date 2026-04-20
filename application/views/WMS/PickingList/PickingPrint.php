<style>
	#overlay {
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height: 100%;
		display: none;
		background: rgba(0, 0, 0, 0.6);
	}

	.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.spinner {
		width: 40px;
		height: 40px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
	}

	@keyframes sp-anime {
		100% {
			transform: rotate(360deg);
		}
	}

	.is-hide {
		display: none;
	}

	#tbody-listSKU tr,
	#tbody-listLokasiSKU tr {
		cursor: pointer;
	}
</style>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-CETAKPERMINTAANBARANG">Cetak Permintaan Barang</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">

		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h5 name="CAPTION-SEARCHFORPERMINTAANBARANG">Search For Permintaan Barang</h5>
					</div>
					<div class="x_content">
						<form id="form-filter-do" class="form-horizontal form-label-left">
							<div class="row">
								<div class="col-lg-4">
									<div class="item form-group">
										<label class="col-form-label col-md-6 col-sm-6 label-align" name="CAPTION-TANGGALPERMINTAANBARANG">Tanggal Permintaan Barang
										</label>
										<div class="col-md-6 col-sm-6">
											<input type="date" class="form-control input-date-start" name="tgl" id="tgl" placeholder="dd-mm-yyyy" required="required" value="<?php echo Date('Y-m-d') ?>">
										</div>

									</div>

									<div class="item form-group">
										<label class="col-form-label col-md-6 col-sm-6 label-align" name="CAPTION-NOPERMINTAANBARANG">No Permintaan Barang
										</label>
										<div class="col-md-6 col-sm-6">
											<input type="text" class="form-control" name="pick_id" id="pick_id">
											<!-- <select class="form-control select2"
                                                name="pick_id" id="pick_id" required>
												<option>-- PILIH NO PERMINTAAN BARANG --</option>
												<?php foreach ($listPicking as $item) : ?>
                                                	<option value="<?php echo $item['picking_list_id'] ?>">
                                                    <?php echo $item['picking_list_kode'] ?></option>
                                                <?php endforeach; ?>
                                                
                                            </select> -->
										</div>
									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-6 col-sm-6 label-align" name="CAPTION-TIPELAYANAN">Tipe Layanan
										</label>
										<div class="col-md-6 col-sm-6">
											<select class="form-control tipe_layanan select2" name="tipe_layanan" id="tipe_layanan" required>
												<option value="">-- PILIH TIPE LAYANAN --</option>
												<?php foreach ($listLayanan as $layanan) : ?>
													<option value="<?php echo $layanan['tipe_layanan_kode'] ?>">
														<?php echo $layanan['tipe_layanan_nama'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-6 col-sm-6 label-align" name="CAPTION-TIPEPENGIRIMAN">Tipe Pengiriman
										</label>
										<div class="col-md-6 col-sm-6">
											<select class="form-control select-tipe-pengiriman select2" name="tipe_pengiriman" id="tipe_pengiriman" required>
												<option value="">-- PILIH TIPE PENGIRIMAN --</option>
												<?php foreach ($listPengiriman as $pengirman) : ?>
													<option value="<?php echo $pengirman['tipe_pengiriman_id'] ?>">
														<?php echo $pengirman['tipe_pengiriman_nama_tipe'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-6 col-sm-6 label-align" for="is_packing" name="CAPTION-PERLUDIKEMAS">Perlu Dikemas

										</label>
										<div class="col-md- col-sm-6">
											<select class="form-control select2" name="is_packing" id="is_packing" required>
												<option value="">ALL
												</option>
												<option value="1">YES
												</option>
												<option value="0">NO
												</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-lg-5">
									<div class="item form-group">
										<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-TIPEPERMINTAANBARANG">Tipe Permintaan Barang
										</label>
										<div class="col-md- col-sm-6">
											<select class="form-control select2" name="tipe_delivery_order_id" id="tipe_delivery_order_id">
												<?php foreach ($listTipeDO as $tipe) : ?>
													<option value="<?php echo $tipe['tipe_delivery_order_id'] ?>">
														<?php echo $tipe['tipe_delivery_order_alias'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-2 col-sm-2 label-align" for="status">Status

										</label>
										<div class="col-md- col-sm-6">
											<select class="form-control select2" name="status" id="status" required>
												<option value="Open">Open
												</option>
												<option value="Close">Close
												</option>
											</select>
										</div>
									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-2 col-sm-2 label-align" for="area">Area

										</label>
										<div class="col-md- col-sm-6">
											<select class="form-control select2" name="area" id="area" required>
												<option value="">-- PILIH AREA --</option>
												<?php foreach ($listArea as $area) : ?>
													<option value="<?php echo $area['area_id'] ?>">
														<?php echo $area['area_nama'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>

							</div>

							<div class="item form-group">
								<div class="col-md-12 col-sm-12 text-left">
									<a class="btn btn-md btn-primary btn-submit-filter" onclick="getDataPickingListSearch()"><label name="CAPTION-CARI">Cari</label></a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row ro-batch" id="do-table">

			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h5 name="CAPTION-DAFTARPERMINTAANBARANG">Daftar Permintaan Barang</h5>
						<a class="btn btn-md btn-primary" href="<?php echo site_url('WMS/Distribusi/PermintaanBarang/PickingForm') ?>"><label name="CAPTION-PERMINTAANBARANGTAMBAH">Tambah Permintaan Barang</label> </a>
						<div class="clearfix"></div>
					</div>
					<div class="row">
						<table id="tablePickOrder" width="100%" class="table table-responsive">
							<thead>
								<tr>
									<th name="CAPTION-NO">No</th>
									<th name="CAPTION-TANGGAL">Tgl</th>
									<th name="CAPTION-NOPERMINTAANBARANG">No Permintaan Barang</th>
									<th>No FDJR</th>
									<th name="CAPTION-TIPE">Tipe</th>
									<th name="CAPTION-TIPELAYANAN">Tipe Layanan</th>
									<th name="CAPTION-TIPEPENGIRIMAN">Tipe Pengiriman</th>
									<th name="CAPTION-DIKEMAS">Dikemas</th>
									<th>Area</th>
									<th name="CAPTION-TANGGALCETAK">Tgl Cetak</th>
									<th>Action</th>
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
<form action="" id="form-do"></form>
<div class="modal fade bs-example-modal-md modal-assign-checker" tabindex="-1" role="dialog" aria-modal="true">
	<div class="modal-dialog modal-md">
		<div class="x_panel">
			<div class="x_title">
				<h5 name="CAPTION-PILIHPESANAN">Choose Pick Order</h5>
			</div>
			<div class="x_content">
				<form id="form-assign-checker" class="form-horizontal form-label-left">
					<div class="item form-group">
						<label class="col-form-label col-md-3 col-sm-3 label-align">Checker <span class="required">*</span>
						</label>
						<div class="col-md-6 col-sm-6">
							<select name="checker" class="form-control select-checker" id="select-checker">
								<option value="Cheker A">Checker A</option>
								<option value="Checker B">Checker B</option>
								<option value="Checker C">Checker C</option>
							</select>
						</div>
					</div>
					<div class="item form-group">
						<div class="text-left">
							<button class="btn btn-primary btn-assign-checker-submit">Assign</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="overlay">
	<div class="cv-spinner">
		<span class="spinner"></span>
	</div>
</div>

<!-- modal view detail-->
<div class="modal fade" id="viewDetail" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-secondary">
				<div class="row">

					<!-- <h5 class="modal-title"><label>Add SKU</label></h5> -->
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
			</div>
			<!-- <div class="container"></div> -->
			<div class="modal-body">
				<div class="table-responsive">

					<table id="tableDetail" style="width:100%" class="table table-hover  table-primary table-bordered ">
						<thead>
							<tr>
								<th style="text-align: center;" name="CAPTION-NOPERMINTAANBARANG">No Permintaan Barang</th>
								<th style="text-align: center;" name="CAPTION-NODO">No DO</th>
								<th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
								<th style="text-align: center;" name="CAPTION-SKUNAMA">Nama SKU</th>
								<th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
								<th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
								<th style="text-align: center;">QTY</th>
								<th style="text-align: center;">E.D</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="modal-footer">

			<a href="#" class="btn btn-danger" data-dismiss="modal" class="btn"><label name="CAPTION-CLOSE">Tutup</label></a>
		</div>
	</div>
</div>