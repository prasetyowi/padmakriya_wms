<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<!-- <h3><strong>Filter Data</strong></h3> -->
				<div class="clearfix"></div>
			</div>
			<div class="container mt-2">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-NODOKUMENPOREC">No. Dokumen PO Rec</label>
									<input type="text" class="form-control" name="doc_po_konfirmasi" id="doc_po_konfirmasi" required readonly>
									<input type="hidden" class="form-control" name="idPenerimaanBarang" id="idPenerimaanBarang" required readonly>
									<input type="hidden" class="form-control" name="lastUpdated" id="lastUpdated" required readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TANGGAL">Tanggal</label>
									<input type="date" class="form-control" name="tgl_konfirmasi" id="tgl_konfirmasi" readonly>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-7">
								<div class="form-group">
									<label name="CAPTION-JASAPENGANGKUT">Jasa Pengangkut</label>
									<select class="form-control select2" name="expedisi_konfirmasi" id="expedisi_konfirmasi" required onchange="handleUpdateService(this.value)"></select>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label name="CAPTION-NOKENDARAAN">No. Kendaraan</label>
									<input type="text" class="form-control" name="no_kendaraan_konfirmasi" id="no_kendaraan_konfirmasi" placeholder="No kendaraan" required onchange="handleUpdateVehicle(this.value)" />
								</div>
							</div>
						</div>

					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-NAMAPENGEMUDI">Nama Pengemudi</label>
							<input type="text" class="form-control" name="nama_pengemudi_konfirmasi" id="nama_pengemudi_konfirmasi" placeholder="Nama Pengemudi" required onchange="handleUpdateDriver(this.value)" />
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
									<input type="text" class="form-control" name="gudang_penerima_konfirmasi" id="gudang_penerima_konfirmasi" required readonly />
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan</label>
							<textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan_konfirmasi" id="keterangan_konfirmasi" readonly></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>