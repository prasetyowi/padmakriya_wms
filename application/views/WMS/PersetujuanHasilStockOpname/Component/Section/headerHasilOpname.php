<section>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="page-title">
					<?php $this->load->view("WMS/PersetujuanHasilStockOpname/Component/Button/btnAction") ?>
				</div>
				<div class="clearfix"></div>
				<div class="container mt-2">
					<div class="row">
						<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
							<div class="form-group">
								<label name="CAPTION-KODEDOKUMEN">Kode Dokumen</label>
								<select name="kode_dokumen" id="kode_dokumen" class="form-control select2" onchange="handlerGetDataOpnameByKode(this.value)"></select>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label name="CAPTION-PENANGGUNGJAWAB ">Penanggung Jawab</label>
										<input type="text" class="form-control" name="penanggung_jawab" id="penanggung_jawab" placeholder="Penanggung Jawab" disabled>
										<input type="hidden" class="form-control" name="lastUpdate" id="lastUpdate" value="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label name="CAPTION-TANGGAL">Tanggal</label>
										<input type="text" class="form-control" name="tgl" id="tgl" placeholder="dd-mm-yyyy" disabled>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label name="CAPTION-PT">Perusahaan</label>
										<input type="text" class="form-control" name="perusahaan" id="perusahaan" placeholder="Perusahaan" disabled>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label name="CAPTION-PRINCIPLE">Principle</label>
										<input type="text" class="form-control" name="principle" id="principle" placeholder="Principle" disabled>
									</div>
								</div>
							</div>

						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label name="CAPTION-JENISSTOCK">Jenis Stock</label>
										<input type="text" class="form-control" name="jenis_stock" id="jenis_stock" placeholder="Jenis Stock" disabled>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label name="CAPTION-TIPESTOCKOPNAME">Tipe Stock Opname</label>
										<input type="text" class="form-control" name="tipe_stock_opname" id="tipe_stock_opname" placeholder="Tipe Stock Opname" disabled>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label name="CAPTION-009001000">Unit Cabang</label>
										<input type="text" class="form-control" name="unit_cabang" id="unit_cabang" placeholder="Unit Cabang" disabled>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label name="CAPTION-AREAOPNAME">Area Opname</label>
										<input type="text" class="form-control" name="area_opname" id="area_opname" placeholder="Area Opname" disabled>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label name="CAPTION-STATUS">Status</label>
								<input type="text" class="form-control" name="status" id="status" placeholder="Status" disabled>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
							<div class="form-group">
								<label name="CAPTION-KETERANGAN">Keterangan</label>
								<textarea cols="10" style="width: 100%;height: 120px" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>