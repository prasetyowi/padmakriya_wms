<script type="text/javascript">
	var index_pallet = 0;
	var arr_list_multi_sku = [];
	var arr_tr_mutasi_stok_detail2 = [];
	var index_no_urut = 0;

	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		});
	}

	<?php if (isset($act)) { ?>
		<?php if ($act == "edit") { ?>
			<?php foreach ($Detail as $key => $value) { ?>
				arr_list_multi_sku.push({
					'sku_id': "<?= $value['sku_id'] ?>",
					'sku_stock_id': "<?= $value['sku_stock_id'] ?>",
					'sku_stock_qty': "<?= $value['qty'] ?>",
					'sku_stock_expired_date': "<?= $value['sku_stock_expired_date'] ?>",
					'pallet_id_asal': "<?= $value['pallet_id_asal'] ?>",
					'sku_stock_qty_awal': "<?= $value['sku_stock_qty_awal'] ?>"
				});
			<?php } ?>

			<?php foreach ($Detail2 as $key => $value) { ?>
				arr_tr_mutasi_stok_detail2.push({
					'tr_mutasi_stok_detail_id': "<?= $value['tr_mutasi_stok_detail_id'] ?>",
					'no_urut': "<?= $key + 1 ?>",
					'depo_detail_id_tujuan': "<?= $value['depo_detail_id_tujuan'] ?>",
					'pallet_id_tujuan': "<?= $value['pallet_id_tujuan'] ?>",
					'sku_stock_id_tujuan': "<?= $value['sku_stock_id_tujuan'] ?>",
					'sku_id': "<?= $value['sku_id'] ?>",
					'sku_stock_expired_date': "<?= $value['sku_stock_expired_date'] ?>",
					'sku_stock_qty': <?= $value['qty'] ?>
				});
			<?php } ?>

			Get_list_mutasi_stock_detail();
		<?php } ?>

		<?php if ($act == "detail") { ?>
			<?php foreach ($Detail as $key => $value) { ?>
				arr_list_multi_sku.push({
					'sku_id': "<?= $value['sku_id'] ?>",
					'sku_stock_id': "<?= $value['sku_stock_id'] ?>",
					'sku_stock_qty': "<?= $value['qty'] ?>",
					'sku_stock_expired_date': "<?= $value['sku_stock_expired_date'] ?>",
					'pallet_id_asal': "<?= $value['pallet_id_asal'] ?>",
					'sku_stock_qty_awal': "<?= $value['sku_stock_qty_awal'] ?>"
				});
			<?php } ?>

			<?php foreach ($Detail2 as $key => $value) { ?>
				arr_tr_mutasi_stok_detail2.push({
					'tr_mutasi_stok_detail_id': "<?= $value['tr_mutasi_stok_detail_id'] ?>",
					'no_urut': "<?= $key + 1 ?>",
					'depo_detail_id_tujuan': "<?= $value['depo_detail_id_tujuan'] ?>",
					'pallet_id_tujuan': "<?= $value['pallet_id_tujuan'] ?>",
					'sku_stock_id_tujuan': "<?= $value['sku_stock_id_tujuan'] ?>",
					'sku_id': "<?= $value['sku_id'] ?>",
					'sku_stock_expired_date': "<?= $value['sku_stock_expired_date'] ?>",
					'sku_stock_qty': <?= $value['qty'] ?>
				});
			<?php } ?>

			Get_list_mutasi_stock_detail();
		<?php } ?>
	<?php } ?>

	$('#select-all-sku').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = true;
				var sku_id = this.getAttribute('data-sku_id');
				var sku_stock_id = this.getAttribute('data-sku_stock_id');
				var sku_stock_qty = this.getAttribute('data-sku_stock_qty');
				var sku_stock_qty_awal = this.getAttribute('data-sku_stock_qty_awal');
				var sku_stock_expired_date = this.getAttribute('data-sku_stock_expired_date');

				arr_list_multi_sku.push({
					'sku_id': sku_id,
					'sku_stock_id': sku_stock_id,
					'sku_stock_qty': sku_stock_qty,
					'sku_stock_expired_date': sku_stock_expired_date,
					'pallet_id_asal': "",
					'sku_stock_qty_awal': sku_stock_qty_awal,
				});
				// console.log(this.getAttribute('data-customer'));
			});
		} else {
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = false;
				arr_list_multi_sku = [];
			});
		}
	});

	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$(".select2").select2();
			$('#filter_mutasi_tgl').daterangepicker({
				'applyClass': 'btn-sm btn-success',
				'cancelClass': 'btn-sm btn-default',
				locale: {
					"format": "DD/MM/YYYY",
					applyLabel: 'Apply',
					cancelLabel: 'Cancel',
				},
				'startDate': '<?= date("01-m-Y") ?>',
				'endDate': '<?= date("t-m-Y") ?>'
			});

			$("#mutasi_principle").append('<option value="">** <label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label> **</option>');
			$("#filter_mutasi_principle").append('<option value=""><label name="CAPTION-ALL">All</label></option>');
		}
	);

	function GetPrincipleHome(client_wms_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/MutasiStok/getDataPrincipleByClientWmsId') ?>",
			data: {
				id: client_wms_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#filter_mutasi_principle").empty();
				let html = "";
				html += '<option value=""><label name="CAPTION-ALL">All</label></option>';
				$.each(response, function(i, v) {
					html += "<option value=" + v.id + ">" + v.nama + "</option>";
				});
				$("#filter_mutasi_principle").append(html);

			}
		});
	}

	function GetPrinciple(client_wms_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/MutasiStok/getDataPrincipleByClientWmsId') ?>",
			data: {
				id: client_wms_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#mutasi_principle").empty();
				let html = "";
				html += '<option value="">** <label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label> **</option>';
				$.each(response, function(i, v) {
					html += "<option value=" + v.id + ">" + v.nama + "</option>";
				});
				$("#mutasi_principle").append(html);

			}
		});
	}

	$('#mutasi_approval').click(function(event) {
		if (this.checked) {
			$("#mutasi_status").val("In Progress Approval");
		} else {
			$("#mutasi_status").val("Draft");
		}
	});

	function GetPencarianMutasiStokTable() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiStok/GetPencarianMutasiStokTable') ?>",
			data: {
				tanggal: $("#filter_mutasi_tgl").val(),
				id: $("#filter_no_mutasi").val(),
				gudang_asal: $("#filter_gudang_asal_mutasi").val(),
				gudang_tujuan: $("#filter_gudang_tujuan_mutasi").val(),
				tipe: $("#filter_mutasi_tipe_transaksi").val(),
				client_wms: $("#filter_mutasi_perusahaan").val(),
				principle: $("#filter_mutasi_principle").val(),
				checker: $("#filter_mutasi_checker").val(),
				status: $("#filter_mutasi_status").val()
			},
			dataType: "JSON",
			success: function(response) {
				$('#table_pencarian_mutasi').fadeOut("slow", function() {
					$(this).hide();

					$('#table_pencarian_mutasi > tbody').empty('');

					if ($.fn.DataTable.isDataTable('#table_pencarian_mutasi')) {
						$('#table_pencarian_mutasi').DataTable().clear();
						$('#table_pencarian_mutasi').DataTable().destroy();
					}

				}).fadeIn("slow", function() {
					$(this).show();

					if (response.MutasiStok.length != 0) {

						$.each(response.MutasiStok, function(i, v) {

							if (v.tr_mutasi_stok_status == "Completed") {

								$("#table_pencarian_mutasi > tbody").append(`
									<tr>
										<td class="text-center">${i+1}</td>
										<td class="text-left">${v.tr_mutasi_stok_kode}</td>
										<td class="text-left">${v.tr_mutasi_stok_tanggal}</td>
										<td class="text-left">${v.principle_kode}</td>
										<td class="text-left">${v.tr_mutasi_stok_nama_checker}</td>
										<td class="text-left">${v.depo_detail_nama}</td>
										<td class="text-left">${v.tr_mutasi_stok_status}</td>
										<td class="text-center">
											<a href="<?= base_url(); ?>WMS/MutasiStok/MutasiStokDetail/?tr_mutasi_stok_id=${v.tr_mutasi_stok_id}" class="btn btn-primary" target="_blank"><i class="fa fa-eye"></i></a>
										</td>
									</tr>
								`);

							} else {

								$("#table_pencarian_mutasi > tbody").append(`
									<tr>
										<td class="text-center">${i+1}</td>
										<td class="text-left">${v.tr_mutasi_stok_kode}</td>
										<td class="text-left">${v.tr_mutasi_stok_tanggal}</td>
										<td class="text-left">${v.principle_kode}</td>
										<td class="text-left">${v.tr_mutasi_stok_nama_checker}</td>
										<td class="text-left">${v.depo_detail_nama}</td>
										<td class="text-left">${v.tr_mutasi_stok_status}</td>
										<td class="text-center">
											<a href="<?= base_url(); ?>WMS/MutasiStok/MutasiStokEdit/?tr_mutasi_stok_id=${v.tr_mutasi_stok_id}" class="btn btn-primary" target="_blank"><i class="fa fa-pencil"></i></a>
										</td>
									</tr>
								`);

							}
						});

						$("#table_pencarian_mutasi").DataTable({
							lengthMenu: [
								[50, 100, 200, -1],
								[50, 100, 200, 'All'],
							],
						});
					}
				});
			}
		});
	}

	function ChPencarianMutasiStokTable(JSONMutasiPallet) {
		$("#table_pencarian_mutasi > tbody").html('');
		$("#loadingview").hide();

		var MutasiPallet = JSON.parse(JSONMutasiPallet);
		var no = 1;

		if (MutasiPallet.MutasiStok != 0) {
			if ($.fn.DataTable.isDataTable('#table_pencarian_mutasi')) {
				$('#table_pencarian_mutasi').DataTable().destroy();
			}

			$('#table_pencarian_mutasi tbody').empty();

			for (i = 0; i < MutasiPallet.MutasiStok.length; i++) {
				var tr_mutasi_pallet_id = MutasiPallet.MutasiStok[i].tr_mutasi_pallet_id;
				var tr_mutasi_pallet_kode = MutasiPallet.MutasiStok[i].tr_mutasi_pallet_kode;
				var tr_mutasi_pallet_tanggal = MutasiPallet.MutasiStok[i].tr_mutasi_pallet_tanggal;
				var tr_mutasi_pallet_tipe = MutasiPallet.MutasiStok[i].tr_mutasi_pallet_tipe;
				var principle_id = MutasiPallet.MutasiStok[i].principle_id;
				var principle_kode = MutasiPallet.MutasiStok[i].principle_kode;
				var tr_mutasi_pallet_nama_checker = MutasiPallet.MutasiStok[i].tr_mutasi_pallet_nama_checker;
				var depo_detail_id_asal = MutasiPallet.MutasiStok[i].depo_detail_id_asal;
				var gudang_asal = MutasiPallet.MutasiStok[i].gudang_asal;
				var depo_detail_id_tujuan = MutasiPallet.MutasiStok[i].depo_detail_id_tujuan;
				var gudang_tujuan = MutasiPallet.MutasiStok[i].gudang_tujuan;
				var tr_mutasi_pallet_status = MutasiPallet.MutasiStok[i].tr_mutasi_pallet_status;


				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_tanggal + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_tipe + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + principle_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_nama_checker + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + gudang_asal + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + gudang_tujuan + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_status + '</td>';
				strmenu = strmenu + '	<td class="text-center"><a href="<?= base_url(); ?>WMS/MutasiStok/MutasiStokEdit/?tr_mutasi_pallet_id=' + tr_mutasi_pallet_id + '" class="btn btn-primary" target="_blank"><i class="fa fa-pencil"></i></a></td>';
				strmenu = strmenu + '</tr>';
				no++;

				$("#table_pencarian_mutasi > tbody").append(strmenu);
			}

			$("#loadingview").hide();

			$('#table_pencarian_mutasi').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
		} else {
			ResetForm();
		}
	}

	function GetSKU() {
		var gudang_asal = $("#gudang_asal_mutasi").val();
		var perusahaan = $("#mutasi_perusahaan").val();
		var principle = $("#mutasi_principle").val();

		if (gudang_asal == "") {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih Gudang Asal!'
			});
		} else if (perusahaan == "") {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih Perusahaan!'
			});
		} else if (principle == "") {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih Principle!'
			});
		} else {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/MutasiStok/GetSKU') ?>",
				data: {
					gudang_asal: $("#gudang_asal_mutasi").val(),
					principle: $("#mutasi_principle").val(),
					perusahaan: perusahaan,
					arr_list_multi_sku: arr_list_multi_sku
				},
				dataType: "JSON",
				success: function(response) {
					if (response.SKU.length > 0) {
						$('#modal-sku').modal('show');

						$('#data-table-sku').fadeOut("slow", function() {
							$(this).hide();

							$('#data-table-sku > tbody').empty('');

							if ($.fn.DataTable.isDataTable('#data-table-sku')) {
								$('#data-table-sku').DataTable().clear();
								$('#data-table-sku').DataTable().destroy();
							}

						}).fadeIn("slow", function() {
							$(this).show();

							if (response.SKU.length != 0) {
								$.each(response.Gudang, function(i, v) {
									$("#gudang_asal_sku").val(v.depo_detail_nama);
								});

								$.each(response.Principle, function(i, v) {
									$("#principle_sku").val(v.principle_kode);
								});

								$.each(response.Perusahaan, function(i, v) {
									$("#perusahaan_sku").val(v.client_wms_nama);
								});

								$.each(response.SKU, function(i, v) {
									$("#data-table-sku > tbody").append(`
										<tr>
											<td class="text-center">
												<input type="checkbox" name="CheckboxSKU" id="item-${i}-sku_stock_id" data-sku_id="${v.sku_id}" data-sku_stock_id="${v.sku_stock_id}" data-sku_stock_qty="${v.sku_stock_qty}" data-sku_stock_expired_date="${v.sku_stock_expired_date}" data-sku_stock_qty_awal="${v.sku_stock_qty_awal}" value="${v.sku_stock_id}" onClick="PushArraySKU('${i}','${v.sku_id}','${v.sku_stock_id}','${v.sku_stock_qty}','${v.sku_stock_expired_date}','${v.sku_stock_qty_awal}')">
											</td>
											<td class="text-left">${v.sku_kode}</td>
											<td class="text-left">${v.sku_nama_produk}</td>
											<td class="text-left">${v.sku_stock_expired_date}</td>
											<td class="text-right">${v.sku_stock_qty}</td>
										</tr>
									`);
								});

								$("#data-table-sku").DataTable({
									lengthMenu: [
										[50, 100, 200, -1],
										[50, 100, 200, 'All'],
									],
								});
							}
						});
					}
				}
			});
		}
	}

	$("#btn_pencarian_mutasi_pallet").click(function() {
		GetPencarianMutasiStokTable();
	});

	$("#btn_pilih_mutasi_sku").click(
		function() {
			GetSKU();
		}
	);

	$("#btn_save_mutasi").click(function() {
		cek_error = 0;

		if (arr_list_multi_sku.length == 0) {

			let alert = "List SKU Tidak Boleh Kosong";
			message_custom("Error", "error", alert);

			return false;
		}

		$.each(arr_list_multi_sku, function(i, v) {
			if (parseInt(v.sku_stock_qty) == 0) {
				let alert = "SKU Stock Tidak Boleh 0";
				message_custom("Error", "error", alert);

				cek_error++;

				return false;
			}

			if (parseInt(v.sku_stock_qty) > parseInt(v.sku_stock_qty_awal)) {

				let alert = "Qty Mutasi Tidak Boleh Lebih Besar Dari SKU Stock Pallet";
				message_custom("Error", "error", alert);

				cek_error++;

				return false;
			}

			if (v.pallet_id_asal == 0) {
				let alert = "Pallet Asal Tidak Boleh Kosong";
				message_custom("Error", "error", alert);

				cek_error++;

				return false;
			}

		});

		setTimeout(() => {

			// console.log(arr_list_faktur_klaim);

			if ($("#mutasi_perusahaan").val() == "") {

				let alert = "Perusahaan Tidak Boleh Kosong";
				message_custom("Error", "error", alert);

				return false;
			}

			if ($("#mutasi_principle").val() == "") {

				let alert = "Principle Tidak Boleh Kosong";
				message_custom("Error", "error", alert);

				return false;
			}

			if ($("#gudang_asal_mutasi").val() == "") {

				let alert = "Gudang Asal Tidak Boleh Kosong";
				message_custom("Error", "error", alert);

				return false;
			}

			if ($("#Pmutasi_checker").val() == "") {

				let alert = "Checker Tidak Boleh Kosong";
				message_custom("Error", "error", alert);

				return false;
			}

			if (cek_error == 0) {

				Swal.fire({
					title: "Apakah anda yakin?",
					text: "Pastikan data yang sudah anda input benar!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Ya",
					cancelButtonText: "Tidak"
				}).then((result) => {
					if (result.value == true) {

						// ResetForm();

						$.ajax({
							async: false,
							url: "<?= base_url('WMS/MutasiStok/insert_tr_mutasi_stok'); ?>",
							type: "POST",
							beforeSend: function() {
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false
								});

								$("#btn_save_mutasi").prop("disabled", true);
							},
							data: {
								tr_mutasi_stok_id: "",
								client_wms_id: $('#mutasi_perusahaan').val(),
								principle_id: $('#mutasi_principle').val(),
								tr_mutasi_stok_kode: "",
								tr_mutasi_stok_tanggal: $('#mutasi_tanggal').val(),
								tr_mutasi_stok_keterangan: $('#mutasi_keterangan').val(),
								tr_mutasi_stok_status: $('#mutasi_status').val(),
								depo_id_asal: "<?= $this->session->userdata('depo_id'); ?>",
								depo_detail_id_asal: $('#gudang_asal_mutasi').val(),
								tr_mutasi_stok_tgl_create: "",
								tr_mutasi_stok_who_create: "",
								tr_mutasi_stok_nama_checker: $('#mutasi_checker').val(),
								tr_mutasi_stok_tgl_update: "",
								tr_mutasi_stokt_who_update: "",
								detail: arr_list_multi_sku
							},
							dataType: "JSON",
							success: function(response) {

								if (response == 1) {

									var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
									// var alert = "Data Berhasil Disimpan";
									message_custom("Success", "success", alert);
									setTimeout(() => {
										location.href = "<?= base_url() ?>WMS/MutasiStok/MutasiStokMenu";
									}, 500);

									ResetForm();
								} else if (response == "2") {

									var msg = "Mutasi Stock sudah ada";
									message_custom("Error", "error", alert);
								} else {
									// var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
									var alert = response.data.message;
									message_custom("Error", "error", alert);
								}

								$("#btn_save_mutasi").prop("disabled", false);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								message("Error", "Error 500 Internal Server Connection Failure", "error");

								$("#btn_save_mutasi").prop("disabled", false);
							},
							complete: function() {
								// Swal.close();
								$("#btn_save_mutasi").prop("disabled", false);
							}
						});
					}
				});

			}

		}, 1000);
	});

	$("#btn_update_mutasi").click(function() {
		cek_error = 0;

		if (arr_tr_mutasi_stok_detail2.length == 0) {

			let alert = "Mutasi stock detail kosong";
			message_custom("Error", "error", alert);

			return false;
		}

		if (arr_tr_mutasi_stok_detail2.length == 0) {

			let alert = "Mutasi stock detail kosong";
			message_custom("Error", "error", alert);

			return false;
		}

		setTimeout(() => {

			if (cek_error == 0) {

				Swal.fire({
					title: "Apakah anda yakin?",
					text: "Pastikan data yang sudah anda input benar!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Ya",
					cancelButtonText: "Tidak"
				}).then((result) => {
					if (result.value == true) {

						$.ajax({
							async: false,
							url: "<?= base_url('WMS/MutasiStok/update_tr_mutasi_stok'); ?>",
							type: "POST",
							beforeSend: function() {
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false
								});

								$("#btn_save_mutasi").prop("disabled", true);
							},
							data: {
								tr_mutasi_stok_id: $('#tr_mutasi_stok_id').val(),
								client_wms_id: $('#mutasi_perusahaan').val(),
								principle_id: $('#mutasi_principle').val(),
								tr_mutasi_stok_kode: $('#mutasi_no').val(),
								tr_mutasi_stok_tanggal: $('#mutasi_tanggal').val(),
								tr_mutasi_stok_keterangan: $('#mutasi_keterangan').val(),
								tr_mutasi_stok_status: $('#mutasi_status').val(),
								depo_id_asal: "<?= $this->session->userdata('depo_id'); ?>",
								depo_detail_id_asal: $('#gudang_asal_mutasi').val(),
								tr_mutasi_stok_tgl_create: "",
								tr_mutasi_stok_who_create: "",
								tr_mutasi_stok_nama_checker: $('#mutasi_checker').val(),
								tr_mutasi_stok_tgl_update: $('#tr_mutasi_stok_tgl_update').val(),
								tr_mutasi_stokt_who_update: $('#tr_mutasi_stokt_who_update').val(),
								detail: arr_list_multi_sku,
								detail2: arr_tr_mutasi_stok_detail2
							},
							dataType: "JSON",
							success: function(response) {

								if (response.status == 200) {

									var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
									// var alert = "Data Berhasil Disimpan";
									message_custom("Success", "success", alert);
									setTimeout(() => {
										location.href = "<?= base_url() ?>WMS/MutasiStok/MutasiStokEdit/?tr_mutasi_stok_id=" + $('#tr_mutasi_stok_id').val();
									}, 500);

									ResetForm();
								} else if (response.status == 400) {
									messageNotSameLastUpdated();
									return false;
								} else if (response.status == 401) {
									let arrayOfErrorsToDisplayMutasiStokDetail = [];
									let indexErrorMutasiStokDetail = [];
									$.each(data.data, (index, item) => {
										let value = item.data;
										// arrayOfErrorsToDisplayMutasiStokDetail
										//     = []
										// indexErrorMutasiStokDetail = []
										indexErrorMutasiStokDetail.push(index + 1);
										arrayOfErrorsToDisplayMutasiStokDetail
											.push({
												title: 'Data Gagal Disimpan!',
												html: `Mutasi stok detail <strong>${value.sku_nama_produk}, tanggal kadaluwarsa ${value.sku_stock_expired_date}</strong> tidak ada`
											});
										// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
									});
									Swal.mixin({
										icon: 'error',
										confirmButtonText: 'Next &rarr;',
										showCancelButton: true,
										progressSteps: indexErrorMutasiStokDetail
									}).queue(arrayOfErrorsToDisplayMutasiStokDetail)
								} else {
									var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
									// var alert = response.data.message;
									message_custom("Error", "error", alert);
								}

								$("#btn_update_mutasi").prop("disabled", false);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								message("Error", "Error 500 Internal Server Connection Failure", "error");

								$("#btn_update_mutasi").prop("disabled", false);
							},
							complete: function() {
								// Swal.close();
								$("#btn_update_mutasi").prop("disabled", false);
							}
						});
					}
				});

			}

		}, 1000);
	});

	$("#btn_konfirmasi_mutasi").click(function() {
		cek_error = 0;

		if (arr_tr_mutasi_stok_detail2.length == 0) {

			let alert = "Mutasi stock detail kosong";
			message_custom("Error", "error", alert);

			return false;
		}

		if (arr_tr_mutasi_stok_detail2.length == 0) {

			let alert = "Mutasi stock detail kosong";
			message_custom("Error", "error", alert);

			return false;
		}

		setTimeout(() => {

			if (cek_error == 0) {

				Swal.fire({
					title: "Apakah anda yakin?",
					text: "Pastikan data yang sudah anda input benar!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Ya",
					cancelButtonText: "Tidak"
				}).then((result) => {
					if (result.value == true) {

						$.ajax({
							async: false,
							url: "<?= base_url('WMS/MutasiStok/konfirmasi_tr_mutasi_stok'); ?>",
							type: "POST",
							beforeSend: function() {
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false
								});

								$("#btn_save_mutasi").prop("disabled", true);
							},
							data: {
								tr_mutasi_stok_id: $('#tr_mutasi_stok_id').val(),
								client_wms_id: $('#mutasi_perusahaan').val(),
								principle_id: $('#mutasi_principle').val(),
								tr_mutasi_stok_kode: $('#mutasi_no').val(),
								tr_mutasi_stok_tanggal: $('#mutasi_tanggal').val(),
								tr_mutasi_stok_keterangan: $('#mutasi_keterangan').val(),
								tr_mutasi_stok_status: $('#mutasi_status').val(),
								depo_id_asal: "<?= $this->session->userdata('depo_id'); ?>",
								depo_detail_id_asal: $('#gudang_asal_mutasi').val(),
								tr_mutasi_stok_tgl_create: "",
								tr_mutasi_stok_who_create: "",
								tr_mutasi_stok_nama_checker: $('#mutasi_checker').val(),
								tr_mutasi_stok_tgl_update: $('#tr_mutasi_stok_tgl_update').val(),
								tr_mutasi_stokt_who_update: $('#tr_mutasi_stokt_who_update').val(),
								detail: arr_list_multi_sku,
								detail2: arr_tr_mutasi_stok_detail2
							},
							dataType: "JSON",
							success: function(response) {

								if (response.status == 200) {

									var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
									// var alert = "Data Berhasil Disimpan";
									message_custom("Success", "success", alert);
									setTimeout(() => {
										location.href = "<?= base_url() ?>WMS/MutasiStok/MutasiStokMenu";
									}, 500);

									ResetForm();
								} else if (response.status == 400) {
									messageNotSameLastUpdated();
									return false;
								} else if (response.status == 401) {
									let arrayOfErrorsToDisplayMutasiStokDetail = [];
									let indexErrorMutasiStokDetail = [];
									$.each(data.data, (index, item) => {
										let value = item.data;
										// arrayOfErrorsToDisplayMutasiStokDetail
										//     = []
										// indexErrorMutasiStokDetail = []
										indexErrorMutasiStokDetail.push(index + 1);
										arrayOfErrorsToDisplayMutasiStokDetail
											.push({
												title: 'Data Gagal Disimpan!',
												html: `Mutasi stok detail <strong>${value.sku_nama_produk}, tanggal kadaluwarsa ${value.sku_stock_expired_date}</strong> tidak ada`
											});
										// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
									});
									Swal.mixin({
										icon: 'error',
										confirmButtonText: 'Next &rarr;',
										showCancelButton: true,
										progressSteps: indexErrorMutasiStokDetail
									}).queue(arrayOfErrorsToDisplayMutasiStokDetail)
								} else {
									var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
									// var alert = response.data.message;
									message_custom("Error", "error", alert);
								}

								$("#btn_update_mutasi").prop("disabled", false);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								message("Error", "Error 500 Internal Server Connection Failure", "error");

								$("#btn_update_mutasi").prop("disabled", false);
							},
							complete: function() {
								// Swal.close();
								$("#btn_update_mutasi").prop("disabled", false);
							}
						});
					}
				});

			}

		}, 1000);
	});

	function PushArraySKU(idx, sku_id, sku_stock_id, sku_stock_qty, sku_stock_expired_date, sku_stock_qty_awal) {
		$("#select-all-sku").prop("checked", false);

		if ($('[id="item-' + idx + '-sku_stock_id"]:checked').length > 0) {
			arr_list_multi_sku.push({
				'sku_id': sku_id,
				'sku_stock_id': sku_stock_id,
				'sku_stock_qty': sku_stock_qty,
				'sku_stock_expired_date': sku_stock_expired_date,
				'pallet_id_asal': "",
				'sku_stock_qty_awal': sku_stock_qty_awal
			});

			const uniqueArray = [];
			const seenIds = {};

			for (const obj of arr_list_multi_sku) {
				if (!seenIds[obj.sku_stock_id]) {
					seenIds[obj.sku_stock_id] = true;
					uniqueArray.push(obj);
				}
			}
			arr_list_multi_sku = uniqueArray;
		} else {
			const findIndexData = arr_list_multi_sku.findIndex((value) => value.sku_stock_id == sku_stock_id);
			if (findIndexData > -1) { // only splice array when item is found
				arr_list_multi_sku.splice(findIndexData, 1); // 2nd parameter means remove one item only
			}
		}
	}

	$("#btn-choose-sku-multi").click(function() {
		$("#modal-sku").hide();
		Get_list_mutasi_stock_detail();
	});

	function Get_list_mutasi_stock_detail() {
		let tr_mutasi_stok_id = "";

		<?php if (isset($act)) { ?>
			<?php if ($act == "edit") { ?>
				tr_mutasi_stok_id = "<?= $tr_mutasi_stok_id; ?>"
			<?php } ?>

			<?php if ($act == "detail") { ?>
				tr_mutasi_stok_id = "<?= $tr_mutasi_stok_id; ?>"
			<?php } ?>
		<?php } ?>

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiStok/Get_list_mutasi_stock_detail') ?>",
			data: {
				tr_mutasi_stok_id: tr_mutasi_stok_id,
				arr_list_multi_sku: arr_list_multi_sku,
				arr_tr_mutasi_stok_detail2: arr_tr_mutasi_stok_detail2
			},
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			dataType: "JSON",
			success: function(response) {

				$('#table_mutasi').fadeOut("slow", function() {
					$(this).hide();

					$('#table_mutasi > tbody').empty('');

					if ($.fn.DataTable.isDataTable('#table_mutasi')) {
						$('#table_mutasi').DataTable().clear();
						$('#table_mutasi').DataTable().destroy();
					}

				}).fadeIn("slow", function() {
					$(this).show();

					if (response.length != 0) {

						$.each(response, function(i, v) {

							if (v.cek_tmsd2 == '1') {
								$("#table_mutasi > tbody").append(`
									<tr class="bg-success">
										<td class="text-center" style="width:5%;">
											${i+1}
											<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-tr_mutasi_stok_detail_id" value="${v.tr_mutasi_stok_detail_id}">
											<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_id" value="${v.sku_stock_id}">
											<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_id" value="${v.sku_id}">
											<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_expired_date" value="${v.sku_stock_expired_date}">
											<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_qty_awal" value="${v.sku_stock_qty_awal}">
										</td>
										<td class="text-left" style="width:10%;">
											<span id="item-${i}-tr_mutasi_stok_detail-sku_kode">${v.sku_kode}</span>
										</td>
										<td class="text-left" style="width:20%;">
											<span id="item-${i}-tr_mutasi_stok_detail-sku_nama_produk">${v.sku_nama_produk}</span>
										</td>
										<td class="text-left" style="width:10%;">
											<span id="item-${i}-tr_mutasi_stok_detail-sku_satuan">${v.sku_satuan}</span>
										</td>
										<td class="text-left" style="width:15%;">${v.sku_stock_expired_date}</td>
										<td class="text-center" style="width:25%;">
											<select class="form-control select2_detail" style="width:100%;" id="item-${i}-tr_mutasi_stok_detail-pallet_id_asal" disabled>
												<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>
											</select>
										</td>
										<td class="text-center" style="width:15%;">
											<input type="number" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_qty" value="${v.sku_stock_qty}" disabled>
										</td>
										<td class="text-center" style="width:5%;">
											<button type="button" class="btn btn-primary btn-sm" onClick="GetListMutasiStokDetail2('${i}')"><i class="fa fa-pencil"></i></button>
										</td>
									</tr>
								`);

							} else {
								$("#table_mutasi > tbody").append(`
									<tr class="bg-danger">
										<td class="text-center" style="width:5%;">
											${i+1}
											<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-tr_mutasi_stok_detail_id" value="${v.tr_mutasi_stok_detail_id}">
											<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_id" value="${v.sku_stock_id}">
											<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_id" value="${v.sku_id}">
											<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_expired_date" value="${v.sku_stock_expired_date}">
											<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_qty_awal" value="${v.sku_stock_qty_awal}">
										</td>
										<td class="text-left" style="width:10%;">
											<span id="item-${i}-tr_mutasi_stok_detail-sku_kode">${v.sku_kode}</span>
										</td>
										<td class="text-left" style="width:20%;">
											<span id="item-${i}-tr_mutasi_stok_detail-sku_nama_produk">${v.sku_nama_produk}</span>
										</td>
										<td class="text-left" style="width:10%;">
											<span id="item-${i}-tr_mutasi_stok_detail-sku_satuan">${v.sku_satuan}</span>
										</td>
										<td class="text-left" style="width:15%;">${v.sku_stock_expired_date}</td>
										<td class="text-center" style="width:25%;">
											<select class="form-control select2_detail" style="width:100%;" id="item-${i}-tr_mutasi_stok_detail-pallet_id_asal" disabled>
												<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>
											</select>
										</td>
										<td class="text-center" style="width:15%;">
											<input type="number" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_qty" value="${v.sku_stock_qty}" disabled>
										</td>
										<td class="text-center" style="width:5%;">
											<button type="button" class="btn btn-primary btn-sm" onClick="GetListMutasiStokDetail2('${i}')"><i class="fa fa-pencil"></i></button>
										</td>
									</tr>
								`);
							}
						});

						$.each(response, function(i, v) {

							$("#item-" + i + "-tr_mutasi_stok_detail-pallet_id_asal").html('');

							$("#item-" + i + "-tr_mutasi_stok_detail-pallet_id_asal").append(`<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>`);

							$.ajax({
								type: 'POST',
								url: "<?= base_url('WMS/MutasiStok/Get_pallet_by_sku_stock_id') ?>",
								data: {
									sku_stock_id: v.sku_stock_id
								},
								dataType: "JSON",
								success: function(response2) {
									$.each(response2, function(i2, v2) {
										$("#item-" + i + "-tr_mutasi_stok_detail-pallet_id_asal").append(`<option value="${v2.pallet_id}" ${v2.pallet_id == v.pallet_id_asal ? 'selected' : '' }>${v2.pallet_kode} || ${v2.sku_stock_qty} ${v2.sku_satuan}</option>`);
									});
								},
								error: function(xhr, ajaxOptions, thrownError) {
									message("Error", "Error 500 Internal Server Connection Failure", "error");
								},
								complete: function() {
									Swal.close();
								}
							});
						});

						$(".select2_detail").select2();

						$("#table_mutasi").DataTable({
							lengthMenu: [
								[50, 100, 200, -1],
								[50, 100, 200, 'All'],
							],
						});
					}
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {
				Swal.close();
			}
		});

	}

	$("#btnback").click(
		function() {
			ResetForm();
			GetMutasiStokMenu();
		}
	);

	$("#btn-back-detail-pallet").click(
		function() {
			$('#modal-pallet-detail').modal('hide');
			$('#modal-pallet').modal('show');
		}
	);

	$("#btn-back-detail-pallet-2").click(
		function() {
			$('#modal-pallet-detail-2').modal('hide');
		}
	);

	$("#btn_history_approval").click(
		function() {
			var dokumen_id = $("#mutasi_pallet_id").val();
			$('#modalHistoryApproval').modal('show');

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Approval/getApprovalByDocumentId') ?>",
				data: {
					dokumen_id: dokumen_id
				},
				success: function(response) {

					let no = 1;
					let data = response;

					if ($.fn.DataTable.isDataTable('#tableHistoryApproval')) {
						$('#tableHistoryApproval').DataTable().destroy();
					}

					$("#tableHistoryApproval tbody").empty();
					$("#tableHistoryApproval tbody").html('');
					if (data.length > 0) {
						$.each(data, function() {
							if (this.approval_status == "Approved") {
								var color = "style='background-color:green;color:white'"
							} else {
								var color = "style='background-color:red;color:white'"
							}
							$("#txt_jenis_pengajuan").val(`${this.approval_reff_dokumen_jenis}`);

							$('#tableHistoryApproval tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; text-align: center; ' >${no}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.tgl}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.approval_reff_dokumen_kode}</td>
                                    <td style='vertical-align:middle; text-align: center;' ><a ${color} class="btn btn-md">${this.approval_status}<a/></td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.karyawan_nama}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.approval_keterangan}</td>
                                </tr>
                            `);
							no++;
						});
					} else {
						$("#tableHistoryApproval tbody").html('');
					}

					$('#tableHistoryApproval').DataTable({
						paging: false
					});

				}
			});
		}
	);

	function DeletePallet(row) {
		var jml_sku = $("#txt_jml_sku").val() - 1;

		var row = row.parentNode.parentNode;
		row.parentNode.removeChild(row);

		$("#txt_jml_sku").val(jml_sku);
	}

	function ViewPallet() {
		$("#table_mutasi > tbody").empty();
		$("#txt_jml_sku").val(0);

		if ($("#mutasi_tipe_transaksi").val() != "") {
			GetGudangTujuan($("#mutasi_tipe_transaksi").val());
		} else {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih Transaksi!'
			});
		}
	}

	function ViewDetailPallet(pallet_id) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiStok/GetPalletDetail') ?>",
			data: {
				pallet_id: pallet_id
			},
			success: function(response) {
				if (response) {
					$('#modal-pallet-detail').modal('show');
					$('#modal-pallet').modal('hide');

					ChPalletDetail(response);
				}
			}
		});

	}

	function ViewDetailPallet2(pallet_id) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiStok/GetPalletDetail') ?>",
			data: {
				pallet_id: pallet_id
			},
			success: function(response) {
				if (response) {
					$('#modal-pallet-detail-2').modal('show');

					ChPalletDetail(response);
				}
			}
		});

	}

	function ChPalletDetail(JSONMutasiPallet) {
		$("#data-table-pallet-detail > tbody").html('');

		var MutasiPallet = JSON.parse(JSONMutasiPallet);
		var no = 1;

		var pallet_id = MutasiPallet.PalletDetail[0].pallet_id;
		var pallet_kode = MutasiPallet.PalletDetail[0].pallet_kode;
		var pallet_jenis_id = MutasiPallet.PalletDetail[0].pallet_jenis_id;
		var pallet_jenis_nama = MutasiPallet.PalletDetail[0].pallet_jenis_nama;

		$("#pallet_detail_pallet_kode").val(pallet_kode);
		$("#pallet_detail_jenis_pallet").val(pallet_jenis_nama);
		$("#pallet_detail_pallet_kode2").val(pallet_kode);
		$("#pallet_detail_jenis_pallet2").val(pallet_jenis_nama);

		if (MutasiPallet.Pallet != 0) {
			if ($.fn.DataTable.isDataTable('#data-table-pallet-detail')) {
				$('#data-table-pallet-detail').DataTable().destroy();
			}

			$('#data-table-pallet-detail tbody').empty();

			for (i = 0; i < MutasiPallet.PalletDetail.length; i++) {
				var pallet_id = MutasiPallet.PalletDetail[i].pallet_id;
				var pallet_kode = MutasiPallet.PalletDetail[i].pallet_kode;
				var pallet_jenis_id = MutasiPallet.PalletDetail[i].pallet_jenis_id;
				var pallet_jenis_nama = MutasiPallet.PalletDetail[i].pallet_jenis_nama;
				var sku_id = MutasiPallet.PalletDetail[i].sku_id;
				var sku_kode = MutasiPallet.PalletDetail[i].sku_kode;
				var principle_kode = MutasiPallet.PalletDetail[i].principle_kode;
				var sku_nama_produk = MutasiPallet.PalletDetail[i].sku_nama_produk;
				var sku_kemasan = MutasiPallet.PalletDetail[i].sku_kemasan;
				var sku_satuan = MutasiPallet.PalletDetail[i].sku_satuan;
				var sku_stock_expired_date = MutasiPallet.PalletDetail[i].sku_stock_expired_date;
				var penerimaan_tipe_nama = MutasiPallet.PalletDetail[i].penerimaan_tipe_nama;
				var sku_stock_qty = MutasiPallet.PalletDetail[i].sku_stock_qty;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td class="text-center">' + no + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + principle_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_stock_expired_date + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + penerimaan_tipe_nama + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_stock_qty + '</td>';
				strmenu = strmenu + '</tr>';
				no++;

				$("#data-table-pallet-detail > tbody").append(strmenu);
			}

			$("#loadingview").hide();

			$('#data-table-pallet-detail').DataTable({
				"lengthMenu": [
					[-1],
					["All"]
				],
				"ordering": false,
				"bInfo": false,
				"paging": false
			});
		}
	}

	function GetCheckerPrinciple(principle) {

		$("#mutasi_checker").html('');
		$("#table_mutasi > tbody").empty();
		$("#txt_jml_sku").val(0);

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiStok/GetCheckerPrinciple') ?>",
			data: {
				principle: principle,
				client_wms_id: $("#mutasi_perusahaan").val()
			},
			success: function(response) {
				let data = response;

				$("#mutasi_checker").html('');
				if (data.length > 0) {
					$.each(data, function() {
						$("#mutasi_checker").append(`<option value="${this.karyawan_id} || ${this.karyawan_nama}">${this.karyawan_nama}</option>`);
					});
				} else {
					$("#mutasi_checker").html('');
				}
			}
		});

	}

	function GetGudangTujuan(tipe_mutasi) {

		$("#gudang_tujuan_mutasi").html('');

		if (tipe_mutasi == "C02CC613-2FAB-49A9-9372-B27D9444FE93") {

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/MutasiStok/GetGudangTujuanByTipe') ?>",
				data: {
					gudang_asal: $("#gudang_asal_mutasi").val()
				},
				success: function(response) {
					let data = response;

					$("#gudang_tujuan_mutasi").html('');
					if (data.length > 0) {
						$("#gudang_tujuan_mutasi").append(`<option value="">** Pilih Gudang Tujuan **</option>`);
						$.each(data, function() {
							$("#gudang_tujuan_mutasi").append(`<option value="${this.depo_detail_id}">${this.depo_detail_nama}</option>`);
						});
					} else {
						$("#gudang_tujuan_mutasi").html('');
					}
				}
			});
		} else {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/MutasiStok/GetGudangTujuan') ?>",
				data: {
					gudangAsal: $("#gudang_asal_mutasi").val()
				},
				success: function(response) {
					let data = response;

					$("#gudang_tujuan_mutasi").html('');
					if (data.length > 0) {
						$("#gudang_tujuan_mutasi").append(`<option value="">** Pilih Gudang Tujuan **</option>`);
						$.each(data, function() {
							$("#gudang_tujuan_mutasi").append(`<option value="${this.depo_detail_id}">${this.depo_detail_nama}</option>`);
						});
					} else {
						$("#gudang_tujuan_mutasi").html('');
					}
				}
			});
		}
	}

	function GetListMutasiStokDetail2(index) {
		$("#modal-mutasi-stok-detail").modal('show');

		let tr_mutasi_stok_detail_id = $("#item-" + index + "-tr_mutasi_stok_detail-tr_mutasi_stok_detail_id").val();
		let sku_stock_id = $("#item-" + index + "-tr_mutasi_stok_detail-sku_stock_id").val();
		let sku_id = $("#item-" + index + "-tr_mutasi_stok_detail-sku_id").val();
		let sku_kode = $("#item-" + index + "-tr_mutasi_stok_detail-sku_kode").text();
		let sku_nama_produk = $("#item-" + index + "-tr_mutasi_stok_detail-sku_nama_produk").text();
		let sku_satuan = $("#item-" + index + "-tr_mutasi_stok_detail-sku_satuan").text();
		let sku_stock_expired_date = $("#item-" + index + "-tr_mutasi_stok_detail-sku_stock_expired_date").val();
		let sku_stock_qty = $("#item-" + index + "-tr_mutasi_stok_detail-sku_stock_qty").val();
		let slc_pallet_asal = $("#item-" + index + "-tr_mutasi_stok_detail-pallet_id_asal option:selected").text();
		let pallet_asal = slc_pallet_asal.split(" || ");
		let pallet_kode_asal = pallet_asal[0];

		$("#filter_mutasi_stok_detail_tr_mutasi_stok_detail_id").val(tr_mutasi_stok_detail_id);
		$("#filter_mutasi_stok_detail_sku_stock_id").val(sku_stock_id);
		$("#filter_mutasi_stok_detail_sku_id").val(sku_id);
		$("#filter_mutasi_stok_detail_sku_kode").val(sku_kode);
		$("#filter_mutasi_stok_detail_sku").val(sku_nama_produk);
		$("#filter_mutasi_stok_detail_sku_stock_expired_date").val(sku_stock_expired_date);
		$("#filter_mutasi_stok_detail_sku_satuan").val(sku_satuan);
		$("#filter_mutasi_stok_detail_sku_stock_qty").val(sku_stock_qty);
		$("#filter_mutasi_stok_detail_pallet_asal").val(pallet_kode_asal);

		Get_list_mutasi_stock_detail2();

	}

	function Get_list_mutasi_stock_detail2() {

		let tr_mutasi_stok_detail_id = $("#filter_mutasi_stok_detail_tr_mutasi_stok_detail_id").val();
		let sku_stock_id = $("#filter_mutasi_stok_detail_sku_stock_id").val();
		let sku_id = $("#filter_mutasi_stok_detail_sku_id").val();
		let sku_kode = $("#filter_mutasi_stok_detail_sku_kode").val();
		let sku_nama_produk = $("#filter_mutasi_stok_detail_sku").val();
		let sku_stock_expired_date = $("#filter_mutasi_stok_detail_sku_stock_expired_date").val();
		let sku_satuan = $("#filter_mutasi_stok_detail_sku_satuan").val();
		let sku_stock_qty_awal = $("#filter_mutasi_stok_detail_sku_stock_qty").val();
		let pallet_kode_asal = $("#filter_mutasi_stok_detail_pallet_asal").val();

		// console.log(arr_tr_mutasi_stok_detail2);

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiStok/Get_list_mutasi_stock_detail2') ?>",
			data: {
				tr_mutasi_stok_detail_id: tr_mutasi_stok_detail_id,
				arr_tr_mutasi_stok_detail2: arr_tr_mutasi_stok_detail2
			},
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			dataType: "JSON",
			success: function(response) {

				$('#table_mutasi_stok_detail').fadeOut("slow", function() {
					$(this).hide();

					$('#table_mutasi_stok_detail > tbody').empty('');

					if ($.fn.DataTable.isDataTable('#table_mutasi_stok_detail')) {
						$('#table_mutasi_stok_detail').DataTable().clear();
						$('#table_mutasi_stok_detail').DataTable().destroy();
					}

				}).fadeIn("slow", function() {
					$(this).show();

					if (response.length != 0) {

						<?php if (isset($act)) { ?>


							<?php if ($act == "edit") { ?>
								$.each(response, function(i, v) {
									$("#table_mutasi_stok_detail > tbody").append(`
										<tr>
											<td class="text-center" style="width:5%;">
												${i+1}
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-tr_mutasi_stok_detail_id" value="${v.tr_mutasi_stok_detail_id}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-sku_stock_id" value="${v.sku_stock_id}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-sku_id" value="${v.sku_id}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-sku_stock_expired_date" value="${v.sku_stock_expired_date}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-no_urut" value="${v.no_urut}">
											</td>
											<td class="text-center" style="width:30%;">
												<select class="form-control" style="width:100%;" id="item-${i}-tr_mutasi_stok_detail2-depo_detail_id_tujuan" onchange="UpdateListMutasiStokDetail2('${v.tr_mutasi_stok_detail_id}','${v.no_urut}','${i}')">
													<option value="${v.depo_detail_id_tujuan}">${v.depo_detail_tujuan}</option>
												</select>
											</td>
											<td class="text-center" style="width:35%;">
												<select class="form-control" style="width:100%;" id="item-${i}-tr_mutasi_stok_detail2-pallet_id_tujuan" onchange="UpdateListMutasiStokDetail2('${v.tr_mutasi_stok_detail_id}','${v.no_urut}','${i}')">
													<option value="${v.pallet_id_tujuan}">${v.pallet_tujuan}</option>
												</select>
											</td>
											<td class="text-center" style="width:15%;">
												<input type="number" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-sku_stock_qty" value="${v.sku_stock_qty}" onchange="UpdateListMutasiStokDetail2('${v.tr_mutasi_stok_detail_id}','${v.no_urut}','${i}')">
											</td>
											<td class="text-center" style="width:5%;">
												<button type="button" class="btn btn-danger btn-sm" onClick="DeleteListMutasiStokDetail2('${v.tr_mutasi_stok_detail_id}','${v.no_urut}','${i}')"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
									`);
								});

								$.each(response, function(i, v) {
									$('#item-' + i + '-tr_mutasi_stok_detail2-depo_detail_id_tujuan').select2({
										ajax: {
											url: '<?= base_url('WMS/MutasiStok/Get_list_gudang_tujuan') ?>', // URL to your CodeIgniter controller method
											dataType: 'json',
											delay: 250,
											data: function(params) {
												return {
													q: params.term,
													depo_detail_id_tujuan: v.depo_detail_id_tujuan
												};
											},
											processResults: function(data, params) {
												return {
													results: data
												};
											},
											cache: true
										},
										placeholder: GetLanguageByKode('CAPTION-PILIH'),
										minimumInputLength: 0
									});

									$('#item-' + i + '-tr_mutasi_stok_detail2-pallet_id_tujuan').select2({
										ajax: {
											url: '<?= base_url('WMS/MutasiStok/Get_list_pallet_tujuan') ?>', // URL to your CodeIgniter controller method
											dataType: 'json',
											delay: 250,
											data: function(params) {
												return {
													q: params.term,
													depo_detail_id_tujuan: $('#item-' + i + '-tr_mutasi_stok_detail2-depo_detail_id_tujuan').val(),
													pallet_id_tujuan: v.pallet_id_tujuan
												};
											},
											processResults: function(data, params) {
												return {
													results: data
												};
											},
											cache: true
										},
										placeholder: GetLanguageByKode('CAPTION-PILIH'),
										minimumInputLength: 0
									});
								});
							<?php } ?>

							<?php if ($act == "detail") { ?>

								$.each(response, function(i, v) {
									$("#table_mutasi_stok_detail > tbody").append(`
										<tr>
											<td class="text-center" style="width:5%;">
												${i+1}
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-tr_mutasi_stok_detail_id" value="${v.tr_mutasi_stok_detail_id}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-sku_stock_id" value="${v.sku_stock_id}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-sku_id" value="${v.sku_id}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-sku_stock_expired_date" value="${v.sku_stock_expired_date}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-no_urut" value="${v.no_urut}">
											</td>
											<td class="text-center" style="width:30%;">
												<select class="form-control" style="width:100%;" id="item-${i}-tr_mutasi_stok_detail2-depo_detail_id_tujuan" onchange="UpdateListMutasiStokDetail2('${v.tr_mutasi_stok_detail_id}','${v.no_urut}','${i}')" disabled>
													<option value="${v.depo_detail_id_tujuan}">${v.depo_detail_tujuan}</option>
												</select>
											</td>
											<td class="text-center" style="width:35%;">
												<select class="form-control" style="width:100%;" id="item-${i}-tr_mutasi_stok_detail2-pallet_id_tujuan" onchange="UpdateListMutasiStokDetail2('${v.tr_mutasi_stok_detail_id}','${v.no_urut}','${i}')" disabled>
													<option value="${v.pallet_id_tujuan}">${v.pallet_tujuan}</option>
												</select>
											</td>
											<td class="text-center" style="width:15%;">
												<input type="number" class="form-control" id="item-${i}-tr_mutasi_stok_detail2-sku_stock_qty" value="${v.sku_stock_qty}" onchange="UpdateListMutasiStokDetail2('${v.tr_mutasi_stok_detail_id}','${v.no_urut}','${i}')" disabled>
											</td>
										</tr>
									`);
								});

								$.each(response, function(i, v) {
									$('#item-' + i + '-tr_mutasi_stok_detail2-depo_detail_id_tujuan').select2({
										ajax: {
											url: '<?= base_url('WMS/MutasiStok/Get_list_gudang_tujuan') ?>', // URL to your CodeIgniter controller method
											dataType: 'json',
											delay: 250,
											data: function(params) {
												return {
													q: params.term,
													depo_detail_id_tujuan: v.depo_detail_id_tujuan
												};
											},
											processResults: function(data, params) {
												return {
													results: data
												};
											},
											cache: true
										},
										placeholder: GetLanguageByKode('CAPTION-PILIH'),
										minimumInputLength: 0
									});

									$('#item-' + i + '-tr_mutasi_stok_detail2-pallet_id_tujuan').select2({
										ajax: {
											url: '<?= base_url('WMS/MutasiStok/Get_list_pallet_tujuan') ?>', // URL to your CodeIgniter controller method
											dataType: 'json',
											delay: 250,
											data: function(params) {
												return {
													q: params.term,
													depo_detail_id_tujuan: $('#item-' + i + '-tr_mutasi_stok_detail2-depo_detail_id_tujuan').val(),
													pallet_id_tujuan: v.pallet_id_tujuan
												};
											},
											processResults: function(data, params) {
												return {
													results: data
												};
											},
											cache: true
										},
										placeholder: GetLanguageByKode('CAPTION-PILIH'),
										minimumInputLength: 0
									});
								});
							<?php } ?>
						<?php } ?>

						$("#table_mutasi_stok_detail").DataTable({
							"lengthMenu": [
								[50, 100, 200, -1],
								[50, 100, 200, 'All'],
							],
							"searching": false
						});
					}
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {
				Swal.close();
			}
		});

	}

	$("#btn_tambah_tujuan_mutasi").click(function() {
		index_no_urut = arr_tr_mutasi_stok_detail2.length;
		index_no_urut++;
		arr_tr_mutasi_stok_detail2.push({
			'tr_mutasi_stok_detail_id': $("#filter_mutasi_stok_detail_tr_mutasi_stok_detail_id").val(),
			'no_urut': index_no_urut,
			'depo_detail_id_tujuan': "",
			'pallet_id_tujuan': "",
			'sku_stock_id_tujuan': "",
			'sku_id': $("#filter_mutasi_stok_detail_sku_id").val(),
			'sku_stock_expired_date': $("#filter_mutasi_stok_detail_sku_stock_expired_date").val(),
			'sku_stock_qty': 0
		});

		Get_list_mutasi_stock_detail2();
	});

	$("#btn-save-mutasi-stok-detail").click(function() {
		let tr_mutasi_stok_detail_id = $("#filter_mutasi_stok_detail_tr_mutasi_stok_detail_id").val();
		let sku_stock_qty_awal = parseInt($("#filter_mutasi_stok_detail_sku_stock_qty").val());

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiStok/Get_total_qty_mutasi_stock_detail2') ?>",
			data: {
				tr_mutasi_stok_detail_id: tr_mutasi_stok_detail_id,
				arr_tr_mutasi_stok_detail2: arr_tr_mutasi_stok_detail2
			},
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			dataType: "JSON",
			success: function(response) {

				if (response.total == sku_stock_qty_awal) {
					let alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
					message("Success", alert, "success");

					$("#modal-mutasi-stok-detail").modal('hide');

					Get_list_mutasi_stock_detail();
				} else {
					let alert = "Total Qty Tidak Cocok Dengan Total Header";
					message("Error", alert, "error");
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {

			}
		});
	});

	$("#btn-tutup-mutasi-stok-detail").click(function() {
		Get_list_mutasi_stock_detail();
	});

	function UpdateListSKU(sku_stock_id, index, tipe) {

		const findIndexData = arr_list_multi_sku.findIndex((value) => value.sku_stock_id == sku_stock_id);
		let slc_pallet_asal = $("#item-" + index + "-tr_mutasi_stok_detail-pallet_id_asal option:selected").text();
		let pallet_asal = slc_pallet_asal.split(" || ");
		let sku_stock_qty = parseInt(pallet_asal[1].replace(/^\D+/g, ''));

		if (tipe == "select") {

			$('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty').val(sku_stock_qty);
			$('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty_awal').val(sku_stock_qty);

			arr_list_multi_sku[findIndexData] = ({
				'sku_stock_id': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_id').val(),
				'sku_id': $('#item-' + index + '-tr_mutasi_stok_detail-sku_id').val(),
				'sku_stock_qty': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty').val(),
				'sku_stock_expired_date': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_expired_date').val(),
				'pallet_id_asal': $('#item-' + index + '-tr_mutasi_stok_detail-pallet_id_asal').val(),
				'sku_stock_qty_awal': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty_awal').val()
			});

		} else {

			arr_list_multi_sku[findIndexData] = ({
				'sku_stock_id': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_id').val(),
				'sku_id': $('#item-' + index + '-tr_mutasi_stok_detail-sku_id').val(),
				'sku_stock_qty': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty').val(),
				'sku_stock_expired_date': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_expired_date').val(),
				'pallet_id_asal': $('#item-' + index + '-tr_mutasi_stok_detail-pallet_id_asal').val(),
				'sku_stock_qty_awal': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty_awal').val()
			});

		}
	}

	function UpdateListMutasiStokDetail2(tr_mutasi_stok_detail_id, no_urut, index) {

		// console.log(no_urut);

		const findIndexData = arr_tr_mutasi_stok_detail2.findIndex((value) => value.tr_mutasi_stok_detail_id == tr_mutasi_stok_detail_id && value.no_urut == no_urut);

		// console.log(findIndexData);

		if (findIndexData > -1) {
			arr_tr_mutasi_stok_detail2[findIndexData] = ({
				'tr_mutasi_stok_detail_id': $("#item-" + index + "-tr_mutasi_stok_detail2-tr_mutasi_stok_detail_id").val(),
				'no_urut': $("#item-" + index + "-tr_mutasi_stok_detail2-no_urut").val(),
				'depo_detail_id_tujuan': $("#item-" + index + "-tr_mutasi_stok_detail2-depo_detail_id_tujuan").val(),
				'pallet_id_tujuan': $("#item-" + index + "-tr_mutasi_stok_detail2-pallet_id_tujuan").val(),
				'sku_stock_id_tujuan': "",
				'sku_id': $("#item-" + index + "-tr_mutasi_stok_detail2-sku_id").val(),
				'sku_stock_expired_date': $("#item-" + index + "-tr_mutasi_stok_detail2-sku_stock_expired_date").val(),
				'sku_stock_qty': $("#item-" + index + "-tr_mutasi_stok_detail2-sku_stock_qty").val()
			});
		}

		// console.log(arr_tr_mutasi_stok_detail2);
	}

	function DeleteListSKU(sku_stock_id, index) {

		const findIndexData = arr_list_multi_sku.findIndex((value) => value.sku_stock_id == sku_stock_id);

		if (findIndexData > -1) { // only splice array when item is found
			arr_list_multi_sku.splice(findIndexData, 1); // 2nd parameter means remove one item only
		}
		Get_list_mutasi_stock_detail();

	}

	function DeleteListMutasiStokDetail2(tr_mutasi_stok_detail_id, no_urut, index) {

		const findIndexData = arr_tr_mutasi_stok_detail2.findIndex((value) => value.tr_mutasi_stok_detail_id == tr_mutasi_stok_detail_id && value.no_urut == no_urut);

		if (findIndexData > -1) { // only splice array when item is found
			arr_tr_mutasi_stok_detail2.splice(findIndexData, 1); // 2nd parameter means remove one item only
		}

		Get_list_mutasi_stock_detail2();

	}

	function ResetForm() {
		<?php
		if ($Menu_Access["U"] == 1) {
		?>
			$("#txtupdateMutasiPalletnama").val('');
			$("#cbupdateMutasiPalletjenis").prop('selectedIndex', 0);
			$("#chupdateMutasiPalletsppbr").prop('checked', false);
			$("#chupdateMutasiPallettipeecomm").prop('checked', false);
			$("#chupdateMutasiPalletisactive").prop('checked', false);

			$("#loadingview").hide();
			$("#btnsaveupdatenewMutasiPallet").prop("disabled", false);
		<?php
		}
		?>

		<?php
		if ($Menu_Access["C"] == 1) {
		?>
			$("#txtMutasiPalletnama").val('');
			$("#cbMutasiPalletjenis").prop('selectedIndex', 0);
			$("#chMutasiPalletsppbr").prop('checked', false);
			$("#chMutasiPallettipeecomm").prop('checked', false);
			$("#chMutasiPalletisactive").prop('checked', false);

			$("#loadingview").hide();
			$("#btnsaveaddnewMutasiPallet").prop("disabled", false);
		<?php
		}
		?>

	}
</script>