<!-- Modal untuk menampilkan form tambah tutup periode !-->
<div class="modal fade" id="previewaddformbkbstandar" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width: 90%">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-FORMBUATBKBSTANDAR"></label></h4>
			</div>
			<div class="modal-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<div class="col-4 col-sm-2">
								<label name="CAPTION-NOBKB"></label>
							</div>
							<div class="col-8 col-sm-10">
								<input type="text" id="txtaddnobkbstandar" class="form-control" value="" readonly />
								<input type="hidden" id="txtaddidbkbstandar" class="form-control" value="" readonly />
							</div>
						</div>
						<div class="form-group">
							<div class="PB-4 col-sm-2">
								<label name="CAPTION-NOPPB"></label>
							</div>
							<div class="col-8 col-sm-10">
								<input type="text" id="txtaddnoppbstandar" class="form-control" value="" readonly />
								<input type="hidden" id="txtpickingorderid" class="form-control" value="" readonly />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="PB-4 col-sm-2">
							<label name="CAPTION-PRINCIPLE"></label>
						</div>
						<div class="col-8 col-sm-10">
							<select name="principleFilterStandar" id="principleFilterStandar" class="form-control select2 principleFilter"></select>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<div class="col-4 col-sm-2">
								<label name="CAPTION-CHECKER"></label>
							</div>
							<div class="col-8 col-sm-10">
								<select id="slcaddcheckerbkbstandar" class="form-control" style="width: 100%"></select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-4 col-sm-2">
								<label name="CAPTION-NODO"></label>
							</div>
							<div class="col-8 col-sm-10">
								<select id="slcaddnodostandar" class="form-control" style="width: 100%"></select>
								<!-- <input type="text" id="txtaddnodostandar" class="form-control" value=""/> -->
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<table id="tableaddbkbstandar" width="100%" class="table table-striped table-bordered">
						<thead>
							<tr class="text-center">
								<td><strong><label name="CAPTION-NODO"></label></strong></td>
								<td><strong><label name="CAPTION-SKUKODE"></label></strong></td>
								<td><strong><label name="CAPTION-NAMABARANG"></label></strong></td>
								<td><strong><label name="CAPTION-KEMASAN"></label></strong></td>
								<td><strong><label name="CAPTION-SATUAN"></label></strong></td>
								<td><strong><label name="CAPTION-JUMLAHBARANG"></label></strong></td>
								<td><strong><label name="CAPTION-SISAAMBIL"></label></strong></td>
								<td><strong><label name="CAPTION-PERMINTAANEDBARANG"></label></strong></td>
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
				<span id="loadingaddbkbstandar" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
				<button type="button" class="btn btn-success" id="btnsaveaddbkbstandar"><label name="CAPTION-SIMPAN"></label></button>
				<button type="button" class="btn btn-danger" id="btnbackaddbkbstandar"><label name="CAPTION-KEMBALI"></label></button>
			</div>
		</div>
	</div>
</div>