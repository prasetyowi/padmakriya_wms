<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-109018000">Draft Surat Tugas Pengiriman</h3>
			</div>
			<div style="float: right">
				<button class="btn-submit btn btn-primary" id="btnerrormsg" onclick="ErrorMsgDOMasal()"><i class="fa fa-message"></i> <span name="CAPTION-PESANERRORDOAPPROVE">Pesan Error DO Approve</span> (<span id="CAPTION-JUMLAHERRORMSG">0</span>) </button>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4>Filter Input</h4>
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-3">
								<label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
								<input type="text" id="filter-do-date" class="form-control" name="filter_do_date" value="" />
								<input type="hidden" id="jml_do" class="form-control" name="jml_do" value="" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-DONO">DO No.</label>
								<input type="text" id="filter-do-number" class="form-control" name="filter_do_number" value="">
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-CUSTOMER">Customer</label>
								<input type="text" id="filter-customer" class="form-control" name="filter_customer" value="">
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-ALAMAT">Alamat</label>
								<input type="text" id="filter-address" class="form-control" name="filter_address" value="">
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-xs-3">
								<label name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal</label>
								<input type="text" id="filter-so-eksternal" class="form-control" name="filter_so_eksternal" value="">
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</label>
								<select id="filter-payment-type" name="filter_payment_type" class="form-control">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
									<option value="0"><label name="CAPTION-TUNAI">Tunai</label></option>
									<option value="1"><label name="CAPTION-NONTUNAI">Non Tunai</label></option>
								</select>
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
								<select id="filter-service-type" name="filter_service_type" class="form-control">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
									<?php foreach ($TipePelayanan as $row) : ?>
										<option value="<?= $row['tipe_layanan_nama'] ?>"><?= $row['tipe_layanan_nama'] ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-STATUS">Status</label>
								<select id="filter-status" name="filter_status" class="form-control">
									<option value=""><span name="CAPTION-ALL">Semua</span></option>
									<option value="not delivered"><span name="CAPTION-GAGALKIRIM">Not Delivered</span></option>
									<option value="Approved"><span name="CAPTION-TIDAKPUNYASURATTUGASPENGIRIMAN">Tidak Punya Surat Tugas Pengiriman</span></option>
								</select>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-xs-3">
								<label name="CAPTION-TIPE">Tipe</label>
								<select id="filter-do-type" name="filter_do_type" class="form-control">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
									<?php foreach ($TipeDeliveryOrder as $type) : ?>
										<option value="<?= $type['tipe_delivery_order_id'] ?>">
											<?= $type['tipe_delivery_order_alias'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="row" style="display: none;">
							<div class="col-xs-3">
								<label name="CAPTION-SEGMENT1">Segment 1</label>
								<select id="filter-segment1" name="filter_segment_1" class="form-control select2" style="display: none;">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
								</select>
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-SEGMENT2">Segment 2</label>
								<select id="filter-segment2" name="filter_segment2" class="form-control select2" style="display: none;">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
								</select>
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-SEGMENT3">Segment 3</label>
								<select id="filter-segment3" name="filter_segment3" class="form-control select2" style="display: none;">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
								</select>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-xs-12 text-right">
								<span id="loadingviewdodraft" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
								<button type="button" id="btn_search_data_do_draft" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel" style="display: none;">
					<div class="x_title">
						<h4>Form Input</h4>
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-4">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_expired_do">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_expired_do" name="CAPTION-TANGGALEXPIRED">Tanggal Expired</label>
									<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_expired_do" class="form-control input-sm datepicker" name="DeliveryOrderDraft[_delivery_order_draft_tgl_expired_do]" autocomplete="off" value="<?= date('d-m-Y'); ?>">
								</div>
							</div>
							<div class="col-xs-4">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_surat_jalan">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_surat_jalan" name="CAPTION-TANGGALSURATJALAN">Tanggal Surat Jalan</label>
									<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_surat_jalan" class="form-control input-sm datepicker" name="DeliveryOrderDraft[_delivery_order_draft_tgl_surat_jalan]" autocomplete="off" value="<?= date('d-m-Y'); ?>">
								</div>
							</div>
							<div class="col-xs-4">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim" name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
									<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim" class="form-control input-sm datepicker" name="DeliveryOrderDraft[_delivery_order_draft_tgl_rencana_kirim]" autocomplete="off" value="<?= date('d-m-Y'); ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_title">
						<div class="clearfix"></div>
					</div>
					<div class="container">
						<ul class="nav nav-tabs">
							<li class="active">
								<a data-toggle="tab" href="#panel_do_gagal">DO Gagal</a>
							</li>
							<li>
								<a data-toggle="tab" href="#panel_not_reschedule">Not Reschedule</a>
							</li>
							<li>
								<a data-toggle="tab" href="#panel_reschedule">Reschedule</a>
							</li>
							<li>
								<a data-toggle="tab" href="#panel_not_reschedule_buat_draft">Not Reschedule Buat Draft</a>
							</li>
						</ul>
						<div class="clearfix"></div>
						<br>
						<div class="tab-content">
							<div id="panel_do_gagal" class="tab-pane fade in active">
								<div class="row">
									<div class="col-xs-12">
										<div class="x_content table-responsive">
											<table id="table_list_data_do_draft" width="100%" class="table table-striped table-bordered">
												<thead>
													<tr class="bg-primary">
														<th class="text-center" style="color:white;">
															<input type="checkbox" name="select-do" id="select-do" value="1">
														</th>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NO">NO</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NODO">No. DO</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOSO">No. SO</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NAMACUSTOMER">Nama Customer</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-ALAMATKIRIM">Alamat Kirim</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-AREA">Area</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-TIPE">Tipe</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOMINAL">Nominal</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-STATUS">Status</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-TANGGALKIRIMULANG">Tanggal Kirim Ulang</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-ACTION">Action</label>
															</strong>
														</td>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
								<br />
								<div class="row">
									<div class="col-xs-12 text-right">
										<span id="loadingviewdodraft" style="display:none;">
											<i class="fa fa-spinner fa-spin"></i>
											<label name="CAPTION-LOADING">Loading</label>... </span>
										<button class="btn btn-success" id="btn_rescheduled_save_fdjr">
											<i class="fa-regular fa-calendar-days"></i>
											<span name="CAPTION-RESCHEDULE">Reschedule</span>
										</button>
										<button class="btn btn-primary" id="btn_not_rescheduled_save_fdjr">
											<i class="fa-solid fa-rectangle-xmark"></i>
											<span name="CAPTION-NOTRESCHEDULE">Not Reschedule</span>
										</button>
										<!-- <button class="btn btn-success" id="btnBuatBaru" onclick="handlerBuatBaruSTK()"><i class="fas fa-search"></i><span name="CAPTION-BUATBARU">Buat Baru</span></button><button class="btn btn-primary" id="btn_insert_do_to_fdjr"><i class="fa-solid fa-arrow-right-from-bracket"></i><span name="CAPTION-SISIPKANSURATTUGASPENGIRIMAN">Sisipkan Surat Tugas Pengiriman</span></button> -->
									</div>
								</div>
							</div>
							<div id="panel_not_reschedule" class="tab-pane fade">
								<div class="row">
									<div class="col-xs-12">
										<div class="x_content table-responsive">
											<table id="table_list_data_do_draft_not_reschedule" width="100%" class="table table-striped table-bordered">
												<thead>
													<tr class="bg-primary">
														<th class="text-center" style="color:white;">
															<input type="checkbox" name="select-do-not-reschedule" id="select-do-not-reschedule" value="1">
														</th>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NODO">No. DO</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOSO">No. SO</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NAMACUSTOMER">Nama Customer</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-ALAMATKIRIM">Alamat Kirim</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-AREA">Area</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-TIPE">Tipe</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOMINAL">Nominal</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-STATUS">Status</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-ACTION">Action</label>
															</strong>
														</td>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
								<br />
								<div class="row">
									<div class="col-xs-12 text-right">
										<span id="loadingviewdodraft" style="display:none;">
											<i class="fa fa-spinner fa-spin"></i>
											<label name="CAPTION-LOADING">Loading</label>... </span>
										<button class="btn btn-success" id="btn_generate_do_not_reschedule">
											<i class="fa-regular fa-save"></i>
											<span name="CAPTION-GENERATEDO">Generate DO</span>
										</button>
									</div>
								</div>
							</div>
							<div id="panel_reschedule" class="tab-pane fade">
								<div class="row">
									<div class="col-xs-12">
										<div class="x_content table-responsive">
											<table id="table_list_data_do_draft_reschedule" width="100%" class="table table-striped table-bordered">
												<thead>
													<tr class="bg-primary">
														<th class="text-center" style="color:white;">#</th>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NODO">No. DO</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOSO">No. SO</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NAMACUSTOMER">Nama Customer</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-ALAMATKIRIM">Alamat Kirim</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-AREA">Area</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-TIPE">Tipe</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOMINAL">Nominal</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-STATUS">Status</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-ACTION">Action</label>
															</strong>
														</td>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
								<br />
							</div>
							<div id="panel_not_reschedule_buat_draft" class="tab-pane fade">
								<div class="row">
									<div class="col-xs-12">
										<div class="x_content table-responsive">
											<table id="table_list_data_do_draft_not_reschedule_buat_draft" width="100%" class="table table-striped table-bordered">
												<thead>
													<tr class="bg-primary">
														<th class="text-center" style="color:white;">#</th>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NODO">No. DO</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOSO">No. SO</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NAMACUSTOMER">Nama Customer</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-ALAMATKIRIM">Alamat Kirim</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-AREA">Area</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-TIPE">Tipe</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-NOMINAL">Nominal</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-STATUS">Status</label>
															</strong>
														</td>
														<td class="text-center" style="color:white;">
															<strong>
																<label name="CAPTION-ACTION">Action</label>
															</strong>
														</td>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
								<br />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_sisipan_fdjr" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" style="width:80%">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-BATCHSURATTUGASPENGIRIMAN">Batch Surat Tugas Pengiriman</h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
								<input type="text" id="filter-sj-date" class="form-control" name="filter_sj_date" value="" />
								<input type="hidden" id="jml_fdjr" class="form-control" name="jml_fdjr" value="0" />
								<input type="hidden" id="id_do_batch_baru_temp" class="form-control" name="id_do_batch_baru_temp" value="" />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label name="CAPTION-AREA">Area</label>
								<select name="area_fdjr" id="area_fdjr" class="form-control select2">
									<option value="">All</option>
									<?php foreach ($areas as $key => $value) { ?>
										<option value="<?= $value['area_nama'] ?>"><?= $value['area_nama'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-xs-3">
							<button class="btn btn-primary" onclick="handlerDataSearchFDJR()" style="margin-top: 24px;"><i class="fas fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
						</div>
					</div>

					<div class="table-responsinve">
						<table id="table_list_data_do_batch" width="100%" class="table table-striped table-bordered">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;">#</th>
									<th class="text-center" style="color:white;">No</th>
									<td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALBATCH">Tanggal Batch</label></strong></td>
									<td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</label></strong></td>
									<td class="text-center" style="color:white;"><strong><label name="CAPTION-NOBATCH">No. Batch</label></strong></td>
									<td class="text-center" style="color:white;"><strong><label name="CAPTION-TIPEDELIVERYORDER">Tipe Delivery Order</label></strong></td>
									<td class="text-center" style="color:white;"><strong><label name="CAPTION-DRIVER">Driver</label></strong></td>
									<td class="text-center" style="color:white;"><strong><label name="CAPTION-TIPELAYANAN">Tipe Layanan</label></strong></td>
									<!-- <td class="text-center" style="color:white;"><strong>Pengemasan</strong></td> -->
									<td class="text-center" style="color:white;"><strong><label name="CAPTION-AREA">Area</label></strong></td>
									<td class="text-center" style="color:white;"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
									<td class="text-center" style="color:white; display: none;"></td>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="btn_handler_save_fdjr"><i class="fas fa-save"></i> <span name="CAPTION-SAVE">Simpan</span></button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-xmark"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_not_rescheduled_alert" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h3 class="modal-title text-center"><b>Apakah anda yakin?</b></h3>
			</div>
			<div class="modal-body text-center">
				<p style="font-size: 16px">Pilih salah satu dibawah ini!</p>
			</div>
			<div class="modal-footer" style="text-align: center;">
				<button type="button" class="btn btn-primary text-center" id="saveButton" onclick="generateDraftMutasi()">Generate Draft Mutasi </button>
				<button type="button" class="btn btn-success text-center" id="denyButton" onclick="generateLayoutingPallet()">Generate Layouting Pallet</button>
				<button type="button" class="btn btn-danger text-center" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
</div>



<div class="modal fade" id="modal-pesan-error" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-PESANERRORDOAPPROVE">Pesan Error DO Approve</label></h4>
			</div>
			<div class="modal-body">
				<div class="row" id="panel-sku">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<table class="table table-bordered" id="table-pesan-error" style="width:100%;">
								<thead>
									<tr class="bg-primary">
										<th style="text-align: center; vertical-align: middle;color:white;">#</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NODO">No. DO</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">SKU</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PESAN">Pesan</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modal_sisipan_fdjr_baru" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" style="width:80%">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-BATCHSURATTUGASPENGIRIMAN">Batch Surat Tugas Pengiriman</h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<div class="row">
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderbatch-delivery_order_batch_tanggal">
								<label class="control-label" for="deliveryorderbatch-delivery_order_batch_tanggal" name="CAPTION-TANGGALBATCH">Tanggal Batch</label>
								<input readonly="readonly" type="text" id="deliveryorderbatch-delivery_order_batch_tanggal" class="form-control" name="DeliveryOrderBatch[delivery_order_batch_tanggal]" autocomplete="off" value="<?= date('d-m-Y') ?>">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderbatch-delivery_order_batch_kode">
								<label class="control-label" for="deliveryorderbatch-delivery_order_batch_kode" name="CAPTION-NOBATCH">No Batch</label>
								<input readonly="readonly" type="text" id="deliveryorderbatch-delivery_order_batch_kode" class="form-control" name="DeliveryOrderBatch[delivery_order_batch_kode]" autocomplete="off" value="">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderbatch-delivery_order_batch_tipe_layanan_id">
								<label class="control-label" for="deliveryorderbatch-delivery_order_batch_tipe_layanan_id" name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
								<select name="DeliveryOrderBatch[delivery_order_batch_tipe_layanan_id]" class="form-control select2" id="deliveryorderbatch-delivery_order_batch_tipe_layanan_id">
									<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
									<?php foreach ($TipePelayanan as $row) : ?>
										<option value="<?= $row['tipe_layanan_id'] ?> || <?= $row['tipe_layanan_kode'] ?> || <?= $row['tipe_layanan_nama'] ?>">
											<?= $row['tipe_layanan_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderbatch-tipe_delivery_order_id">
								<label class="control-label" for="deliveryorderbatch-tipe_delivery_order_id" name="CAPTION-TIPE">Tipe</label>
								<select name="DeliveryOrderBatch[tipe_delivery_order_id]" class="form-control select2" id="deliveryorderbatch-tipe_delivery_order_id" disabled readonly>
									<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
									<?php foreach ($TipeDeliveryOrder as $row) : ?>
										<option value="<?= $row['tipe_delivery_order_id'] ?>" <?= $row['tipe_delivery_order_id'] === 'EE5CD3F7-F7E3-475E-A7B4-67FD5AB65975' ? 'selected' : '' ?>>
											<?= $row['tipe_delivery_order_alias'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderbatch-delivery_order_batch_tanggal_kirim">
								<label class="control-label" for="deliveryorderbatch-delivery_order_batch_tanggal_kirim" name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
								<input type="text" id="deliveryorderbatch-delivery_order_batch_tanggal_kirim" class="form-control datepicker" name="DeliveryOrderBatch[delivery_order_batch_tanggal_kirim]" autocomplete="off" value="<?= date('d-m-Y') ?>">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderbatch-tipe_ekspedisi_id">
								<label class="control-label" for="deliveryorderbatch-tipe_ekspedisi_id" name="CAPTION-TIPEEKSPEDISI">Tipe Ekspedisi</label>
								<select name="DeliveryOrderBatch[tipe_ekspedisi_id]" class="form-control select2" id="deliveryorderbatch-tipe_ekspedisi_id">
									<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
									<?php foreach ($TipeEkspedisi as $row) : ?>
										<option value="<?= $row['tipe_ekspedisi_id'] ?>"><?= $row['tipe_ekspedisi_nama'] ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderbatch-kendaraan_id">
								<label class="control-label" for="deliveryorderbatch-kendaraan_id" name="CAPTION-KENDARAAN">Kendaraan</label>
								<select name="DeliveryOrderBatch[kendaraan_id]" class="form-control select2" id="deliveryorderbatch-kendaraan_id">
									<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
									<?php foreach ($Kendaraan as $row) : ?>
										<option value="<?= $row['kendaraan_id'] ?>"><?= $row['kendaraan_nopol'] ?> -
											<?= $row['kendaraan_model'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderbatch-karyawan_id">
								<label class="control-label" for="deliveryorderbatch-karyawan_id" name="CAPTION-DRIVER">Driver</label>
								<select name="DeliveryOrderBatch[karyawan_id]" class="form-control select2" id="deliveryorderbatch-karyawan_id">
									<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
									<?php foreach ($Driver as $row) : ?>
										<option value="<?= $row['karyawan_id'] ?>"><?= $row['karyawan_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="btn_handler_save_fdjr_baru"><i class="fas fa-save"></i> <span name="CAPTION-SAVE">Simpan</span></button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-xmark"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
			</div>
		</div>
	</div>
</div>