<section>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="page-title">
					<?php $this->load->view("WMS/PersetujuanHasilStockOpname/Component/Button/btnAction") ?>
				</div>
				<div class="clearfix"></div>
				<div class="x_title">
					<div class="row">
						<div class="container form-horizontal form-label-left" style="margin-left: 3px;margin-right: 3px;">

							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3" name="CAPTION-AREA">Area</label>
									<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 nopadding">
										<select class="form-control select2" id="areaSelected" onchange="handlerGetKodeDokumenArr(this.value)">
											<option value="">--Pilih Area--</option>
											<?php foreach ($depoDetail as $key => $value) { ?>
												<option value="<?= $value->id ?>"><?= $value->nama ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3" name="CAPTION-KODEDOKUMEN">Kode Dokumen</label>
									<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 nopadding">
										<select name="kode_dokumen_compare" id="kode_dokumen_compare" multiple class="select22"></select>
									</div>
								</div>

							</div>
							<div class="col-md-2">
								<button class="btn btn-info btn-md" onclick="handlerCompare()">Compare</button>
							</div>
						</div>
					</div>
				</div>
				<div class="container mt-2">
					<div class="container" style="margin-left: 3px;margin-right: 3px;">
						<input type="hidden" id="lengtDataCompare" value="0" />
						<div id="init-data-compare-list"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>