<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-LAPORANPOSISISTOCKBARANGBYSEGMENT">Menu Laporan Posisi Stock Barang By Segment</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
							<div class="col-md-8 col-sm-8">
								<select class="form-control select2" id="filter_stock_principle" name="filter_stock_principle" style="width:100%">
									<option value=""><label name="CAPTION-ALL">All</label></option>
									<?php foreach ($Principle as $row) { ?>
										<option value="<?= $row['principle_id']; ?>"><?= $row['principle_kode']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-SEGMENT">Segment</label>
							<div class="col-md-8 col-sm-8">
								<select class="form-control select2" id="filter_segment" name="filter_segment" style="width:100%">
									<?php
									if (COUNT($Segment) > 0) :
										foreach ($Segment as $row) : ?>
											<option value="<?= $row['client_pt_segmen_id']; ?>"><?= $row['client_pt_segmen_nama']; ?></option>
									<?php endforeach;
									endif; ?>
								</select>
							</div>
						</div>
						<div class="form-group" style="float: right; margin-right: 15px">
							<label class="col-form-label col-md-4 col-sm-4 label-align"></label>
							<div class="col-md-8 col-sm-8">
								<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
									<label name="CAPTION-LOADING">Loading</label>...</span>
								<button type="button" id="btn_stock_pencarian" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default panel-stock-rekap" style="display:none;">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<table id="table_stock_rekap" width="100%" class="table table-striped text-center">
						<thead>
							<tr style="background:#F0EBE3;">
								<th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
								<th class="text-center" name="CAPTION-SKUKONVERSIGRUP">SKU Konversi Grup</th>
								<th class="text-center" name="CAPTION-SKU">SKU</th>
								<th class="text-center" name="CAPTION-COMPOSITE">Composite</th>
								<th class="text-center" name="CAPTION-QTY">QTY</th>
								<th class="text-center" name="CAPTION-QTYINPCS">QTY In Pcs</th>
								<th class="text-center" name="CAPTION-SEGMENT">Segment</th>
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