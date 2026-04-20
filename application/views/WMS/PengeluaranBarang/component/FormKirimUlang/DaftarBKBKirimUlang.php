<!-- Modal untuk menampilkan form tambah tutup periode !-->
<div class="modal fade" id="previewformbkbkirimulang" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width: 90%">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<!-- <h4 class="modal-title"><label name="CAPTION-DAFTARBKBBULK"></label></h4> -->
				<div class="col-lg-12 col-sm-12 martop">
					<div style="float:left">
						<h4 class="modal-title"><label>Daftar BKB Reschedule</label></h4>
					</div>
					<div style="float: right; padding: 5px; background-color: white; border-radius: 10px; color: black; display: flex; justify-content: space-between; align-items: center;">
						<div class="row" style="margin-left: 5px;margin-right: 0px;margin-top:5px">
							<span id="loadingloactionrak" style="display: none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
							<div style="display: flex; align-items: center;">
								<button type="button" class="btn btn-primary" id="btnaddbkbkirimulang"><label name="CAPTION-BUATBARU"></label></button>
								<div style="margin-left:8px;margin-right:8px;border:0px solid black;"></div>
								<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnbackkirimulang"><label name="CAPTION-KEMBALI"></label></button>
								<!-- <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeHandlerLocationRak()"><label name="CAPTION-KEMBALI"></label></button> -->
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
								<label name="CAPTION-NOPPB"></label>
							</div>
							<div class="col-8 col-sm-10">
								<input type="text" id="txtnoppbkirimulang" class="form-control" value="" readonly>
								<input type="hidden" id="txtidppbkirimulang" class="form-control" value="" readonly>
							</div>
						</div>
						<div class="form-group">
							<div class="PB-4 col-sm-2">
								<label name="CAPTION-NOPB"></label>
							</div>
							<div class="col-8 col-sm-10">
								<input type="text" id="txtnopbkirimulang" class="form-control" value="" readonly>
							</div>
						</div>
						<div class="form-group">
							<div class="col-4 col-sm-2">
								<label name="CAPTION-FDJR"></label>
							</div>
							<div class="col-8 col-sm-10">
								<input type="text" id="txtnofdjrbkbkirimulang" class="form-control" value="" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<table id="tablebkbkirimulang" width="100%" class="table table-striped">
						<thead>
							<tr>
								<th name="CAPTION-NODOKUMENBKB"></th>
								<th name="CAPTION-CHECKER"></th>
								<th name="CAPTION-TANGGAL"></th>
								<th name="CAPTION-DIBUATOLEH"></th>
								<th name="CAPTION-TINDAKAN"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <span id="loadingbkbkirimulang" style="display:none;"><i class="fa fa-spinner fa-spin"></i> &nbsp;<label name="CAPTION-LOADING"></label></span>
				<button type="button" class="btn btn-primary" id="btnaddbkbkirimulang"><label name="CAPTION-BUATBARU"></label></button>
				<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnbackkirimulang"><label name="CAPTION-KEMBALI"></label></button> -->
			</div>
		</div>
	</div>
</div>