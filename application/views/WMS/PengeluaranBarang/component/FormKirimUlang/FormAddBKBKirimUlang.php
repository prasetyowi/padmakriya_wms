<!-- Modal untuk menampilkan form tambah tutup periode !-->
<div class="modal fade" id="previewaddformbkbkirimulang" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width: 90%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="row">
						<div class="col-lg-12 col-sm-12 martop">
							<div style="float:left">
								<h4 class="modal-title"><label>Form Buat BKB Reschedule</label></h4>
							</div>
							<div style="float: right; padding: 5px; background-color: white; border-radius: 10px; color: black; display: flex; justify-content: space-between; align-items: center;">
								<div class="row" style="margin-left: 5px;margin-right: 0px;margin-top: 5px;">
									<span id="loadingaddbkbkirimulang" style="display: none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
									<div style="display: flex; align-items: center;">
										<button type="button" class="btn btn-success" id="btnsaveaddbkbkirimulang"><label name="CAPTION-SIMPAN"></label></button>
										<button type="button" class="btn btn-danger" id="btnbackaddbkbkirimulang"><label name="CAPTION-KEMBALI"></label></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-xs-3">
						<label name="CAPTION-CHECKER"></label>
						<select id="slcaddcheckerbkbkirimulang" class="form-control select2" style="width: 100%;"></select>
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-PRINCIPLE"></label>
						<select name="principleFilterKirimUlang" id="principleFilterKirimUlang" class="form-control select2 principleFilter"></select>
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-NOBKB"></label>
						<input type="text" id="txtaddnobkbkirimulang" class="form-control" value="" readonly />
						<input type="hidden" id="txtaddidbkbkirimulang" class="form-control" value="" readonly />
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-NOPPB"></label>
						<input type="text" id="txtaddnoppbkirimulang" class="form-control" value="" readonly />
						<input type="hidden" id="txtpickingorderid" class="form-control" value="" readonly />
					</div>

				</div>
				<div class="row" style="margin-top: 5px;">
					<div class="col-xs-3">
						<input type="checkbox" id="slcremainderchkkirimulang" name="slcremainderchkkirimulang" checked style="transform: scale(1.5);margin-right:10px" onchange="handlerCheckedRemainderTakeKirimUlang(event)"> <span name="CAPTION-CHECKIFREMAINDER" style="display: inline-block;font-size:1.5rem">Lihat yang belum diambil</span>
					</div>
				</div>
				<div class="row">
					<table id="tableaddbkbkirimulang" width="100%" class="table table-striped">
						<thead>
							<tr>
								<td><strong><label name="CAPTION-PRINCIPLE"></label></strong></td>
								<td><strong><label name="CAPTION-BRAND"></label></strong></td>
								<td><strong><label name="CAPTION-SKUKODE"></label></strong></td>
								<td><strong><label name="CAPTION-NAMABARANG"></label></strong></td>
								<td><strong><label name="CAPTION-KEMASAN"></label></strong></td>
								<td><strong><label name="CAPTION-SATUAN"></label></strong></td>
								<td><strong><label name="CAPTION-RENCANAQTYAMBIL"></label></strong></td>
								<td><strong><label name="CAPTION-SISAAMBIL"></label></strong></td>
								<td><strong><label name="CAPTION-AKTUALQTYAMBIL"></label></strong></td>
								<td><strong><label name="CAPTION-TGLEXP"></label></strong></td>
								<td width="10%"><strong><label name="CAPTION-LOKASI"></label></strong></td>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <span id="loadingaddbkbkirimulang" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span> -->
				<!-- <button type="button" class="btn btn-success" id="btnsaveaddbkbkirimulang"><label name="CAPTION-SIMPAN"></label></button> -->
				<!-- <button type="button" class="btn btn-danger" id="btnbackaddbkbkirimulang"><label name="CAPTION-KEMBALI"></label></button> -->
			</div>
		</div>
	</div>
</div>
