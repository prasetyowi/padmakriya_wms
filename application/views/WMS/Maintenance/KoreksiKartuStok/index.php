<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3><span name="CAPTION-KOREKSIKARTUSTOK">Koreksi Kartu Stok</span></h3>
			</div>
			<div class="title_right">
				<div class="pull-right">

					<button type="button" id="btn_proses" class="btn btn-success" onclick="getData()">
						<i class=" fa fa-search"></i> <span name="CAPTION-CARI">Cari</span>
					</button>
					<button type="button" id="btn_proses" class="btn btn-primary" onclick="proses()">
						<i class=" fa fa-check"></i> <span name="CAPTION-PROSES">Proses</span>
					</button>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_content table-responsive">
						<table id="table_data" width="100%" class="table table-hover table-bordered dataTable-sm-td">
							<thead>
								<tr class="bg-primary">

									<th class="text-center" style="color:white;"><span name="CAPTION-DEPODETAILNAMA">Depo Detail Nama</span></th>
									<th class="text-center" style="color:white;"><span name="CAPTION-PRINCIPLEKODE">Principle Kode</span></th>
									<th class="text-center" style="color:white;"><span name="CAPTION-SKUKODE">Sku Kode</span></th>
									<th class="text-center" style="color:white;"><span name="CAPTION-SKU">Sku</span></th>
									<th class="text-center" style="color:white;"><span name="CAPTION-EXP">Expired Date</span></th>
									<th class="text-center" style="color:white;"><span name="CAPTION-QTYSTOCK">Qty Stock</span></th>
									<th class="text-center" style="color:white;"><span name="CAPTION-QTYSTOCKCARD">Qty Stock Card</span></th>
									<th class="text-center" style="color:white;"><span name="CAPTION-SELISIH">Selisih</span></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>