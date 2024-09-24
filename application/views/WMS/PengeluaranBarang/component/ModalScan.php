<style>
	.check-item {
		transform: scale(1.5);
	}

	.check-item-span {
		margin-left: 7px;
		font-size: 12px;
		font-weight: 600
	}

	/* #previewlokasirak {
		overflow-y: auto;
	} */

	/* .fixed-div {
		padding: 25px;
		margin-left: 10px;
		margin-right: -50px;
		width: 98%;
		position: fixed;
		z-index: 1051;
		background: #fff;
		box-shadow: 0 5px 20px 4px rgba(0, 0, 0, .1);
	} */

	#previewlokasirak {
		overflow-y: auto;
	}

	#modalPilihManual {
		overflow-y: auto;
	}

	@media (max-width: 880px) {


		.fixed-div {
			margin-left: -10px;
			margin-right: -50px;
		}

		.martop {
			margin-top: 10px;
			float: right;
		}

		.marfull {
			margin-top: 10px;
			float: inherit;
		}
	}
</style>

<div class="modal fade" id="previewlokasirak" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width:90%">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="row">
						<div class="col-lg-6 col-sm-6 marfull">
							<div style="float: left; padding: 7px;background-color:white;border-radius:10px;color:black">
								<div style="justify-content:space-between;align-items:center">
									<div id="informasi-sku"></div>
									<!-- <div style="margin-left:10px;margin-right:10px;border:1px solid black; height:80px"></div> -->
									<div class="is-filled" id="is-filled"></div>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-sm-6 martop" style="margin-top:15px">
							<div style="float: right; padding: 5px; background-color: white; border-radius: 10px; color: black; display: flex; justify-content: space-between; align-items: center;">
								<div class="row" style="margin-left: 5px;margin-right: 0px;margin-top:5px">
									<span id="loadingloactionrak" style="display: none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
									<div style="display: flex; align-items: center;">
										<button type="button" class="btn btn-success" id="simpanHandlerLocationRak" onclick="simpanHandlerLocationRak(event)"><label name="CAPTION-SIMPAN"></label></button>
										<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeHandlerLocationRak()"><label name="CAPTION-KEMBALI"></label></button>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- <h4 class="modal-title">
						<label name="CAPTION-DETAILLOKASI"></label>

					</h4> -->
				</div>

				<input type="hidden" id="pickingorderid" />
			</div>
			<div class="modal-body form-horizontal form-label-left">
				<div class="container">
					<div class="row" id="rowswitch">

					</div>
					<div class="form-group">
						<input type="checkbox" class="check-item" name="sameSKU" id="sameSKU" checked>
						<span class="check-item-span" name="CAPTION-LIHATSKUYANGSAMA"></span>
					</div>
					<div id="dtlocationrak"></div>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <span id="loadingloactionrak" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
				<button style="display: none;" type="button" class="btn btn-success" id="simpanHandlerLocationRak" onclick="simpanHandlerLocationRak(event)"><label name="CAPTION-SIMPAN"></label></button>
				<button style="display: none;" type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeHandlerLocationRak()"><label name="CAPTION-KEMBALI"></label></button> -->
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalPilihManual" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width:90%">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="row">
						<div class="col-lg-6 col-sm-6 marfull">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title">

								<!-- <div style="float: right; padding: 7px;background-color:white;border-radius:10px;color:black">
									<div style="display: flex;justify-content:space-between;align-items:center"> -->
								<div style="float: left; padding: 7px;background-color:white;border-radius:10px;color:black">
									<div style="justify-content:space-between;align-items:center">
										<div id="informasi-sku-pilih"></div>
										<!-- <div style="margin-left:10px;margin-right:10px;border:1px solid black; height:80px"></div> -->
										<div class="is-filled" id="is-filled-pilih"></div>
									</div>
								</div>
							</h4>
						</div>
						<div class="col-lg-6 col-sm-6 martop" style="margin-top:15px">
							<div style="float: right; padding: 5px; background-color: white; border-radius: 10px; color: black; display: flex; justify-content: space-between; align-items: center;">
								<div class="row" style="margin-left: 5px;margin-right: 0px;margin-top:5px">
									<span id="loadingloactionrak" style="display: none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
									<div style="display: flex; align-items: center;">
										<button type="button" class="btn btn-success" id="simpanHandlerScanManual" onclick="simpanHandlerScanManual(event)"><label name="CAPTION-SIMPAN"></label></button>
										<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeHandlerLocationRak()"><label name="CAPTION-KEMBALI"></label></button>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" id="pickingorderid" />
						<input type="hidden" id="pickingOrderPlanId">
						<input type="hidden" id="skuKodeDiatas">
						<input type="hidden" id="expDateDiatas">
						<input type="hidden" id="typeData">
					</div>
				</div>
			</div>
			<div class="modal-body form-horizontal form-label-left">
				<div class="container">
					<!-- <div class="head-switch" style="justify-content: start;margin-bottom:1rem">
						<div class="switch-holder">
							<div class="switch-toggle">
								<input type="checkbox" id="check_scan_pilih" class="check_scan_pilih" onchange="changeScanPalletPilihHandler(event)">
								<label for="check_scan_pilih"></label>
							</div>

							<div class="switch-label" style="margin-left: -40px;">
								<button type="button" class="btn btn-info start_scan_pallet_pilih" id="start_scan_pallet_pilih" name="start_scan_pallet_pilih" onclick="startScanPalletHandler(null, null, null, null, null, null, null, null, null, null, 'pilih', null)"> <i class="fas fa-qrcode"></i> Scan</button>
								<button type="button" class="btn btn-warning input_pallet_pilih" id="input_pallet_pilih" name="input_pallet_pilih" style="display:none" onclick="inputScanPalletHandler(null, null, null, null, null, null, null, null, null, null, 'pilih', null)"> <i class="fas fa-keyboard"></i> Input</button>
							</div>
						</div>
					</div> -->
					<div class="row" style="margin-top: 10px;">
						<div class="col-lg-12 col-md-12 col-xm-12 col-sm-12">
							<div class="panel panel-default" id="initScanManualPallet">
								<div class="panel-heading"><strong>Pallet</strong></div>
								<div class="panel-body">
									<div class="container">
										<div class="table-responsive">
											<table class="table table-striped">
												<thead class="bg-primary text-white">
													<tr class="text-center">
														<td>
															<strong>Scan</strong>
														</td>
														<td>
															<strong>Pallet Kode</strong>
														</td>
														<td>
															<strong>Jenis</strong>
														</td>
														<td>
															<strong>Lokasi</strong>
														</td>
														<td>
															<strong>Status</strong>
														</td>
														<td>
															<strong>Action</strong>
														</td>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-xm-12 col-sm-12">
							<div class="panel panel-default">
								<div class="panel-heading"><strong>Pallet Detail</strong></div>
								<div class="panel-body">
									<div class="container">
										<div class="table-responsive">
											<table class="table table-striped" id="initScanManualPalletDetail" width="100%">
												<thead class="bg-primary text-white">
													<tr class="text-center">
														<td style="display:none"></td>
														<td>
															<strong>Sku Kode</strong>
														</td>
														<td>
															<strong>Sku Nama Produk</strong>
														</td>
														<td>
															<strong>Sku Satuan</strong>
														</td>
														<td>
															<strong>Sku Kemasan</strong>
														</td>
														<td>
															<strong>Qty Available</strong>
														</td>
														<td>
															<strong>Qty Aktual</strong>
														</td>
														<td>
															<strong>Expired Date</strong>
														</td>
														<!-- <td>
															<strong>Action</strong>
														</td> -->
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
				</div>
			</div>
			<div class="modal-footer">
				<!-- <span id="loadingloactionrak" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span> -->
				<!-- <button type="button" class="btn btn-success" id="simpanHandlerScanManual" onclick="simpanHandlerScanManual(event)"><label name="CAPTION-SIMPAN"></label></button>
				<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeHandlerLocationRak()"><label name="CAPTION-KEMBALI"></label></button> -->
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalScanPallet" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-SCANPALLET"></h4>
			</div>
			<div class="modal-body">
				<div id="selectCameraPallet"></div>
				<div id="previewScanPallet"></div>

				<div class="from-group" style="margin-top: 20px;">
					<label name="CAPTION-HASILSCANPALLET"></label>
					<input type="text" class="form-control" id="txtPreviewScanPallet" readonly />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-dark" onclick="closeScanPalletHandler()"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN"></label></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalInputPallet" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-MANUALINPUTCHECKPALLET"></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="label-input">
						<label name="CAPTION-KODEPALLET"></label>&nbsp;<span name="CAPTION-MASUKKANKODEPERTAMA"></span>
					</div>
					<input type="text" class="form-control nama_rak" autocomplete="off" id="nama_rak">
					<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
						<table class="table table-striped table-sm table-hover" id="table-fixed">
							<tbody id="konten-table"></tbody>
						</table>
					</div>
				</div>
				<!-- <div class="form-group">
					<div class="label-input">
						<label name="CAPTION-KODEPALLET"></label>&nbsp;<span name="CAPTION-MASUKKANKODEPERTAMA"></span>
					</div>
					<input type="text" class="form-control nama_rak" id="nama_rak" onchange="changeInputPalletHandler(event)" />
				</div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-dark" onclick="closeInputPalletHandler()"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSE"></label></button>
			</div>
		</div>
	</div>
</div>