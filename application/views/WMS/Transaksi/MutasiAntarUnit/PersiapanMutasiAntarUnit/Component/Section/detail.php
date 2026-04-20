<section>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h4 class="pull-left">
						<?php if ($this->input->get('mode') !== 'view') { ?>
							<!-- <button class="btn btn-primary btn-sm" onclick="handlerAddMutasiAntarDepo()">Tambah</button> -->
						<?php } ?>
					</h4>
					<div class="clearfix"></div>
				</div>
				<div style="padding: 10px">
					<h4>Mutasi Antar Depo</h4>
					<div id="initMutasiAntarDepo" style="padding: 7px;max-height:35vh; overflow-y:scroll">
						<div class="childMutasiAntarDepo" style="display: flex;align-items:center; padding: 7px; border:1px solid grey; border-radius: 5px; margin-bottom: 7px">
							<div style="width: 5%;">
								<h4 class="text-center numberChild">1</h4>
							</div>
							<div style="width: 85%;">
								<div class="row">
									<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
										<div class="form-group">
											<label>Depo Asal</label>
											<select class="form-control select2" name="depoAsal" id="depoAsal1" required disabled>
												<option value="">--Pilih Depo Asal--</option>
												<?php if ($pages === 'form') { ?>
													<?php foreach ($depos['depoWithoutItSelf'] as $depo) : ?>
														<option value="<?= $depo->depo_id ?>" <?= $this->session->userdata('depo_id') === $depo->depo_id ? 'selected' : '' ?>><?= $depo->depo_nama ?></option>
													<?php endforeach; ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
										<div class="form-group">
											<label>Gudang Asal</label>
											<select class="form-control select2" name="gudangAsal" id="gudangAsal1" data-counter="1" onchange="handlerChangeDataGudangAsalMutasiAntarDepo(event)" required>
												<option value="">--Pilih Gudang Asal--</option>
												<?php if ($pages === 'form') { ?>
													<?php foreach ($warehouses as $warehouse) : ?>
														<option value="<?= $warehouse->depo_detail_id ?>"><?= $warehouse->depo_detail_nama ?></option>
													<?php endforeach; ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
										<div class="form-group">
											<label>Depo Tujuan</label>
											<select class="form-control select2" name="depoTujuan" id="depoTujuan1" data-counter="1" onchange="handlerChangeDataDepoTujuanMutasiAntarDepo(event)" required>
												<option value="">--Pilih Depo Tujuan--</option>
												<?php if ($pages === 'form') { ?>
													<?php foreach ($depos['depoWithItSelft'] as $depo) : ?>
														<option value="<?= $depo->depo_id ?>"><?= $depo->depo_nama ?></option>
													<?php endforeach; ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
										<?php if ($this->input->get('mode') !== 'view') { ?>
											<button class="btn btn-info btn-sm addSKUChild" data-counter="1" onclick="handlerAddDetailSKUMutasiAntarDepo(event, 'add')" style="margin-top: 23px;">Tambah SKU</button>
										<?php } else { ?>
											<button class="btn btn-success btn-sm cetakSKUChild" data-counter="1" onclick="handlerAddDetailSKUMutasiAntarDepo(event, 'cetak')" style="margin-top: 23px;">Cetak</button>

											<button class="btn btn-info btn-sm addSKUChild" data-counter="1" onclick="handlerAddDetailSKUMutasiAntarDepo(event, 'view')" style="margin-top: 23px;">View SKU</button>
										<?php } ?>
									</div>

								</div>
							</div>
							<?php if ($this->input->get('mode') !== 'view') { ?>
								<!-- <div style="width: 10%;" class="text-center">
									<i class="fas fa-trash text-danger deleteRowChildMutasiAntarDepo" data-counter="1" style="cursor: pointer;font-size: 2em"></i>
								</div> -->
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="showDetailSKUMutasiAntarDepo" style="display: none;">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h4 class="pull-left titleDetailMutasiAntarDepo"></h4>
					<div class="clearfix"></div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped" width="100%" id="initDetailSKUMutasiAntarDepo">
						<thead>
							<tr class="text-center text-bold">
								<td>#</td>
								<td>SKU Kode</td>
								<td>SKU Nama Produk</td>
								<td>SKU Satuan</td>
								<td>QTY</td>
								<td>ED Produk</td>
								<?php if ($this->input->get('mode') !== 'view') { ?>
									<td>Action</td>
								<?php } ?>

							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>