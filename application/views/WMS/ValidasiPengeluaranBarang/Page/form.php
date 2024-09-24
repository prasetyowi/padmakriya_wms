<?php $this->load->view("WMS/ValidasiPengeluaranBarang/Component/Script/Style/index") ?>

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 id="title-menu"></h3>
			</div>
			<div style="float: right">
				<button type="button" class="btn btn-success btn-sm" id="btnCompletedProsesValidasi" onclick="handlerCompletedDataProsesValidasi()"><i class="fas fa-check"></i> <span name="CAPTION-KONFIRMASIGENERATESELESAI">Konfirmasi Generate Selesai</span></button>
				<button type="button" class="btn btn-success btn-sm" id="btnConfirmProsesValidasi" onclick="handlerKonfimasiDataProsesValidasi()"><i class="fas fa-check"></i> <span name="CAPTION-KONFIRMASI">Konfirmasi</span></button>
				<button type="button" class="btn btn-primary btn-sm" id="btnSaveProsesValidasi" onclick="handlerSaveDataProsesValidasi()"><i class="fas fa-save"></i> <span name="CAPTION-SIMPAN">Simpan</span></button>
				<button type="button" class="btn btn-primary btn-sm" id="btnCetakProsesValidasi" onclick="handlerCetakDataProsesValidasi()"><i class="fas fa-print"></i> <span name="CAPTION-PRINT">Cetak</span></button>
				<button type="button" class="btn btn-dark btn-sm" id="btnBackProsesValidasi" onclick="location.href = '<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/ValidasiPengeluaranBarangMenu') ?>'"><i class="fas fa-arrow-left"></i> <span name="CAPTION-BACK">Kembali</span></button>

				<!-- <button type="button" class="btn btn-primary btn-sm" onclick="handlerTestGeneratePickingList('C68B7609-5630-47E0-831F-039C1D21EEB2')"><i class="fas fa-save"></i> <span>Test Generate Picking</span></button> -->
			</div>
		</div>

		<div class="clearfix"></div>
		<div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<!-- <h3><strong>Filter Data</strong></h3> -->
						<div class="clearfix"></div>
					</div>
					<div class="container mt-2">
						<div class="row">
							<div class="col-md-xl-4 col-lg-4 col-md-4 col-sm-12 col-sm-12">
								<div class="form-group">
									<label name="CAPTION-NOSURATTUGASPENGIRIMAN">No. Surat Tugas Pengiriman</label>
									<input type="hidden" name="lastUpdated" id="lastUpdated">
									<select class="form-control select2" name="noFDJR" id="noFDJR" required onchange="handlerGetDataByFDJR(this.value)">
										<option value="">--Pilih No. Surat Tugas Pengiriman--</option>
										<?php foreach ($dataFDJR as $key => $value) { ?>
											<option value="<?= $value->id ?>"><?= $value->kode ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-xl-2 col-lg-2 col-md-2 col-sm-12 col-sm-12">
								<div class="form-group">
									<label name="CAPTION-NOPPB">No. PPB</label>
									<select class="form-control select2" name="noPPB" id="noPPB" required onchange="handlerGetDataProsesValidasi(this.value)">
										<option value="">--Pilih No. PPB--</option>
									</select>
								</div>
							</div>
							<div class="col-md-xl-2 col-lg-2 col-md-2 col-sm-12 col-sm-12">
								<div class="form-group">
									<label name="CAPTION-DRIVER">Driver</label>
									<input type="hidden" name="pickingOrderId" id="pickingOrderId">
									<input type="hidden" name="serahTerimaKirimId" id="serahTerimaKirimId">
									<input type="text" class="form-control" name="driver" id="driver" placeholder="Driver" disabled />
								</div>
							</div>
							<div class="col-md-xl-2 col-lg-2 col-md-2 col-sm-12 col-sm-12">
								<div class="form-group">
									<label name="CAPTION-TANGGALPENGIRIMAN">Tanggal pengiriman</label>
									<input type="date" class="form-control" name="tglPengiriman" id="tglPengiriman" placeholder="Tgl Pengiriman" disabled />
								</div>
							</div>

							<div class="col-md-xl-2 col-lg-2 col-md-2 col-sm-12 col-sm-12">
								<div class="form-group">
									<label name="CAPTION-NOSERAHTERIMA">No. Serah terima</label>
									<input type="text" class="form-control" name="noSerahTerima" id="noSerahTerima" placeholder="No. Serah Terima" disabled />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-xl-4 col-lg-4 col-md-4 col-sm-12 col-sm-12">
								<div class="form-group">
									<label name="CAPTION-KETERANGAN">Keterangan</label>
									<textarea name="txtketerangan" id="txtketerangan" cols="3" rows="3" class="form-control keterangan" placeholder="keterangan"></textarea>
								</div>
							</div>

							<div class="col-md-xl-4 col-lg-4 col-md-4 col-sm-12 col-sm-12">
								<div class="form-group">
									<label name="CAPTION-STATUS">Status</label>
									<input type="text" id="txtstatus" class="form-control" name="txtstatus" value="Draft" readonly />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="container mt-2">
						<fieldset>
							<legend> <label name="CAPTION-DETAILBARANG">Detail Barang</label></legend>
							<div class="table-responsive">
								<table class="table table-striped" id="initDataDetailbarang">
									<thead>
										<tr class="text-center bg-primary text-white">
											<td><strong> <label name="CAPTION-NO">No.</label></strong></td>
											<td><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
											<td><strong><label name="CAPTION-KODESKU">Kode SKU</label></strong></td>
											<td><strong><label name="CAPTION-NAMASKU">Nama SKU</label></strong></td>
											<td><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
											<td><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
											<?php if (!isset($_GET['mode'])) { ?>
												<td><strong><label name="CAPTION-VALIDASI">Validasi</label></strong></td>
											<?php } ?>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</fieldset>

						<fieldset style="margin-top: 10px;">
							<legend> <label name="CAPTION-KOREKSIDO">Koreksi DO</label></legend>
							<div class="table-responsive">
								<table class="table table-striped" id="initDataKoreksiDO">
									<thead>
										<tr class="text-center bg-primary text-white">
											<td><input type="checkbox" name="select-do" id="select-do" value="1"></td>
											<td><strong><label name="CAPTION-PRIORITAS">Prioritas</label></strong></td>
											<td><strong><label name="CAPTION-NODO">No. DO</label> </strong></td>
											<td><strong><label name="CAPTION-NAMAOUTLET"></label> Nama Outlet</strong></td>
											<td><strong><label name="CAPTION-ALAMATOUTLET">Alamat Outlet</label> </strong></td>
											<td><strong><label name="CAPTION-SEGMENTLEVEL1">Segment 1</label> </strong></td>
											<td><strong><label name="CAPTION-SEGMENTLEVEL2">Segment 2</label> </strong></td>
											<td><strong><label name="CAPTION-SEGMENTLEVEL3">Segment 3</label> </strong></td>
											<td><strong>Status</strong></td>
											<!-- <?php if (!isset($_GET['mode'])) { ?>

                      <?php } ?> -->
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</fieldset>

						<fieldset style="margin-top: 10px;display:none" id="showDataSKUKembaliKeLokasi">
							<legend><label name="CAPTION-SUMMARYSKUKEMBALILOKASI">Summary SKU Yang Harus dikembalikan ke Lokasi</label> </legend>
							<div class="table-responsive">
								<table class="table table-striped" id="initDataSKUKembaliKeLokasi">
									<thead>
										<tr class="text-center bg-primary text-white">
											<td><strong><label name="CAPTION-KODESKU">Kode SKU</label> </strong></td>
											<td><strong><label name="CAPTION-NAMASKU">Nama SKU</label> </strong></td>
											<td><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
											<td><strong><label name="CAPTION-SATUAN">Satuan</label> </strong></td>
											<td><strong><label name="CAPTION-JUMLAH">Jumlah</label></strong></td>
											<td><strong>Status</strong></td>
											<!-- <td><strong>Type</strong></td> -->
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<button class="btn btn-info btn-sm generateKembaliLokasi" style="float: right;display:none" onclick="handlerGenerateKembaliLokasi()">Generate Assignment</button>
						</fieldset>

						<fieldset style="margin-top: 10px;display:none" id="showDataSKUYangKurang">
							<legend><label name="CAPTION-SUMMARYSKUKURANG">Summary SKU Yang Kurang</label> </legend>
							<div class="table-responsive">
								<table class="table table-striped" id="initDataSKUYangKurang">
									<thead>
										<tr class="text-center bg-primary text-white">
											<td><strong><label name="CAPTION-KODESKU">Kode SKU</label> </strong></td>
											<td><strong><label name="CAPTION-NAMASKU">Nama SKU</label> </strong></td>
											<td><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
											<td><strong><label name="CAPTION-SATUAN">Satuan</label> </strong></td>
											<td><strong><label name="CAPTION-JUMLAH">Jumlah</label></strong></td>
											<td><strong>Status</strong></td>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<button class="btn btn-info btn-sm btnGeneratePickingList" style="float: right;display:none" onclick="handlergeneratePickingList()"><label name="CAPTION-GENERATESKUKURANG">Generate Picking List Untuk SKU yang Kurang</label> </button>
						</fieldset>

						<fieldset style="margin-top: 10px;display:none" id="showDataSKUSalahInput">
							<legend><label name="CAPTION-SUMMARYSKUSALAHJUMLAHAMBIL">Summary SKU Salah Input Jumlah Ambil</label> </legend>
							<div class="table-responsive">
								<table class="table table-striped" id="initDataSKUSalahInput">
									<thead>
										<tr class="text-center bg-primary text-white">
											<td><strong><label name="CAPTION-KODESKU">Kode SKU</label> </strong></td>
											<td><strong><label name="CAPTION-NAMASKU">Nama SKU</label> </strong></td>
											<td><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
											<td><strong><label name="CAPTION-SATUAN">Satuan</label> </strong></td>
											<td><strong><label name="CAPTION-JUMLAH">Jumlah</label></strong></td>
											<td><strong>Status</strong></td>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<button class="btn btn-info btn-sm btnGenerateKoreksiBKB" style="float: right;display:none" onclick="handlergenerateKoreksiBKB()"><label name="CAPTION-GENERATESKUSALAHJUMLAHAMBIL">Generate Dokumen Koreksi BKB</label> </button>
						</fieldset>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<?php $this->load->view("WMS/ValidasiPengeluaranBarang/Component/Modal/prosesValidasi") ?>


<?php $this->load->view("WMS/ValidasiPengeluaranBarang/Component/Modal/dropKoreksiDO") ?>

<!-- /page content -->