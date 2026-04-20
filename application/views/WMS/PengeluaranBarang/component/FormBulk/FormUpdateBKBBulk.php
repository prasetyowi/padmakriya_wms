<!-- Modal untuk menampilkan form tambah tutup periode !-->
<div class="modal fade" id="previewupdateformbkbbulk" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width: 90%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<!-- <h4 class="modal-title"><label name="CAPTION-FORMBUATBKBBULK"></label></h4> -->
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="row">
						<div class="col-lg-12 col-sm-12 martop">
							<div style="float:left">
								<h1 class="modal-title"><label name="CAPTION-FORMBUATBKBBULK"></label></h1>
							</div>
							<div style="float: right; padding: 5px; background-color: white; border-radius: 10px; color: black; display: flex; justify-content: space-between; align-items: center;">
								<div class="row" style="margin-left: 5px;margin-right: 0px;margin-top:5px">
									<span id="loadingloactionrak" style="display: none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
									<div style="display: flex; align-items: center;">
										<button type="button" class="btn btn-danger" id="btnbackupdatebkbbulk"><label name="CAPTION-KEMBALI"></label></button>
										<!-- <button type="button" class="btn btn-success" id="simpanHandlerLocationRak" onclick="simpanHandlerLocationRak(event)"><label name="CAPTION-SIMPAN"></label></button> -->
										<!-- <div style="margin-left:8px;margin-right:8px;border:0px solid black;"></div> -->
										<!-- <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeHandlerLocationRak()"><label name="CAPTION-KEMBALI"></label></button> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<div class="col-4 col-sm-2">
								<label name="CAPTION-NOBKB"></label>
							</div>
							<div class="col-8 col-sm-10">
								<input type="text" id="txtupdatenobkbbulk" class="form-control" value="" readonly />
								<input type="hidden" id="txtupdateidbkbbulk" class="form-control" value="" readonly />
							</div>
						</div>
						<div class="form-group">
							<div class="PB-4 col-sm-2">
								<label name="CAPTION-NOPPB"></label>
							</div>
							<div class="col-8 col-sm-10">
								<input type="text" id="txtupdatenoppbbulk" class="form-control" value="" readonly />
								<input type="hidden" id="txtpickingorderid" class="form-control" value="" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-4 col-sm-2">
								<label name="CAPTION-CHECKER"></label>
							</div>
							<div class="col-8 col-sm-10">
								<select id="slcupdatecheckerbkbbulk" class="form-control" style="width: 100%;" readonly></select>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<table id="tableupdatebkbbulk" width="100%" class="table table-striped">
						<thead>
							<tr>
								<th name="CAPTION-PRINCIPLE"></th>
								<th name="CAPTION-BRAND"></th>
								<th name="CAPTION-SKUKODE"></th>
								<th name="CAPTION-NAMABARANG"></th>
								<th name="CAPTION-KEMASAN"></th>
								<th name="CAPTION-SATUAN"></th>
								<th name="CAPTION-RENCANAQTYAMBIL"></th>
								<th name="CAPTION-SISAAMBIL"></th>
								<th name="CAPTION-AKTUALQTYAMBIL"></th>
								<th name="CAPTION-TGLEXP"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<span id="loadingupdatebkbbulk" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
				<!-- <button type="button" class="btn btn-success" id="btnsaveupdatebkbbulk">Simpan</button> -->
				<!-- <button type="button" class="btn btn-danger" id="btnbackupdatebkbbulk"><label name="CAPTION-KEMBALI"></label></button> -->
			</div>
		</div>
	</div>
</div>