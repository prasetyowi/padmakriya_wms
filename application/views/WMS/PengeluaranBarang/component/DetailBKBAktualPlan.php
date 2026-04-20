<div class="modal fade" id="previewdetailbkbaktualplan" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width: 90%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
				<!-- <h4 class="modal-title">
          <label name="CAPTION-DETAILBKB"></label>
        </h4> -->
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
										<!-- <button type="button" class="btn btn-success" id="simpanHandlerLocationRak" onclick="simpanHandlerLocationRak(event)"><label name="CAPTION-SIMPAN"></label></button> -->
										<!-- <div style="margin-left:8px;margin-right:8px;border:0px solid black;"></div> -->
										<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnbackbkbaktualplan"><label name="CAPTION-KEMBALI"></label></button>
										<!-- <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeHandlerLocationRak()"><label name="CAPTION-KEMBALI"></label></button> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" id="pickingorderid" />
			</div>
			<div class="modal-body form-horizontal form-label-left">
				<div class="row">
					<table id="tablebkbaktualplan" width="100%" class="table table-striped">
						<thead>
							<tr>
								<th name="CAPTION-NOBKB"></th>
								<th name="CAPTION-PRINCIPLE"></th>
								<th name="CAPTION-BRAND"></th>
								<th name="CAPTION-KODESKU"></th>
								<th name="CAPTION-NAMABARANG"></th>
								<th name="CAPTION-KEMASAN"></th>
								<th name="CAPTION-SATUAN"></th>
								<th name="CAPTION-RENCANANOANTRIAN"></th>
								<th name="CAPTION-RENCANAQTYAMBIL"></th>
								<th name="CAPTION-RENCANATANGGALKADALUWARSA"></th>
								<th name="CAPTION-RENCANANOAKTUAL"></th>
								<th name="CAPTION-AKTUALQTYAMBIL"></th>
								<th name="CAPTION-AKTUALTANGGALKADALUWARSA"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <span id="loadingbkbaktualplan" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span> -->
				<!-- <button type="button" class="btn btn-danger" data-dismiss="modal" id="btnbackbkbaktualplan"><label name="CAPTION-KEMBALI"></label></button> -->
			</div>
		</div>
	</div>
</div>