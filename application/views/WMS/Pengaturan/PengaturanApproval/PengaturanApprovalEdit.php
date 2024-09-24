<style>
	#overlay {
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height: 100%;
		display: none;
		background: rgba(0, 0, 0, 0.6);
	}

	.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.input:focus {
		outline: none !important;
		border: 1px solid red;
		box-shadow: 0 0 10px #719ECE;
	}

	.spinner {
		width: 40px;
		height: 40px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
	}

	@keyframes sp-anime {
		100% {
			transform: rotate(360deg);
		}
	}

	.is-hide {
		display: none;
	}

	#tbody-listSKU tr,
	#tbody-listLokasiSKU tr {
		cursor: pointer;
	}
</style>
<div class="right_col" role="main">
	<form class="form" enctype="multipart/form-data" id="FormKreditLimit">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-EDITPENGATURANAPPROVAL">Edit Pengaturan Approval</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h5 name="CAPTION-EDITPENGATURANAPPROVAL">Edit Pengaturan Approval</h5>
					</div>

					<div class="x_content">
						<div class="row form-horizontal form-label-left">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 nopadding tab-content" id="anylist">
								<div class="tab-pane active" id="event">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
										<input type="hidden" id="add_id" name="add_id" class=" form-control" value="<?= $pengaturan_approval->approval_setting_id ?>" />
										<input type="hidden" id="add_detail_id" name="add_detail_id" class="txtkode form-control" />
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" name="CAPTION-MENUAPPROVAL">Menu
												Approval
											</label>
											<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
												<select id="add_menu" class="form-control custom-select">
													<option value="">-- <label name="CAPTION-PILIHMENU">Pilih Menu
														</label> --</option>
													<?php foreach ($menu_wms as $key => $value) { ?>
														<option value="<?= $value['menu_id'] ?>" data-kode="<?= $value['menu_kode'] ?>" <?= $pengaturan_approval->menu_web_id == $value['menu_id'] ? 'selected' : '' ?>>
															<?= $value['menu_name'] ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" name="CAPTION-JENISAPPROVAL">Jenis
												Approval
											</label>
											<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
												<select id="add_jenis" class="form-control custom-select">
													<option value="">-- <Label name="CAPTION-PILIHJENIS">Pilih
															Jenis</Label> --</option>
													<?php foreach ($jenis_approval as $key => $value) { ?>
														<option value="<?= $value['jenis'] ?>" data-kode="<?= $value['jenis'] ?>" <?= $pengaturan_approval->approval_setting_jenis == $value['jenis'] ? 'selected' : '' ?>>
															<?= $value['jenis'] ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" name="CAPTION-PARAMETERAPPROVAL">Parameter
												Approval
											</label>
											<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
												<input type="text" id="add_parameter" name="add_parameter" class=" form-control" value="<?= $pengaturan_approval->approval_setting_parameter ?>" />
												<i class="fa fa-info" style="display:none" id="info_check_param" aria-hidden="true"></i>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" name="CAPTION-KETERANGAN">Keterangan
											</label>
											<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
												<input type="text" id="add_keterangan" name="add_keterangan" class=" form-control" value="<?= $pengaturan_approval->approval_setting_keterangan ?>" />
											</div>
										</div>

									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">

										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" name="CAPTION-REFFURL">Reff
												Url
											</label>
											<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
												<input type="text" id="add_url" name="add_url" class=" form-control" value="<?= $pengaturan_approval->approval_setting_reff_url ?>" />
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" name="CAPTION-PARALELAPPROVAL">Paralel
												Approval
											</label>
											<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
												<input type="checkbox" id="add_is_paralel" name="add_is_paralel" class="" style="width: 20px;height: 20px;" <?= $pengaturan_approval->approval_setting_is_parallel == 1 ? 'checked' : '' ?> />
												<label class="" name="CAPTION-YA">Ya</label>
												</label>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" name="CAPTION-STATUS">Status
											</label>
											<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
												<input type="checkbox" id="add_status" name="add_status" class="" style="width: 20px;height: 20px;" <?= $pengaturan_approval->approval_setting_is_aktif == 1 ? 'checked' : '' ?> />
												<label class="" name="CAPTION-AKTIF">Aktif</label>
												</label>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row ro-batch" id="do-table">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<a class="btn btn-sm btn-primary " onclick="addDataApprover()"><i class="fa fa-plus" aria-hidden="true"></i><label name="CAPTION-DATAAPPROVER"> Data Approver </label></a>
					</div>

					<div class="row">
						<div class="table-responsive">

							<table id="tableDetail" style="width:100%;" class="table table-primary table-bordered ">
								<thead>
									<tr>
										<th style="text-align: center;" name="CAPTION-DIRECTSUPERVISOR">Direct
											Supervisor</th>
										<th style="text-align: center;" name="CAPTION-GROUP">Group</th>
										<!-- <th style="text-align: center;" name="CAPTION-DIVISI">Divisi</th>
										<th style="text-align: center;" name="CAPTION-LEVEL">Level</th> -->
										<th style="text-align: center;" name="CAPTION-MINNILAIAPPROVE">Min Nilai Approve
										</th>
										<th style="text-align: center;" name="CAPTION-MAKSNILAIAPPROVE">Maks Nilai
											Approve</th>
										<th style="text-align: center;" name="CAPTION-ACTION">Action</th>
									</tr>
								</thead>

								<tbody>
									<!-- <?php
											$no = 1;
											foreach ($pengaturan_approval_detail as $key => $value) { ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <input type="checkbox" onclick="chkSupervisor(<?= $no ?>)"
                                                class="is_direct_supervisor" id="is_direct_supervisor-<?= $no ?>"
                                                name="is_direct_supervisor[]" class="" style="width: 20px;height: 20px;"
                                                <?= $value['approval_setting_detail_is_direct_spv'] == 1 ? 'checked' : '' ?> />
                                        </td>
                                        <td>
                                            <select id="slc_divisi-<?= $no ?>" name="slc_divisi[]" class="form-control "
                                                <?= $value['approval_setting_detail_is_direct_spv'] == 1 ? 'disabled' : '' ?>>
                                                <option value="">-- <LABEL name="CAPTION-PILIHDIVISI">Pilih
                                                        Divisi</LABEL> --</option>
                                                <?php foreach ($karyawan_divisi as $key => $item) { ?>
                                                <option value="<?= $item['karyawan_divisi_id'] ?>"
                                                    data-kode="<?= $item['karyawan_divisi_nama'] ?>"
                                                    <?= $value['karyawan_divisi_id'] == $item['karyawan_divisi_id'] ? 'selected' : '' ?>>
                                                    <?= $item['karyawan_divisi_nama'] ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select id="slc_level-<?= $no ?>" name="slc_level[]" class="form-control "
                                                <?= $value['approval_setting_detail_is_direct_spv'] == 1 ? 'disabled' : '' ?>>
                                                <option value="">-- <label name="CAPTION-PILIHLEVEL">Pilih Level</label>
                                                    --</option>
                                                <?php foreach ($karyawan_level as $key => $item) { ?>
                                                <option value="<?= $item['karyawan_level_id'] ?>"
                                                    data-kode="<?= $item['karyawan_level_nama'] ?>"
                                                    <?= $value['karyawan_level_id'] == $item['karyawan_level_id'] ? 'selected' : '' ?>>
                                                    <?= $item['karyawan_level_nama'] ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td><input type="number" id="min_nilai[]" name="min_nilai[]"
                                                class=" form-control"
                                                value="<?= (int)$value['approval_setting_detail_min_nilai'] ?>" /></td>
                                        <td><input type="number" id="max_nilai[]" name="max_nilai[]"
                                                class=" form-control"
                                                value="<?= (int)$value['approval_setting_detail_max_nilai'] ?>" /></td>
                                        <td><a class="btn btn-danger DeleteRow"><i class="fa fa-trash"></i><a></td>
                                    </tr>
                                    <?php $no++;
											} ?> -->

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>










































		<div class="row mt-2">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel" style="display: flex;align-items:center;justify-content:space-between">

					<div class="text-right">
						<a class="btn btn-primary btn-update-picklist-progress" id="saveData"><label name="CAPTION-SIMPAN">Simpan</label></a>
						<a class="btn btn-danger" href="<?php echo site_url('WMS/Pengaturan/PengaturanApproval/PengaturanApprovalMenu') ?>"><label name="CAPTION-KEMBALI">Kembali</label></a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<div id="overlay">
	<div class="cv-spinner">

		<span class="spinner"></span>
	</div>
</div>

<div id="modalDetailGroup" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header  bg-primary">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Modal Header</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-striped" id="tabledetailGroup">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;" name="CAPTION-NO">NO</th>
									<th class="text-center" style="color:white;"><span name="CAPTION-NAMA">Nama</span></th>
									<th class="text-center" style="color:white;"><span name="CAPTION-NOURUT">Urut</span></th>
									<th class="text-center" style="color:white;"><span name="CAPTION-DIVISI">Divisi</span></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>