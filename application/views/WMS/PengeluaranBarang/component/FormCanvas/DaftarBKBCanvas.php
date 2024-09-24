<!-- Modal untuk menampilkan form tambah tutup periode !-->
<div class="modal fade" id="previewformbkbcanvas" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width: 90%">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-DAFTARBKBCANVAS"></label></h4>
			</div>
			<div class="modal-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<div class="col-4 col-sm-2">
								<label name="CAPTION-NOPPB"></label>
							</div>
							<div class="col-8 col-sm-10">
								<input type="text" id="txtnoppbcanvas" class="form-control" value="" readonly>
								<input type="hidden" id="txtidppbcanvas" class="form-control" value="" readonly>
							</div>
						</div>
						<div class="form-group">
							<div class="PB-4 col-sm-2">
								<label name="CAPTION-NOPB"></label>
							</div>
							<div class="col-8 col-sm-10">
								<input type="text" id="txtnopbcanvas" class="form-control" value="" readonly>
							</div>
						</div>
						<div class="form-group">
							<div class="col-4 col-sm-2">
								<label name="CAPTION-FDJR"></label>
							</div>
							<div class="col-8 col-sm-10">
								<input type="text" id="txtnofdjrbkbcanvas" class="form-control" value="" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<table id="tablebkbcanvas" width="100%" class="table table-striped">
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
				<span id="loadingbkbcanvas" style="display:none;"><i class="fa fa-spinner fa-spin"></i> &nbsp;<label name="CAPTION-LOADING"></label></span>
				<button type="button" class="btn btn-primary" id="btnaddbkbcanvas"><label name="CAPTION-BUATBARU"></label></button>
				<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnbackcanvas"><label name="CAPTION-KEMBALI"></label></button>
			</div>
		</div>
	</div>
</div>