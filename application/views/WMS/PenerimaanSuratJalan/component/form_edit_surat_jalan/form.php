<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="container mt-2">
				<div class="row">
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-NOSURATJALAN">No. Surat Jalan</label>
									<input type="hidden" name="id" id="id" value="<?= $id ?>">
									<input type="hidden" name="principle_id_edit" id="principle_id_edit" value="<?= $data->principle_id ?>">
									<input type="hidden" name="tipe_penerimaan_id_edit" id="tipe_penerimaan_id_edit" data-tipe="<?= implode(",", $data_detail) ?>">
									<input type="hidden" name="count_error" id="count_error_edit" class="form-control">
									<input type="hidden" name="lastUpdated" id="lastUpdated" class="form-control" value="<?= $data->penerimaan_surat_jalan_tgl_update ?>">
									<input type="text" class="form-control" name="doc_batch_edit" id="doc_batch_edit" placeholder="auto generate" required readonly />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TANGGAL">Tanggal</label>
									<input type="date" class="form-control input-date-start" name="tgl_edit" id="tgl_edit" placeholder="dd-mm-yyyy" value="<?php echo Date('Y-m-d') ?>">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label name="CAPTION-PERUSAHAAN">Perusahaan</label>
							<select class="form-control select2" name="perusahaan_edit" id="perusahaan_edit" required></select>
						</div>
						<div class="form-group">
							<label name="CAPTION-PENYALURATAUPRINCIPLE">Penyalur / Principle</label>
							<select class="form-control select2" name="principle_edit" id="principle_edit" required></select>
						</div>
						<input type="text" class="form-control" name="sub_principle_edit" id="sub_principle_edit" placeholder="Nama Principle" required readonly />
					</div>
					<div class="col-md-4">

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TIPEPENERIMAAN">Tipe Penerimaan</label>
									<select class="form-control select2 tipe_penerimaan_edit" name="tipe_penerimaan_edit" id="tipe_penerimaan_edit" required></select>
								</div>
								<!-- <div class="form-group">
                                    <label>Tempo Pembayaran</label>
                                    <input type="text" class="form-control" name="tempo_pembayaran_edit" id="tempo_pembayaran_edit" placeholder="30 Hari" required readonly />
                                </div> -->
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TIPEPENERIMAAN">Status</label>
									<select class="form-control select2" name="status_edit" id="status_edit" required disabled></select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label name="CAPTION-FILEATTACHMENT">File Attachment</label>&nbsp;<small class="text-danger" name="CAPTION-ABAIKANJIKATIDAKMERUBAHFILE">* abaikan jika tidak merubah file</small>
							<input type="file" class="form-control upload up" name="file_edit" id="file_edit" placeholder="upload attachment" onchange="previewFileEdit()" required accept="image/jpeg, image/jpg, image/png, image/gif, image/JPG, image/JPEG, image/GIF, application/pdf,.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />

							<div class="row" id="show-file-edit" style="margin-top: 5px;"></div>
						</div>

						<div class="form-group">
							<label name="CAPTION-NOSURATJALANEKSTERNAL">No. Surat Jalan Eksternal</label>

							<div class="row">
								<div class="col-md-7">
									<input type="text" class="form-control" name="no_surat_jalan_edit" id="no_surat_jalan_edit" placeholder="No. Surat Jalan Eksternal" required />
								</div>
								<div class="col-md-2">
									<input type="text" class="form-control text-center" value="-" readonly>
								</div>
								<div class="col-md-3">
									<input type="text" class="form-control" name="no_surat_jalan_counter_edit" id="no_surat_jalan_counter_edit" readonly required />
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan</label>
							<textarea name="keterangan_edit" id="keterangan_edit" cols="30" rows="4" class="form-control keterangan" placeholder="keterangan" style="width: 100%"></textarea>
						</div>
						<div class="form-group">
							<label name="CAPTION-NOKENDARAAN">No. Kendaraan</label>
							<input type="text" class="form-control" name="no_kendaraan_edit" id="no_kendaraan_edit" placeholder="No. Kendaraan" required readonly />
						</div>
					</div>
					<div style="float:right">
						<button type="button" class="btn btn-primary" id="pilih-sku-edit"><i class="fas fa-box"></i> <label name="CAPTION-PILIHSKU">Pilih SKU</label></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>