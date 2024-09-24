<!-- Modal untuk menampilkan form tambah tutup periode !-->
<div class="modal fade" id="previewaddformbkbcanvas" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width: 90%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<!-- <h4 class="modal-title"><label name="CAPTION-FORMBUATBKBCANVAS">Buat Bkb Canvas</label></h4> -->
				<div class="col-lg-12 col-sm-12 martop">
					<div style="float:left">
						<h4 class="modal-title"><label name="CAPTION-FORMBUATBKBCANVAS">Buat Bkb Canvas</label></h4>
					</div>
					<div style="float: right; padding: 5px; background-color: white; border-radius: 10px; color: black; display: flex; justify-content: space-between; align-items: center;">
						<div class="row" style="margin-left: 5px;margin-right: 0px;margin-top:5px">

							<span id="loadingaddbkbcanvas" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
							<div style="display: flex; align-items: center;">
								<button type="button" class="btn btn-success" id="btnsaveaddbkbcanvas"><label name="CAPTION-SIMPAN">Simpan</label></button>
								<div style="margin-left:8px;margin-right:8px;border:0px solid black;"></div>
								<button type="button" class="btn btn-danger" id="btnbackaddbkbcanvas"><label name="CAPTION-KEMBALI">Kembali</label></button>
								<!-- <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeHandlerLocationRak()"><label name="CAPTION-KEMBALI"></label></button> -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-xs-3">
						<label name="CAPTION-CHECKER">Checker</label>
						<select id="slcaddcheckerbkbcanvas" class="form-control" style="width: 100%;"></select>
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-PRINCIPLE">Principle</label>
						<select name="principleFilterCanvas" id="principleFilterCanvas" class="form-control select2 principleFilter"></select>
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-NOBKB">No BKB</label>
						<input type="text" id="txtaddnobkbcanvas" class="form-control" value="" readonly />
						<input type="hidden" id="txtaddidbkbcanvas" class="form-control" value="" readonly />
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-NOPPB">No Ppb</label>
						<input type="text" id="txtaddnoppbcanvas" class="form-control" value="" readonly />
						<input type="hidden" id="txtpickingorderid" class="form-control" value="" readonly />
					</div>
				</div>
				<div class="row" style="margin-top: 5px;">
					<div class="col-xs-3">

						<input type="checkbox" id="slcremainderchkcanvas" name="slcremainderchkcanvas" checked style="transform: scale(1.5);margin-right:10px" onchange="handlerCheckedRemainderTakeCanvas(event)"> <span name="CAPTION-CHECKIFREMAINDER" style="display: inline-block;font-size:1.5rem">Jika sisa ambil tidak sama 0</span>

					</div>
				</div>
				<div class="row">
					<table id="tableaddbkbcanvas" width="100%" class="table table-striped">
						<thead>
							<tr>
								<td><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
								<td><strong><label name="CAPTION-BRAND">Brand</label></strong></td>
								<td><strong><label name="CAPTION-SKUKODE">Sku Kode</label></strong></td>
								<td><strong><label name="CAPTION-NAMABARANG">Sku</label></strong></td>
								<td><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
								<td><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
								<td><strong><label name="CAPTION-RENCANAQTYAMBIL">Qty Ambil</label></strong></td>
								<td><strong><label name="CAPTION-SISAAMBIL">Sisa Ambil</label></strong></td>
								<td><strong><label name="CAPTION-AKTUALQTYAMBIL">Aktual Qty Ambil</label></strong></td>
								<td><strong><label name="CAPTION-TGLEXP">Tgl Exp</label></strong></td>
								<td><strong><label>Pilih Manual</label></strong></td>
								<td width="10%"><strong><label name="CAPTION-LOKASI">Lokasi</label></strong></td>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <span id="loadingaddbkbcanvas" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
			<button type="button" class="btn btn-success" id="btnsaveaddbkbcanvas"><label name="CAPTION-SIMPAN">Simpan</label></button>
			<button type="button" class="btn btn-danger" id="btnbackaddbkbcanvas"><label name="CAPTION-KEMBALI">Kembali</label></button> -->
			</div>
		</div>
	</div>
</div>