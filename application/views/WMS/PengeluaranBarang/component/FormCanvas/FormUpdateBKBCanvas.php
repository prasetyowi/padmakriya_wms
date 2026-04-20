<!-- Modal untuk menampilkan form tambah tutup periode !-->
<div class="modal fade" id="previewupdateformbkbcanvas" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width: 90%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">

				<div class="col-lg-12 col-sm-12 martop">
					<div style="float:left">
						<h4 class="modal-title"><label name="CAPTION-FORMBUATBKBCANVAS">Update Bkb Canvas</label></h4>
					</div>
					<div style="float: right; padding: 5px; background-color: white; border-radius: 10px; color: black; display: flex; justify-content: space-between; align-items: center;">
						<div class="row" style="margin-left: 5px;margin-right: 0px;margin-top:5px">

							<span id="loadingupdatebkbcanvas" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
							<div style="display: flex; align-items: center;">

								<!-- <div style="margin-left:8px;margin-right:8px;border:0px solid black;"></div> -->
								<button type="button" class="btn btn-danger" id="btnbackupdatebkbcanvas"><label name="CAPTION-KEMBALI">Kembali</label></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-xs-3">
						<label name="CAPTION-NOBKB">No BKB</label>

						<input type="text" id="txtupdatenobkbcanvas" class="form-control" value="" readonly />
						<input type="hidden" id="txtupdateidbkbcanvas" class="form-control" value="" readonly />

					</div>
					<div class="col-xs-3">
						<label name="CAPTION-NOPPB">No Ppb</label>


						<input type="text" id="txtupdatenoppbcanvas" class="form-control" value="" readonly />
						<input type="hidden" id="txtpickingorderid" class="form-control" value="" />

					</div>
					<div class="col-xs-3">
						<label name="CAPTION-CHECKER">Checker</label>

						<select id="slcupdatecheckerbkbcanvas" class="form-control" style="width: 100%;" readonly></select>

					</div>

				</div>
				<div class="row">
					<table id="tableupdatebkbcanvas" width="100%" class="table table-striped">
						<thead>
							<tr>
								<th name="CAPTION-PRINCIPLE">Principle</th>
								<th name="CAPTION-BRAND">Brand</th>
								<th name="CAPTION-SKUKODE">Sku Kode</th>
								<th name="CAPTION-NAMABARANG">Sku</th>
								<th name="CAPTION-KEMASAN">Kemasan</th>
								<th name="CAPTION-SATUAN">Satuan</th>
								<th name="CAPTION-RENCANAQTYAMBIL">Qty Ambil</th>
								<th name="CAPTION-SISAAMBIL">Sisa Ambil</th>
								<th name="CAPTION-AKTUALQTYAMBIL">Aktual Qty Ambil</th>
								<th name="CAPTION-TGLEXP">Tgl Exp</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <span id="loadingupdatebkbcanvas" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span> -->
				<!-- <button type="button" class="btn btn-success" id="btnsaveupdatebkbcanvas">Simpan</button> -->
				<!-- <button type="button" class="btn btn-danger" id="btnbackupdatebkbcanvas"><label name="CAPTION-KEMBALI"></label></button> -->
			</div>
		</div>
	</div>
</div>