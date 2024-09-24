<!-- Modal untuk menampilkan form tambah tutup periode !-->
<style>
	/* .modal-body {
		height: calc(100vh - 126px);
		overflow-y: scroll;
	} */

	.fixedheader {
		position: relative;
		left: 10px;
		width: 600px;
	}

	.fixed-div {
		/* padding: 25px; */
		margin-left: 10px;
		margin-right: -50px;
		/* width: 98%; */
		position: fixed;
		z-index: 1051;
		background: #fff;
		box-shadow: 0 5px 20px 4px rgba(0, 0, 0, .1);
	}

	@media (max-width: 880px) {


		.fixed-div {
			margin-left: -10px;
			margin-right: -50px;
		}
	}
</style>
<div class="modal fade" id="previewaddformbkbbulk" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width: 90%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="row">
						<div class="col-lg-12 col-sm-12 martop">
							<div style="float:left">
								<h1 class="modal-title"><label name="CAPTION-FORMBUATBKBBULK"></label></h1>
							</div>
							<div style="float: right; padding: 5px; background-color: white; border-radius: 10px; color: black; display: flex; justify-content: space-between; align-items: center;">
								<div class="row" style="margin-left: 5px;margin-right: 0px;margin-top: 5px;">

									<span id="loadingloactionrak" style="display: none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
									<div style="display: flex; align-items: center;">
										<button type="button" class="btn btn-success" id="btnsaveaddbkbbulk"><label name="CAPTION-SIMPAN"></label></button>
										<button type="button" class="btn btn-danger" id="btnbackaddbkbbulk"><label name="CAPTION-KEMBALI"></label></button>
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
						<select id="slcaddcheckerbkbbulk" class="form-control" style="width: 100%;"></select>
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-PRINCIPLE"></label>
						<select name="principleFilterBulk" id="principleFilterBulk" class="form-control select2 principleFilter"></select>
					</div>

					<div class="col-xs-3">
						<label name="CAPTION-NOBKB"></label>
						<input type="text" id="txtaddnobkbbulk" class="form-control" value="" readonly />
						<input type="hidden" id="txtaddidbkbbulk" class="form-control" value="" readonly />
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-NOPPB"></label>
						<input type="text" id="txtaddnoppbbulk" class="form-control" value="" readonly />
						<input type="hidden" id="txtpickingorderid" class="form-control" value="" readonly />
					</div>
				</div>
				<div class="row" style="margin-top: 5px;">
					<div class="col-xs-3">
						<input type="checkbox" id="slcremainderchk" name="slcremainderchk" checked style="transform: scale(1.5);margin-right:10px" onchange="handlerCheckedRemainderTake(event)"> <span name="CAPTION-CHECKIFREMAINDER" style="display: inline-block;font-size:1.5rem">Lihat yang belum diambil</span>
					</div>
				</div>
				<div class="row" style="padding: 50px;height: 500px;overflow: scroll;">
					<table id="tableaddbkbbulk" width="100%" class="table table-striped">
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
								<td><strong><label>Pilih Manual</label></strong></td>
								<td width="10%"><strong><label name="CAPTION-LOKASI"></label></strong></td>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<span id="loadingaddbkbbulk" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
				<!-- <button type="button" class="btn btn-success" id="btnsaveaddbkbbulk"><label name="CAPTION-SIMPAN"></label></button>
				<button type="button" class="btn btn-danger" id="btnbackaddbkbbulk"><label name="CAPTION-KEMBALI"></label></button> -->
			</div>
		</div>
	</div>
</div>